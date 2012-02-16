<?php

class ApplicationTable extends Zend_Db_Table_Abstract
{

    public function __construct ($config = array())
    {
        $resource = Bootstrap::instance()->getPluginResource('multidb');
        $this->_db = $resource->getDb('db1');
        parent::__construct($config);
    }
    
}