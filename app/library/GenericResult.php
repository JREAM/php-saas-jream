<?php

namespace Library;

class GenericResult
{
    protected $result = 0;
    protected $error = '';
    protected $data = [];

    /**
     * GenericResult constructor.
     *
     * @param int   $result
     * @param mixed $data Error or Data
     */
    public function __construct(int $result = 0, $data)
    {
        $this->result = (int) (bool) $result;

        if ($result == 0) {
            $this->data  = null;
            $this->error = $data;
        } else {
            $this->data  = $data;
            $this->error = null;
        }
    }

    public function __toString()
    {
        return (int) $this->result;
    }
}
