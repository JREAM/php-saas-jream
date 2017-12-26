<?php

declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;
use \Models\User;
use \Models\NewsletterSubscription;

class PromotionController extends ApiController
{

    public function onConstruct()
    {
        parent::initialize();
    }

    public function selectItemAction(): void
    {
        // Array of Items
        $this->json;
        $this->json->item;

        $items = $this->input->post('item');
    }
}
