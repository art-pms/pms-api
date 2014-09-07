<?php

namespace Pms\Api\Library;

use Pms\Api\Application;

abstract class AbstractWebAction
{
    protected $_app;

    public function __construct()
    {
        //$this->_app =
    }

    protected function _parseQueryValue($value)
    {
        if($value === 'false')
            $value = false;
        else if($value === 'true')
            $value = true;
        else if($value === 'null')
            $value = null;
        else if(is_numeric($value))
            $value = $value + 0;

        if(strpos($value, 'ObjectId(') === 0)
        {
            $value = new MongoId(substr($value, 9, -1));
        }

        if(strpos($value, 'DateISO(') === 0)
        {
            $value = new MongoDate(strtotime(substr($value, 8, -1)));
        }

        return $value;
    }

    /**
     * Prep resource
     */
    protected function _prepResource($resources)
    {

        if($resources instanceof \Purekid\Mongodm\Collection) {
            $resources = $resources->toArray(true, true);
        }

        if($resources instanceof \Purekid\Mongodm\Model) {
            $resources = $resources->toArray();
        }

        if(method_exists($resources, 'toArray')) {
            $resources = $resources->toArray();
        }

        // Single into multiple
        if(isset($resources['_id']))
        {
            $oneResource = true;
            $resources = array($resources);
        }

        if(is_scalar($resources) || is_null($resources)) {
            return $resources;
        }

        foreach($resources as &$resource)
        {
            if(is_array($resource)) {
                array_walk_recursive($resource, function(&$val, $key){
                    if($val instanceof \MongoId) {
                        $val = (string) $val;
                    }
                    if($val instanceof \MongoDate) {
                        $val = date('c', $val->sec);
                    }
                    if($val instanceof \DateTime) {
                        $val = $val->format('c');
                    }
                    if(method_exists($val, 'toArray')) {
                        $val = $val->toArray();
                    }
                });

                if(isset($resource['_id'])) {
                    $resource['id'] = $resource['_id'];
                }
                unset($resource['_id']);
            }
        }
        unset($resource);

        if(isset($oneResource))
        {
            return $resources[0];
        }

        return $resources;
    }

    protected function _response($resource, $code = 200)
    {
        $resource = $this->_prepResource($resource);
        return $this->_app->json($resource, $code);
    }
}