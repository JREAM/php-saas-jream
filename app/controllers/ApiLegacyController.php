<?php
declare(strict_types=1);

namespace Controllers;

/**
 * @RoutePrefix("/apilegacy/")
 */
class ApiLegacyController extends BaseController
{

    /**
     * ApiController constructor.
     */
    public function onConstruct()
    {
        parent::initialize();
        // From the config/services.php We can use our custom Cookie Wrapper
//        $this->components->cookie;
    }

    // --------------------------------------------------------------

    /**
     * Renders a markdown preview
     *
     * @return  string  json
     */
    public function markdownAction()
    {
        if (!$this->session->has('id')) {
            throw new \DomainException('Only Logged in users can do this.');
        }

        $parsedown = new \Parsedown();
        $content = trim($this->request->getPost('content'));
        if ($content) {
            $content = $parsedown->parse($content);
        }

        return $this->output(1, $content);
    }

    // --------------------------------------------------------------

    // --------------------------------------------------------------

    /**
     * Updates a single field.
     * @param  [type] $table [description]
     * @param  [type] $field [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function adminUpdate($model, $primary_key, $column, $value)
    {
        if (!$this->session->has('id') || $this->session->get('role') != 'admin') {
            die;
        }
    }

    // --------------------------------------------------------------

    public function fakeAction()
    {
        echo json_encode('Fake Output for Testing');
        exit;
    }
}
