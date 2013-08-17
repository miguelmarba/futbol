<?php
namespace Administrator\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
 
use Zend\Db\Sql\Sql,
    Zend\Db\Sql\Where;

use Zend\Db\Sql\Select;

use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class RecursoTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function listarModulos()
    {
        $resultSet = $this->tableGateway->select( 'id_padre = 0 AND eliminado = 0' );
        return $resultSet;
    }
    public function listarDetalles()
    {
//        $select = new Select(array('a' =>'sys_recursos'));
//        $select->where('a.eliminado = 0');
//        $select->join(array('b' =>'sys_recursos'), 'a.id_padre = b.id_recurso', array('hereda' => 'nombre'), \Zend\Db\Sql\Select::JOIN_LEFT);
//        
//        $resultSet = $this->tableGateway->select($select);
//        //var_dump($resultSet);exit;       
//        //echo $select->getSqlString();exit;
//        return $resultSet;
        
        $sql = new Sql($this->tableGateway->getAdapter());

        $select = $sql->select();
        $select->from(array('a' => 'sys_recursos'))
                ->columns(array('id_recurso', 'id_padre', 'nombre', 'descripcion', 'eliminado' ))
                ->join(array('b' => 'sys_recursos'), 'a.id_padre = b.id_recurso',
                       array('hereda' => 'nombre'), \Zend\Db\Sql\Select::JOIN_LEFT)
                ->where('a.eliminado = 0');
        
        //$where = new Where();
        //$where->equalTo('cp', $cp) ;
        //$select->where($where);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
 
        return $result;
    }
    
    public function listarRecursosHijos($id_padre)
    {
        $resultSet = $this->tableGateway->select( 'id_padre = ' . (int )$id_padre .' AND eliminado = 0' );
        return $resultSet;
    }

    public function get($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_recurso' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function save(Recurso $rol)
    {
        $data = array(
            'id_padre'      => $rol->id_padre,
            'nombre'        => $rol->nombre,
            'descripcion'   => $rol->descripcion,
        );

        $id = (int)$rol->id_recurso;
        
        if ($id == 0) {
            $data['eliminado'] = 0;
            $this->tableGateway->insert($data);
        } else {
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('id_recurso' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id_recurso' => $id));
    }
}
