<?php
namespace Administrator\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Sql,
    Zend\Db\Sql\Where;

class PermisoTable
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

    public function get($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_permiso' => $id));
        
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function existe($id, $id_padre, $id_hijo)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select('id_rol = ' . $id . ' AND id_recurso_padre = ' . $id_padre . ' AND id_recurso_hijo = ' . $id_hijo );
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return true;
    }

    public function save($datos)
    {
        $data = array(
            'id_rol'            => $datos['id_rol'],
            'id_recurso_padre'  => $datos['id_recurso_padre'],
            'id_recurso_hijo'   => $datos['id_recurso_hijo'],
        );
        
        $data['activo'] = $datos['activo'];
        $this->tableGateway->insert($data);
    }
    
    public function update($datos)
    {
        $data['activo'] = $datos['activo'];
        $this->tableGateway->update($data, array(
            'id_rol' => $datos['id_rol'], 
            'id_recurso_padre' => $datos['id_recurso_padre'], 
            'id_recurso_hijo' => $datos['id_recurso_hijo']));
    }
    
    public function listarPermisos($id_rol, $id_rec_padre)
    {
        $condicion = 'id_rol = ' . (int) $id_rol . ' AND id_recurso_padre = ' . (int) $id_rec_padre;
        
        $sql = new Sql($this->tableGateway->getAdapter());

        $select = $sql->select();
        $select->from(array('a' => 'sys_permisos'))
                ->columns(array('id_permiso', 'id_recurso_hijo', 'activo' ))
                ->where( $condicion );
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
 
        return $result;
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id_rol' => $id));
    }
}
