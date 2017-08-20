<?php
use \Phalcon\Tag;

class BaseController extends \Phalcon\Mvc\Controller
{

    /**
     * Initializes all the base items for a page
     *
     * @return void
     */
    public function initialize()
    {
        getBaseUrl('/');
        if ($this->session->has('agent')) {
            if ($this->session->get('agent') != $_SERVER['HTTP_USER_AGENT']) {
                $this->flash->error('Please re-login. For your security, we\'ve detected you\'re using a different browser.');
                $this->response->redirect("user/login");
            }
        }



        // $this->_observeActiveSession();
    }

    public function onConstruct()
    {
        // This has a bug duplicating the title
        // Tag::setTitleSeparator(' / ');
        // Tag::appendTitle($this->di['config']['title']);
    }

    // --------------------------------------------------------------

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $this->view->system->info_display = false;

        if ($this->view->system->info_display) {
            $dt = new \DateTime('now', new \DateTimeZone('America/New_York'));
            $this->view->system->info_date = $dt->format('M jS - g:ia') . ' EST';
            $this->view->system->info_message = "The facebook login SDK is currently being worked on.";
        }
    }

    // --------------------------------------------------------------

    /**
     * Simple way to prevent duplicate logins with same userID
     *
     * @return boolean|callable redirect
     */
    protected function _observeActiveSession()
    {
        if (!$this->session->isStarted() || !$this->session->has('id')) {
            return false;
        }

        $user = \User::findFirstById($this->session->get('id'));
        if (!$user) {
            return false;
        }

        if ($user->session_id != $this->session->getId()) {
            $this->session->destroy();
            if ($this->session->has('facebook_id')) {
                $this->facebook->destroySession();
            }

            $this->flash->success('This account is logged in elsewhere. You have been logged out.');
            return $this->redirect('user/login');
        }
    }

    // --------------------------------------------------------------

    /**
     * Redirection
     *
     * @param  string $uri
     *
     * @return void
     */
    public function redirect($append)
    {
        $url = rtrim(\URL, '/');
        if (strlen($append) !== 0) {
            $url .= '/' . ltrim($append, '/');

            // Ensure there are no trailing slashes.
            // This has been an issue.
            $url = trim($url, '/');
        }


        return $this->response->redirect($url, false);
    }

    // --------------------------------------------------------------

    /**
     * JSON Output
     *
     * @param  boolean $result
     * @param  array|object|string $data (Optional)
     *
     * @return string
     */
    protected function output($result, $data = null)
    {
        $output = [];
        $output['result'] = (int) $result;

        if ($result == 0) {
            $output['data']  = null;
            $output['error'] = $data;
        } else {
            $output['data']  = $data;
            $output['error'] = null;
        }

        $response = new \Phalcon\Http\Response();
        $response->setStatusCode(200, "OK");
        $response->setContent(json_encode($output));
        $response->send();
        exit;
    }

    // --------------------------------------------------------------

    public function createSession(\User $user, $additional = [], $remember_me = false)
    {
        // Clear the login attempts
        $user->login_attempt    = null;
        $user->login_attempt_at = null;

        $this->session->set('id', $user->id);
        $this->session->set('role', $user->role);
        $this->session->set('alias', $user->getAlias());

        if (property_exists($user, 'timezone')) {
            $this->session->set('timezone', $user->timezone);
        } else {
            $this->session->set('timezone', 'utc');
        }

        if (is_array($additional)) {
            foreach ($additional as $_key => $_value) {
                $this->session->set($_key, $_value);
            }
        }

        // Delete old session so multiple logins aren't allowed
        session_regenerate_id(true);

        $user->session_id = $this->session->getId();
        $user->save();

        // If the user changes web browsers, prevent a hijacking attempt
        $this->session->set('agent', $_SERVER['HTTP_USER_AGENT']);
    }

    // --------------------------------------------------------------

    /**
     * Logs a user out here and with a service if applicable
     *
     * @return void
     */
    public function destroySession()
    {

        if ($this->session->has('facebook_id')) {
            $this->session->destroy();
            $this->facebook->destroySession();
            $this->facebook->setAccessToken('');
            return $this->response->redirect($this->facebook->getLogoutUrl(), true);
        }

        $this->session->destroy();
    }

    // --------------------------------------------------------------
}


/**
 * Batch Mockup
 * Allows batch inserts
 *
 * @usage
 *     $batch = new Batch('stats');
 *     $batch->columns = ['score', 'name'];
 *     $batch->data = [
 *         [1, 'john'],
 *         [4, 'fred'],
 *         [1, 'mickey'],
 *     ];
 *     $batch->insert();
 *
 */
class Batch
{
    /** @var string */
    public $table = null;

    /** @var array */
    public $rows = [];

    /** @var array */
    public $values = [];

    // --------------------------------------------------------------

    public function __construct($table = false)
    {
        if ($table) {
            $this->table = (string) $table;
        }

        $di = Phalcon\DI::getDefault();
        $this->db = $di->get('db');

        return $this;
    }

    // --------------------------------------------------------------

    /**
     * Set the Rows
     *
     * @param array $rows
     *
     * @return object Batch
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
        $this->rowsString = sprintf('`%s`', implode('`,`', $this->rows));

        return $this;
    }

    // --------------------------------------------------------------

    /**
     * Set the values
     *
     * @param $values Array of Arrays
     *
     * @return object Batch
     */
    public function setValues($values)
    {
        if (!$this->rows) {
            throw new \Exception('You must setRows() before setValues');
        }
        $this->values = $values;

        $valueCount = count($values);
        $fieldCount = count($this->rows);

        // Build the Placeholder String
        $placeholders = [];
        for ($i = 0; $i < $valueCount; $i++) {
            $placeholders[] = '(' . rtrim(str_repeat('?,', $fieldCount), ',') . ')';
        }
        $this->bindString = implode(',', $placeholders);

        // Build the Flat Value Array
        $valueList = [];
        foreach ($values as $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $valueList[] = $v;
                }
            } else {
                $valueList[] = $values;
            }
        }
        $this->valuesFlattened = $valueList;
        unset($valueList);

        return $this;
    }

    // --------------------------------------------------------------

    /**
     * Insert into the Database
     *
     * @param boolean $ignore Use an INSERT IGNORE (Default: false)
     *
     * @return void
     */
    public function insert($ignore = false)
    {
        $this->_validate();

        // Optional ignore string
        if ($ignore) {
            $insertString = "INSERT IGNORE INTO `%s` (%s) VALUES %s";
        } else {
            $insertString = "INSERT INTO `%s` (%s) VALUES %s";
        }

        $query = sprintf(
            $insertString,
            $this->table,
            $this->rowsString,
            $this->bindString
        );

        $this->db->execute($query, $this->valuesFlattened);
    }

    // --------------------------------------------------------------

    /**
     * Validates the data before calling SQL
     *
     * @return void
     */
    private function _validate()
    {
        if (!$this->table) {
            throw new \Exception('Batch Table must be defined');
        }

        $requiredCount = count($this->rows);

        if ($requiredCount == 0) {
            throw new \Exception('Batch Rows cannot be empty');
        }

        foreach ($this->values as $value) {
            if (count($value) !== $requiredCount) {
                throw new \Exception('Batch Values must match the same column count of ' . $requiredCount);
            }
        }
    }

    // --------------------------------------------------------------
}

// End of File
// --------------------------------------------------------------
