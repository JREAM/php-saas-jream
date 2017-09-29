<?php

namespace Forum;

use BaseModel;

class ForumCategory extends BaseModel
{
    public $id;
    public $title;
    public $description;
    public $is_deleted;
    public $deleted_at;
    public $updated_at;
    public $created_at;

    public function initialize(): void
    {
        $this->hasMany("post_id", "ForumPost", "id");
    }
}
