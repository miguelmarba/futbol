<?php

/**
 * Description of Album
 *
 * @author Miguel A. Martínez
 */

namespace Reportes\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql,
        Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class Album extends TableGateway 
{
    
    protected $table ='album';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }
    
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }
}
