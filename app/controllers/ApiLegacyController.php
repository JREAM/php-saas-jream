<?php
declare(strict_types=1);

namespace Controllers;

class ApiLegacyController extends BaseController
{
    /**
     * ApiController constructor.
     */
    public function onConstruct()
    {
        parent::initialize();
    }

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

}
