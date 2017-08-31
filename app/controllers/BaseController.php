<?php
declare(strict_types=1);

namespace Controllers;

use Library\TokenManager;
use Phalcon\Tag;
use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    /**
     * @var \Phalcon\Filter
     */
    protected $filter;

    /**
     * @var TokenManager
     */
    protected $tokenManager;

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

        $this->filter = $this->di->get('filter');
        $this->tokenManager = new TokenManager();
    }

    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        // --------------------------------------------------------------
        // Generate User Sessions CSRF Tokens
        // --------------------------------------------------------------
        // 1: Create a user-session CSRF Token Pair if one does NOT exist.
        // .. All Users signed in or not must have a CSRF token.
        // --------------------------------------------------------------
        if (!$this->tokenManager->hasToken()) {
            // Creates session data.
            $this->tokenManager->generate();
        }

        if ($this->request->isMethod('post')) {
            // Check every POST (Non-XHR and XHR) to have CSRF
            $this->validateTokens();
        }
    }

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        // Set the Page ID  for FrontEnd
        $this->view->setVar('pageId', sprintf('%s-%s', 'page', $this->generateBodyPageId()));

        // Create a random number for   cache busting in non-production
        $cacheBust = false;
        if (\APPLICATION_ENV !== \APP_PRODUCTION) {
            $cacheBust = '?v=' . random_int(100000, 999999);
        }
        $this->view->setVar('cacheBust', $cacheBust);

        // Set the CSRF for every request (It uses a unique key/pair token per user session)
        $this->view->setVars([
            'tokenKey'  => $this->tokenManager->getTokens()['tokenKey'],
            'token'     => $this->tokenManager->getTokens()['token'],
            'prefs'     => [
                'user_id'      => $this->session->get('id')
            ]
        ]);

    }

    /**
     * Used for the Views, sets a PageID variable
     *
     * @return string
     */
    protected function generateBodyPageId()
    {
        $pageId = $this->di->get('router')->getControllerName();
        $action_name = $this->di->get('router')->getActionName();
        if ($action_name !==  '') {
            $pageId .= '-' . $this->di->get('router')->getActionName();
        }
        // Remove the "Index" or default home page.
        if (strpos($pageId, '-index') !== -1) {
            $pageId = str_replace('-index', '', $pageId);
        }

        return strtolower($pageId);
    }

    /**
     * Redirection
     *
     * @param  string   $append  Add the the full URL
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

    public function __construct($table = false)
    {
        if ($table) {
            $this->table = (string) $table;
        }

        $di = \Phalcon\DI::getDefault();
        $this->db = $di->get('db');

        return $this;
    }

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

}
