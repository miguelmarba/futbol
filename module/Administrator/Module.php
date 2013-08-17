<?php
 /**
 * Itsa Interactive (http://www.itsainteractive.com/)
 *
 * @autor Miguel A. Martinez
 * @version 1.1
 * @copyright Copyright (c) 2013-2013 Itsa Interactive SA de CV
 * @license   http://www.itsainteractive.com/license/contactcenter
 */

namespace Administrator;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Administrator\Model\Album;
use Administrator\Model\AlbumTable;
use Administrator\Model\User;
use Administrator\Model\UserTable;
use Administrator\Model\Personal;
use Administrator\Model\PersonalTable;
use Administrator\Model\Rol;
use Administrator\Model\RolTable;
use Administrator\Model\Recurso;
use Administrator\Model\RecursoTable;
use Administrator\Model\Campana;
use Administrator\Model\CampanaTable;
use Administrator\Model\Permiso;
use Administrator\Model\PermisoTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

date_default_timezone_set('America/Mexico_City');

class Module implements AutoloaderProviderInterface
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Administrator\Model\AlbumTable' =>  function($sm) {
                    $tableGateway = $sm->get('AlbumTableGateway');
                    $table = new AlbumTable($tableGateway);
                    return $table;
                },
                'Administrator\Model\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'Administrator\Model\PersonalTable' =>  function($sm) {
                    $tableGateway = $sm->get('PersonalTableGateway');
                    $table = new PersonalTable($tableGateway);
                    return $table;
                },
                'Administrator\Model\RolTable' =>  function($sm) {
                    $tableGateway = $sm->get('RolTableGateway');
                    $table = new RolTable($tableGateway);
                    return $table;
                },
                'Administrator\Model\RecursoTable' =>  function($sm) {
                    $tableGateway = $sm->get('RecursoTableGateway');
                    $table = new RecursoTable($tableGateway);
                    return $table;
                },        
                'Administrator\Model\CampanaTable' =>  function($sm) {
                    $tableGateway = $sm->get('CampanaTableGateway');
                    $table = new CampanaTable($tableGateway);
                    return $table;
                },
                'Administrator\Model\PermisoTable' =>  function($sm) {
                    $tableGateway = $sm->get('PermisoTableGateway');
                    $table = new PermisoTable($tableGateway);
                    return $table;
                },
                'AlbumTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
                'PersonalTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Personal());
                    return new TableGateway('cat_personal', $dbAdapter, null, $resultSetPrototype);
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('sys_usuarios', $dbAdapter, null, $resultSetPrototype);
                },
                'RolTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Rol());
                    return new TableGateway('sys_roles', $dbAdapter, null, $resultSetPrototype);
                },
                'RecursoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Recurso());
                    return new TableGateway('sys_recursos', $dbAdapter, null, $resultSetPrototype);
                },        
                 'CampanaTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Campana());
                    return new TableGateway('gen_campana', $dbAdapter, null, $resultSetPrototype);
                },
                'PermisoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Permiso());
                    return new TableGateway('sys_permisos', $dbAdapter, null, $resultSetPrototype);
                },        
            ),
        );
    }

    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
               
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
    }
}
