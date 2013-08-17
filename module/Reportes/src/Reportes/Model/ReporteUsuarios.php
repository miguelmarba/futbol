<?php

/**
 * Sabe
 *
 * @author Alika Mena
 */

namespace Reportes\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Sql\Sql,
        Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class ReporteUsuarios extends TableGateway 
{
    
    protected $table ='sys_usuarios';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }
    
    public function fetchAll(Select $select = null)
    {
//        $resultSet = $this->select('cp = 43050');
//        return $resultSet->toArray();
        
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getReporteLlamadasPorOperador($id = '')
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select();
        $select->from(array('a' => 'sys_usuarios'))
                ->columns(array('id_usuario', 'username' ))
                ->join(array('b' => 'cat_personal'), 'a.id_personal = b.id_personal',
                       array('nombre' => 'nombre', 
                             'apaterno' => 'a_paterno', 
                             'amaterno' => 'a_materno', 
                            ), \Zend\Db\Sql\Select::JOIN_INNER)
                ->join(array('c' => 'sys_roles'), 'b.id_rol = c.id_rol', array('perfil' => 'descripcion'), \Zend\Db\Sql\Select::JOIN_INNER);
        
        //$where = new Where();
        //$where->equalTo('cp', $cp) ;
        //$select->where($where);
        
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