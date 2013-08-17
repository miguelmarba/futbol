<?php
 /**
 * Itsa Interactive (http://www.itsainteractive.com/)
 *
 * @autor Miguel A. Martinez
 * @version 1.1
 * @copyright Copyright (c) 2013-2013 Itsa Interactive SA de CV
 * @license   http://www.itsainteractive.com/license/contactcenter
 */

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterAwareInterface;

class MenuTable extends AbstractTableGateway
    implements AdapterAwareInterface
{
    protected $table = 'sys_menu';

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();
 
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select(function (Select $select){
                $select->order(array('id_menu asc'));
        });
 
        $resultSet = $resultSet->toArray();
 
        return $resultSet;
    }
}
