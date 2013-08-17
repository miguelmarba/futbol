<?php

/**
 * Clase de Identidad Codigo Postal
 *
 * @author Miguel A. Martínez
 */

namespace Administrator\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql,
        Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class Cp extends TableGateway 
{
    
    protected $table ='tbl_localidades';
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
        $resultSet = $this->select('cp = 43050');
        return $resultSet->toArray();
    }
    
    public function findByCp($cp = '')
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select();
        $select->from(array('a' => 'tbl_localidades'))
                ->columns(array('id_localidad', 'cp', 'descripcion', 'id_estado', 'id_municipio' ))
                ->join(array('b' => 'tbl_estados'), 'a.id_estado = b.id_estado', array('estado' => 'descripcion'), \Zend\Db\Sql\Select::JOIN_INNER)
                ->join(array('c' => 'tbl_municipios'), 'a.id_municipio = c.id_municipio AND a.id_estado = c.id_estado', array('municipio' => 'descripcion'), \Zend\Db\Sql\Select::JOIN_INNER);
        
        $where = new Where();
        $where->equalTo('cp', $cp) ;
        $select->where($where);
        //echo $select->getSqlString();exit;
        
//        $resultSet = $this->tableGateway->selectWith($select);
//        return $resultSet;
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
 
        return $result; 
        
        
        
    }
    
    public function getMunicipios($seleccionado = '')
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select();
        $select->from(array('a' => 'tbl_estados'))
                ->columns(array('id_estado', 'descripcion'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
 
        return $result;
    }
    
    public function findMunicipiosByEstado($id_estado = '')
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select();
        $select->from(array('a' => 'tbl_municipios'))
                ->columns(array('id_municipio', 'descripcion'));
                
        $where = new Where();
        $where->equalTo('id_estado', $id_estado) ;
        $select->where($where);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
 
        return $result; 
    }
    
    public function findColonias($id_estado, $id_municipio)
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select();
        $select->from(array('a' => 'tbl_localidades'))
                ->columns(array('id_localidad', 'descripcion'));
                
        $where = new Where();
        $where->equalTo('id_estado', (int)$id_estado);
        $where->equalTo('id_municipio', (int)$id_municipio) ;
        $select->where($where);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
 
        return $result; 
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