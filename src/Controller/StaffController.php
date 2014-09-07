<?php

namespace Pms\Api\Controller;

use Pms\Api\Model\Staff;

class StaffController
{

    public function create(array $data)
    {
        $model = new Staff();

        $staff = $model->create($data);

        return $staff;
    }

    public function read()
    {
        $model = new Staff();

        $items = $model->read();

        return $items;
    }
}
