<?php

namespace Models\Traits;

/**
 * Trait TimeStamp
 */
trait TimestampTrait
{

    public $dateFormat = 'Y-m-d H:i:s';

    // -----------------------------------------------------------------------------

    public function beforeCreate()
    {
        $this->created_at = date($this->dateFormat);
    }

    // ----------------------------------------------------------------------------

    public function beforeUpdate()
    {
        $this->updated_at = date($this->dateFormat);
    }

    // ----------------------------------------------------------------------------

    public function afterDelete()
    {
        $this->is_deleted = (int) 1;
        $this->deleted_at = date($this->dateFormat);
    }

    // ----------------------------------------------------------------------------

}
