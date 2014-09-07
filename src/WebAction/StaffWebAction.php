<?php

namespace Pms\Api\WebAction;

use Pms\Api\Library\AbstractWebAction;
use Pms\Api\Controller\StaffController;


class StaffWebAction extends AbstractWebAction
{

    protected $_ctrlName = 'Staff';

    public function __construct($app)
    {
        $this->_app = $app;
    }

    public function create()
    {
        $data = $this->_app['request']->requset->getAll();
        $ctrl = new StaffController();
        $staff = $ctrl->create($data);

        return $this->_response($staff);
    }

    public function read()
    {
        $ctrl = new StaffController();
        $items = $ctrl->read();

        return $this->_response($items);
    }
}