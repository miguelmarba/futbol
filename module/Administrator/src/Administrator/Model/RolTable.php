<?php
namespace Administrator\Model;

use Zend\Db\TableGateway\TableGateway;

class RolTable
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
        $rowset = $this->tableGateway->select(array('id_rol' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function save(Rol $rol)
    {
        $data = array(
            'nombre'        => $rol->nombre,
            'descripcion'   => $rol->descripcion,
        );

        $id = (int)$rol->id_rol;
        
        if ($id == 0) {
            $data['eliminado'] = 0;
            $this->tableGateway->insert($data);
        } else {
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('id_rol' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id_rol' => $id));
    }
}
