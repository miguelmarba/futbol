<?php
namespace Administrator\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Sql,
        Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class UserTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select('eliminado <> 1');
        return $resultSet;
        
//        $sql = new Sql($this->tableGateway->getAdapter());
//
//    $select = $sql->select();
//    $select->from('sys_usuarios');
//    
//    $resultSet = $this->tableGateway->selectWith($select);
//    return $resultSet;
        
        
        $sql = new Sql($this->tableGateway->getAdapter());

        $select = $sql->select();
        $select->from(array('a' => 'sys_usuarios'))
                ->columns(array('id_usuario', 'username', 'fecha_cambio_password'))
                ->join(array('b' => 'cat_personal'), 'a.id_personal = b.id_personal', array('nombre_user' => 'nombre'), \Zend\Db\Sql\Select::JOIN_INNER);

//        echo $select->getSqlString();
//        exit;
//        $resultSet = $this->tableGateway->selectWith($select);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
    
        return $result;
    }

    public function get($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_usuario' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getByUser($username)
    {
        $username  = (string) $username;
        $rowset = $this->tableGateway->select(array('username' => $username));
        $row = $rowset->current();
        if (!$row) {
            //throw new \Exception("Could not find row $id");
            $row = array();
        }
        return $row;
    }
    
    public function bloquearUser(User $user)
    {
        $data = array(
            'bloqueado' => 1,
            'numero_intentos_acceso' => 0,
        );
        
        $id = (int)$user->id_usuario;
        if ($this->get($id)) {
            $this->tableGateway->update($data, array('id_usuario' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
    
    public function agregarIntentoAcceso(User $user)
    {
        $data = array(
            'ultimo_acceso' => date('d-m-Y H:i:s'),
            'numero_intentos_acceso' => $user->numero_intentos_acceso + 1,
        );
        
        $id = (int)$user->id_usuario;
        if ($this->get($id)) {
            $this->tableGateway->update($data, array('id_usuario' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
    
    public function initAcces(User $user)
    {
        $data = array(
            'ultimo_acceso' => date('d-m-Y H:i:s'),
            'numero_intentos_acceso' => 0,
        );
        
        $id = (int)$user->id_usuario;
        if ($this->get($id)) {
            $this->tableGateway->update($data, array('id_usuario' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }

    public function save(User $user)
    {
        $data = array(
            'username'  => $user->username,
            'password'  => $user->password,
            'hash'      => $user->hash,
            'password_generado_por_sistema'  => $user->password_generado_por_sistema,
            'fecha_cambio_password'  => $user->fecha_cambio_password,
            'id_personal'  => $user->id_personal,
            'bloqueado'  => $user->bloqueado,
            'eliminado'  => $user->eliminado,
        );
        
        $id = (int)$user->id_usuario;
        if ($id == 0) {
//            var_dump($data);exit;
            $this->tableGateway->insert($data);
        } else {
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('id_usuario' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function delete($id)
    {
        //$this->tableGateway->delete(array('id_usuario' => $id));
        
        // Se realiza un borrado lógico
        $data = array(
            'eliminado' => 1,
        );

        $id = (int) $id;
        if ($id) {
            $this->tableGateway->update($data, array('id_usuario' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
}
