<?php
declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;
use \Omnipay\Omnipay;

class ProductController extends BaseController
{
    const REDIRECT_MAIN = 'product';

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        Tag::setTitle('Products | ' . $this->di['config']['title']);

        // Stripe key already set in services.php

        // Paypal Express
        $this->paypal = $this->di->get('paypal');
    }

    /**
     * @return void
     */
    public function indexAction() : void
    {
        $products = \Product::find(['is_deleted = 0 ORDER BY status DESC']);

        $this->view->setVars([
            'products' => $products,
        ]);
    }

    /**
     * Displays a Product based on the slug
     *
     * @param  string   $slug  URL Friendly Slug
     *
     * @return void
     */
    public function courseAction(string $slug) : void
    {
        $product = \Product::findFirstBySlug($slug);
        Tag::setTitle($product->title . ' | ' . $this->di['config']['title']);

        if (!$product) {
            return $this->redirect(self::REDIRECT_MAIN);
        }

        $discount = null;
        $discount_price = null;
        $promotion_code = $this->request->get('promotion_code');
        if ($promotion_code) {
            $promotion = new \Promotion();
            $percent_off = $promotion->check($promotion_code, $product->id);
            if ($percent_off->code !== 0) {
                // Keep the promo on the users incase they refresh the page
                $this->promotion_code = $promotion_code;

                // this sets the price discount
                // Security
                $this->session->set('session_hash', $this->security->hash(
                    $this->config->session_hash,
                    $this->session->getId()
                ));

                // Price
                $discount_price = $product->price * ($percent_off * .1);
                $this->session->set('discount', $percent_off);
                $this->session->set('discount_price', $discount_price);
            }
        }

        $courses = \ProductCourse::find([
            "product_id = :product_id:",
            "bind"  => [
                'product_id' => $product->id,
            ],
            "order" => 'section, course',
        ]);

        // ---------------------------
        // Facebook Login
        // ---------------------------
        $after_fb = sprintf('/product/course/%s', $slug);
        $helper = $this->facebook->getRedirectLoginHelper();
        $fbLoginUrl = $helper->getLoginUrl(
            $this->api->fb->redirectUri,
            (array) $this->api->fb->scope
        );

        // ---------------------------
        // End Facebook
        // ---------------------------

        $user = false;
        if ($this->session->has('id')) {
            $user = \User::findFirstById($this->session->get('id'));
        }

        $months = [
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $this->view->setVars([
            'user'           => $user,
            'product'        => $product,
            'courses'        => $courses,
            'promotion_code' => $promotion_code,
            'discount_price' => $discount_price,
            'hasPurchased'   => $product->hasPurchased(),
            'fbLoginUrl'     => $fbLoginUrl,
            'months'         => $months,
            'years'          => range(date('Y'), date('Y') + 5),
        ]);
    }

    /**
     * Preview a Course
     *
     * @param string $productSlug
     * @param int    $courseId
     */
    public function coursePreviewAction(string $productSlug, int $courseId) : void
    {
        $rtmpSignedUrl = null;
        $error = null;

        $product = \Product::findFirstBySlug($productSlug);
        $productCourse = \ProductCourse::findFirstById($courseId);
        if (!$product || !$productCourse) {
            $this->flash->error('This product and/or course does not exist');

            return $this->redirect(self::REDIRECT_MAIN);
        }

        if ($productCourse->free_preview == 1) {
            $rtmpSignedUrl = \ProductCourse::generateStreamUrl(
                $productCourse->getProduct()->path,
                $productCourse->name
            );
        } else {
            $error = 'There is no preview for this course, please purchase at the product area.';
        }

        Tag::setTitle(formatName($productCourse->name) . ' | Product Preview | ' . $this->di['config']['title']);

        $this->view->setVars([
            'rtmpSignedUrl' => $rtmpSignedUrl,
            'error'         => $error,
            'product'       => $product,
            'productCourse' => $productCourse,
            'courseName'    => formatName($productCourse->name),
        ]);

        $this->view->pick('product/preview');
    }

}
