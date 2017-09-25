<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;

class Youtube extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $title;
    public $description;
    public $video_id;
    public $img_sm;
    public $img_md;
    public $img_lg;
    public $is_deleted;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
    {
        /** DB Table Name */
        $this->setSource('youtube');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));
    }

    // -----------------------------------------------------------------------------

}
