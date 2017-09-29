<?php

namespace Forum;

use BaseModel;

class ForumPostTag extends BaseModel
{
    public $id;
    public $name;
    public $slug;
    public $post_count;
    public $is_approved;
    public $is_deleted;
    public $deleted_at;
    public $updated_at;
    public $created_at;


    public function initialize(): void
    {
        $this->belongsTo("post_id", "ForumPost", "id");
    }
}
