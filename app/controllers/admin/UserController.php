<?php
namespace Admin;
use \Phalcon\Tag;

class UserController extends \BaseController
{

    const REDIRECT_SUCCESS = 'admin/user';
    const REDIRECT_FAILURE = 'admin/user';

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        if (!$this->session->has('id') || $this->session->get('role') != 'admin') {
            $this->redirect('index');
        }

        parent::initialize();
        Tag::setTitle('Admin');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $page = (int) $this->request->get("page");
        $page = (!$page) ? 1 : (int) $page;

        $builder = $this->modelsManager->createBuilder()
            ->columns('id, alias, facebook_alias, timezone, banned, created_at')
            ->from('User')
            ->orderBy('id');

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder([
            "builder" => $builder,
            "limit"=> 50,
            "page" => $page
        ]);

        // Get the paginated results
        $page = $paginator->getPaginate();

        $this->view->setVars([
            'page' => $page,
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken()
        ]);

        $this->view->pick('admin/user');
    }

    // --------------------------------------------------------------

    public function editAction($id)
    {
        $id = (int) $id;
        $user = \User::findFirstById($id);
        $this->view->setVars([
            'form'      => new \AdminUserForm($user, ['edit' => true]),
            'user'      => $user,
            'purchases' => \UserPurchase::findByUserId($this->session->get('id')),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken()
        ]);
        $this->view->pick('admin/user-edit');
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------