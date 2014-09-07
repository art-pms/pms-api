<?php

namespace Pms\Api\Exception\Controller;
use Pms\Api\Exception\AbstractException;

/**
 * Class CreateException
 * @package Pms\Api\Exception\Controller
 */
class CreateException extends AbstractException
{
    /**
     * @param array $data
     * @param string $message
     */
    public function __construct(array $data, $message = '')
    {
        $this->message = $message ?: 'Create failed';
        $this->data = $data;
    }
}