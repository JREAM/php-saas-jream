<?php

namespace Forum;

use BaseModel;

class ForumPost extends BaseModel
{
    public $id;
    public $parent_id;
    public $title;
    public $slug;
    public $view_count;
    public $reply_count;
    public $threshold;
    public $is_approved;
    public $is_deleted;
    public $deleted_at;
    public $updated_at;
    public $created_at;

    public function initialize(): void
    {
        $this->belongsTo("category_id", "ForumCategory", "id");
        $this->hasMany("id", "ForumPost", "id");
        //$this->hasMany("tag_id", "ForumPostTagRelation", "id");
    }
}
