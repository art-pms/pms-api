<?php

namespace Pms\Api\Model;

use Pms\Api\Library\AbstractModel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;

class Role extends AbstractModel
{
    public static $useType = false;
    static $collection = "Role";

    protected static $attrs = array(
        'name' => array('type' => 'string'),
    );

    public function read()
    {
        $items = $this::find();
        return $items;
    }
}