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

class PersonalTable extends AbstractTableGateway
{
    protected $tableGateway;
//    protected $adapter;


    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        
//        $this->adapater = $this->tableGateway->getAdapter();
//        $this->initialize();
//        echo 'Mike';exit;
//        var_dump($this->adapater);exit;
    }

    public function fetchAll()
    {
//        $resultSet = $this->tableGateway->select('eliminado <> 1');
//        return $resultSet;
        
        
        
        
        $select = new Select('cat_personal');
        // create a new result set based on the Album entity
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Personal());
        // create a new pagination adapter object
        $paginatorAdapter = new DbSelect(
                        // our configured select object
                        $select,
                        // the adapter to run it against
                        $this->tableGateway->getAdapter(),
                        // the result set to hydrate
                        $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;


//        $resultSet = $this->tableGateway->select();
//        return $resultSet;
    }
    
    public function fetchPersonalSinUsuario($id = 1)
    {
//        $adapater = $this->tableGateway->getAdapter();
//        var_dump($adapater);exit;
//        $sql = new Sql($adapter);
//        $select = $sql->select();
//        $select->from('cat_personal')
//              ->join('sys_usuarios', 'cat_personal.id_personal = sys_usuarios.id_personal');
// 
//        $where = new  Where();
//        $where->equalTo('id_personal', $id) ;
//        $select->where($where);
// 
//        //you can check your query by echo-ing :
//        // echo $select->getSqlString();
//        $statement = $sql->prepareStatementForSqlObject($select);
//        $result = $statement->execute();
// 
//        return $result;
        
        
        $result = $this->select(function (Select $select) use ($id) {
                    $select->where(array('id_personal' => $id));
                    $select->join('sys_usuarios', 'cat_personal.album_id = sys_usuarios.id');
                });

        return $result;
    }

    public function get($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_personal' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function save(Personal $personal)
    {
//        echo utf8_decode($personal->a_paterno);exit;
        $data = array(
            'nombre'            => $personal->nombre,
            'a_paterno'         => $personal->a_paterno,
            'a_materno'         => $personal->a_materno,
            'fecha_nacimiento'  => $personal->fecha_nacimiento,
            'fecha_alta'        => $personal->fecha_alta,
            'id_rol'            => $personal->id_rol,
            'departamento'      => $personal->departamento,
            'cargo'             => $personal->cargo,
            'telefono_movil'    => $personal->telefono_movil,
            'telefono_casa'     => $personal->telefono_casa,
            'telefono_trabajo'  => $personal->telefono_trabajo,
            'fax'               => $personal->fax,
            'cp'                => $personal->cp,
            'calle'             => $personal->calle,
            'no_interior'       => $personal->no_interior,
            'no_exterior'       => $personal->no_exterior,
            'id_pais'           => $personal->id_pais,
            'id_estado'         => $personal->id_estado,
            'id_municipio'      => $personal->id_municipio,
            'id_colonia'        => $personal->id_colonia,
        );
//var_dump($data);exit;
        $id = (int)$personal->id_personal;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('id_personal' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function delete($id)
    {
        //$this->tableGateway->delete(array('int_IdPersonal' => $id));
        
        //Se realiza un borrado lógico
        $data = array(
            'eliminado' => 1,
        );

        $id = (int) $id;
        if ($id) {
            $this->tableGateway->update($data, array('id_personal' => $id));
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
}
