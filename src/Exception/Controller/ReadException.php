<?php

namespace Pms\Api\Exception\Controller;

use Pms\Api\Exception\AbstractException;

class ReadException extends AbstractException
{
    public function __construct(array $filter = array(), array $sort = array(), array $fields = array(), $limit = 0, $skip = 0, $message = '')
    {
        $this->message = $message ? : 'Read failed';
        $this->filter = $filter;
        $this->fields = $fields;
        $this->sort = $sort;
        $this->limit = $limit;
        $this->skip = $skip;
    }
}