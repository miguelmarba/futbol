<?php

/**
 * Description of Usuario
 *
 * @author Miguel A. Martinez
 */

namespace Monitoreo\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql,
        Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class Monitoreo extends TableGateway 
{
    
    protected $table ='sys_usuarios';
//     public function __construct(Adapter $adapter = null, $databaseSchema = null, 
//        ResultSet $selectResultPrototype = null)
//    {
//        return parent::__construct('sys_usuarios', $adapter, $databaseSchema, 
//            $selectResultPrototype);
//    }
    
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
    
    public function getUserPersonal($id = 3)
    {
        $sql = new Sql($this->adapter);

    $select = $sql->select();
    $select->from('sys_usuarios')
        ->columns(array('id_usuario', 'username', 'fecha_cambio_password'))
        ->join(array('b' => 'cat_personal'), 'id_personal = b.id_personal', array('nombre_user' => 'nombre'), \Zend\Db\Sql\Select::JOIN_INNER);
    $resultSet = $this->tableGateway->selectWith($select);
    return $resultSet;
    }

}

?>
