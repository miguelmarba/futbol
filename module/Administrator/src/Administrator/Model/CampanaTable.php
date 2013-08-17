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

class CampanaTable extends AbstractTableGateway
{
    protected $tableGateway;
        
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
//        $resultSet = $this->tableGateway->select();
//        return $resultSet;
        
        $select = new Select('gen_campana');
        // create a new result set based on the Album entity
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Campana());
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


        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getCampana($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar el registro $id");
        }
        return $row;
    }

    public function saveCampana(Campana $campana)
    {  
        $data = array(
            'nom_campana' => $campana->nom_campana,
            'desc_campana' => $campana->desc_campana,
            'tipo_campana' => $campana->tipo_campana,
            'status_prendida' => $campana->status_prendida,
            'fecha_inicio' => $campana->fecha_inicio,
            'fecha_fin' => $campana->fecha_fin,
            'status'  => 1,
        );
        
        $id = (int)$campana->id;
        //$prueba = $campana->domingohora3ini;
        //var_dump($data);exit;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCampana($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCampana($id)
    {
         $data = array(
            'status'  => 0,
        );
        $this->tableGateway->update($data, array('id' => $id));     
    }
    
    
}
