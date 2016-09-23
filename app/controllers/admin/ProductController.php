<?php
namespace Admin;
use \Phalcon\Tag;

class ProductController extends \BaseController
{
    // --------------------------------------------------------------

    const REDIRECT_SUCCESS = 'admin/product';
    const REDIRECT_FAILURE = 'admin/product';

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

        $this->view->setVars([
            'products' => \Product::find(),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);
        $this->view->pick('admin/product');
    }

    // --------------------------------------------------------------

    public function createAction()
    {
        $this->view->setVars([
            'form' => new \AdminProductForm(),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);
        $this->view->pick('admin/product-create');
    }

    // --------------------------------------------------------------

    public function editAction($id)
    {
        $courses =  \ProductCourse::findByProductId($id);

        $this->view->setVars([
            'form' => new \AdminProductForm(),
            'product' => \Product::findFirstById($id),
            'courses' => \ProductCourse::findByProductId($id),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);
        $this->view->pick('admin/product-edit');
    }

    // --------------------------------------------------------------

    public function editCourseAction($productId, $courseId)
    {
        $this->view->setVars([
            'formCourse' => new \AdminCourseForm(),
            'formMeta'   => new \AdminCourseMetaForm(),
            'product'    => \Product::findFirstById($productId),
            'course'     => \ProductCourse::findFirstById($courseId),
            'meta'       => \ProductCourseMeta::findByProductCourseId($courseId),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken()
        ]);
        $this->view->pick('admin/product-course-edit');
    }

    // --------------------------------------------------------------

    public function doMetaEditAction($metaId)
    {
        $meta = \ProductCourseMeta::findFirstById($metaId);
        $meta->type        = $this->input->getPost('type');
        $meta->resource      = $this->input->getPost('resource');
        $meta->content     = $this->input->getPost('content');
        $meta->description = $this->input->getPost('description');
        $result = $meta->update();

        if ($result) {
            $this->output(1);
        }

        $this->output(0);
    }

    // --------------------------------------------------------------

    public function doMetaAddAction($productId, $courseId)
    {
        $meta = new \ProductCourseMeta();
        $meta->product_course_id = $courseId;
        $meta->type              = $this->request->getPost('type');
        $meta->resource            = $this->request->getPost('resource');
        $meta->content           = $this->request->getPost('content');
        $meta->description       = $this->request->getPost('description');
        $meta->save();

        if ($meta->getMessages()) {
            $this->flash->error('Error Saving');
        } else {
            $this->flash->error('Meta Item Added');
        }

        return $this->redirect(self::REDIRECT_SUCCESS . '/editcourse/' . $productId . '/' . $courseId);
    }

    // --------------------------------------------------------------

    public function doDeleteMeta($metaId) {
        $meta = \ProductCourseMeta::findFirstById($metaId);
        $meta->delete();

        if ($meta->getMessages()) {
            $this->flash->error('Error Deleting');
        } else {
            $this->flash->error('Meta Item Removed');
        }

        return $this->redirect(self::REDIRECT_SUCCESS . '/editcourse/' . $productId . '/' . $courseId);
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------