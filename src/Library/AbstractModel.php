<?php

namespace Pms\Api\Library;

use \Purekid\Mongodm\Model;
use MongoId;

abstract class AbstractModel extends Model
{
    /**
     * @param array $criteria
     * @param array $sort
     * @param array $fields
     * @param null $limit
     * @param null $skip
     * @return mixed
     * @throws \Exception
     */
    public static  function find($criteria = array(), $sort = array(), $fields = array() , $limit = null , $skip = null)
    {
        $criteria = self::setReference($criteria);

        if(isset($criteria['_id']) && !($criteria['_id'] instanceof MongoId)){
            try {
                $criteria['_id'] = new MongoId($criteria['_id']);
            } catch (\Exception $e) {
                throw new \Exception(sprintf('Wrong id [%s].', $criteria['_id']), 404);
            }
        }

        return parent::find($criteria, $sort, $fields, $limit, $skip);
    }


    /**
     * @param array $criteria
     * @param array $fields
     * @return mixed
     * @throws \Exception
     */
    public static function one($criteria = array(),$fields = array())
    {
        $criteria = self::setReference($criteria);

        if(isset($criteria['_id']) && !($criteria['_id'] instanceof MongoId)){
            try {
                $criteria['_id'] = new MongoId($criteria['_id']);
            } catch (\Exception $e) {
                throw new \Exception(sprintf('Wrong id [%s].', $criteria['_id']), 404);
            }
        }
        return parent::one($criteria, $fields);
    }

    /**
     * @param $criteria
     * @return mixed
     */
    private static function setReference($criteria)
    {
        $attrs = parent::getAttrs();
        foreach($criteria as $key => $value) {
            if(isset($attrs[$key])
                && isset($attrs[$key]['type'])
                && ( $attrs[$key]['type'] == parent::DATA_TYPE_REFERENCE
                    || $attrs[$key]['type'] == parent::DATA_TYPE_REFERENCES ) ) {

                if ($attrs[$key]['type'] == self::DATA_TYPE_REFERENCE){
                    $criteria[$key.'.$id'] = new MongoId($value);
                    unset($criteria[$key]);
                } elseif ($attrs[$key]['type'] == self::DATA_TYPE_REFERENCES) {
                    $criteria[$key] = array(
                        '$elemMatch' => array(
                            '$id' => new MongoId($value)
                        )
                    );
                }

            }
        }
        return $criteria;
    }

}