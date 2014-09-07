<?php

namespace Pms\Api\Model;


use Pms\Api\Models\AbstractModel;
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
        'phone' => array('type' => 'string'),
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
                'phone' => array(
                    new Assert\Optional(new Assert\Type(array('type' => 'string')))
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

    public function addStaff(array $data)
    {

    }
}