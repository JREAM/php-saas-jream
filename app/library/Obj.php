<?php

namespace Library;

class Obj
{
    protected $result = 0;
    protected $msg = '';
    protected $error = '';
    protected $data;

    public function __construct(int $result = 0, string $msg = '')
    {
        $this->result = (int) (bool) $result;
        $this->msg = $msg;
    }

    public function __toString()
    {
        return $this->result;
    }
}
