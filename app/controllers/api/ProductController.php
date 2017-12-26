<?php

declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;

class ProductController extends ApiController
{

    public function onConstruct()
    {
        parent::initialize();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    public function getAction($productId = false)
    {
        $this->apiMethods(['GET']);

        if ($productId) {
            $product = \Product::findFirstById($productId);
            if ($product) {
                return $this->output(1, 'all products', $product);
            }
            return $this->output(0, 'Product not found');
        }
        return $this->output(1, \Product::find(['is_deleted' => false]));
    }
}
