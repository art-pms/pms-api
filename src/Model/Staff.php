<?php

namespace Pms\Api\Model;

use Pms\Api\Library\AbstractModel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;

class Staff extends AbstractModel
{
    public static $useType = false;
    static $collection = "Staff";

    protected static $attrs = array(
        'email' => array('type' => 'string'),
        'password' => array('type' => 'string'),
        'firstName' => array('type' => 'string'),
        'lastName' => array('type' => 'string'),
        'gender' => array('type' => 'string'),
        'role' => array('type' => 'string')
    );

    public function validate()
    {
        $data = $this->toArray();

        $validator = Validation::createValidator();


        $constraint = new Assert\Collection(
            array(
                '_id' => new Assert\Optional(new Assert\Length(array('min' => 24))),
                'email' => array(
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Callback(function ($object, ExecutionContextInterface $context) {
                        $staff = self::one(array("email" => $this->email));
                        if ($staff && ($staff->getId() <> $this->getId()))
                            $context->addViolationAt(
                                'email',
                                'Staff with the same email already exist!',
                                array(),
                                null
                            );
                    })
                ),
                'firstName' => array(
                    new Assert\Length(array('min' => 3)),
                    new Assert\NotBlank()
                ),
                'gender' => array(
                    new Assert\Type(array('type' => 'string')),
                    new Assert\NotBlank()
                ),
                'lastName' => array(
                    new Assert\Length(array('min' => 3)),
                    new Assert\NotBlank()
                ),
                'role' => array(
                    new Assert\Type(array('type' => 'string')),
                    new Assert\NotBlank()
                )
            )
        );

        return $validator->validateValue($data, $constraint);
    }

    public function __preSave()
    {
        $this->email = strtolower($this->email);
        $errors = $this->validate();
        if (count($errors) > 0)
            throw new ModelException($errors);
        parent::__preInsert();
    }

    public function create(array $data)
    {
        //TODO: To be improved
        if(isset($data['password']) && !empty($data['password'])){
            $data['password'] = md5($data['password']);
        }

        $this->update($data);
        $this->save();

        return $this;
    }

    public function updateStaff(array $data)
    {
        //TODO: To be improved
        if(isset($data['password']) && !empty($data['password'])){
            $data['password'] = md5($data['password']);
        }

        $criteria = array(
            '_id' => $data['_id'],
            '_dOn' => array(
                '$exists' => false
            )
        );

        $staff = $this::one($criteria);
        unset($data['_id']);
        $staff->update($data);
        $staff->save();

        return $staff;
    }

    public function read()
    {
        $criteria = array(
            '_dOn' => array(
                '$exists' => false
            )
        );

        $fields = array(
            'email', 'firstName', 'lastName', 'gender', 'role'
        );

        $sort = array();

        $items = $this::find($criteria, $sort, $fields);

        if($items) {
            return $items->toArray();
        } else {
            throw new \Exception('No staff was found.', 404);
        }
    }

    public function readOne($staffId)
    {
        $criteria = array(
            '_id' => $staffId,
            '_dOn' => array(
                '$exists' => false
            )
        );

        $fields = array(
            'email', 'firstName', 'lastName', 'gender', 'role'
        );

        $staff = $this::one($criteria, $fields);

        if($staffId) {
            return $staff->toArray(array(), true);
        } else {
            return array();
        }
    }

}