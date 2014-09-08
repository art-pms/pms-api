<?php

namespace Pms\Api\WebAction;

use Pms\Api\Library\AbstractWebAction;
use Pms\Api\Model\Role;

class RoleWebAction extends AbstractWebAction
{
    protected $_ctrlName = 'Role';

    public function __construct($app)
    {
        $this->_app = $app;
    }

    public function read()
    {
        $model = new Role();
        $role = $model->read();

        return $this->_response($role);
    }
}