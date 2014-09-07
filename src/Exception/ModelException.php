<?php

namespace Pms\Api\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ModelException
 * @package Pms\Api\Exception
 */
class ModelException extends AbstractException
{
    /**
     * @param ConstraintViolationList $errors
     * @param string $message
     */
    public function __construct(ConstraintViolationList $errors, $message = '')
    {
        $this->message = $message ?: (string) $errors;
    }
}