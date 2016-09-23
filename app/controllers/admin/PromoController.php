<?php
namespace Admin;
use \Phalcon\Tag;

class PromoController extends \BaseController
{
    const REDIRECT_SUCCESS = 'admin/promo';
    const REDIRECT_FAILURE = 'admin/promo/create';

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

    public function indexAction()
    {
        $this->view->setVars([
            'promos' => \ProductPromo::find(),
        ]);
        $this->view->pick('admin/promo');
    }

    // --------------------------------------------------------------

    public function createAction()
    {

        $user = \User::find();

        $this->view->setVars([
            'products' => \Product::find(),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);

        $this->view->pick('admin/promo_create');
    }

    // --------------------------------------------------------------

    public function doCreateAction()
    {
        $product     = $this->request->getPost('product');
        $code        = $this->request->getPost('code');
        $percent_off = $this->request->getPost('percent_off');
        $is_visible  = $this->request->getPost('is_visible');
        $expires_on  = $this->request->getPost('expires_on');

        $promo              = new \ProductPromo();
        $promo->product     = $product;
        $promo->code        = $code;
        $promo->is_visible  = $is_visible;
        $promo->percent_off = $percent_off;
        $promo->expires_on  = $expires_on;
        $result = $promo->save();

        if ($result) {
            $this->flash->success('Promo Created!');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $this->flash->error($promo->getMessagesList());
        return $this->redirect(self::REDIRECT_FAILURE);
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------