<?php
 /**
 * Itsa Interactive (http://www.itsainteractive.com/)
 *
 * @autor Miguel A. Martinez
 * @version 1.1
 * @copyright Copyright (c) 2013-2013 Itsa Interactive SA de CV
 * @license   http://www.itsainteractive.com/license/contactcenter
 */

namespace Application;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Authentication\AuthenticationListener;

use Application\Model\User;
use Application\Model\UserTable;


class Module implements AutoloaderProviderInterface
{
    /**
     *
     * @var object SessionContainer $sessioncontainer
     */
    protected $_sessioncontainer;
    
    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                        $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    }
                }
            ),
            'invokables' => array(
                 'menu' => 'Application\Model\MenuTable'
            ),
            'factories' => array(
                'Navigation'    => 'Zend\Navigation\Service\DefaultNavigationFactory',
                'Application\Model\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('sys_usuarios', $dbAdapter, null, $resultSetPrototype);
                },       
            ),
        );
    }
    
    public function onBootstrap(MvcEvent $e)
    {
//        $e->getApplication()->getServiceManager()->get('translator');
//        $eventManager        = $e->getApplication()->getEventManager();
//        $moduleRouteListener = new ModuleRouteListener();       
//        $moduleRouteListener->attach($eventManager);
//        
//        //$eventManager  = $e->getApplication()->getEventManager();
//        $authListener       = new AuthenticationListener();
//        $authListener->attach($eventManager);
//        
//        //Permissions
//        $this->initAcl($e);
//        $e->getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));
        
        $em = $e->getApplication()->getEventManager();
        $em->attach('route', array($this, 'checkSession'));
    }
    
    public function checkSession(MvcEvent $e) {
        $sm = $e->getApplication()->getServiceManager();
        if (!$sm->get('AuthService')->getStorage()->getSessionManager()
                        ->getSaveHandler()
                        ->read($sm->get('AuthService')->getStorage()->getSessionId())) {
            
            $controller = $e->getRouteMatch()->getParam('controller');
            
            if ($controller != 'SanAuthWithDbSaveHandler\Controller\Auth') {
                return $e->getTarget()->getEventManager()->getSharedManager()
                        ->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', 
                                function($e) {
                                    $controller = $e->getTarget();
                                    $controller->redirect()->toRoute('auth');
                                }, -11);
            }
        }
        

        //Permissions
        $this->initAcl($e);
        $this->checkAcl($e);
        //echo 'Hola mundo de nuevo';exit;
            
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function initAcl(MvcEvent $e) 
    {
        $acl = new \Zend\Permissions\Acl\Acl();
        $roles = include __DIR__ . '/config/module.acl.roles.php';
        
        $allResources = array();
        foreach ($roles as $role => $resources) {
            $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
            $acl->addRole($role);

            $allResources = array_merge($resources, $allResources);
            //adding resources
            foreach ($resources as $resource) {
                $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
            }
            //adding restrictions
            foreach ($allResources as $resource) {
                $acl->allow($role, $resource);
            }
        }
        
        $acl->deny('guest', 'administrator');
        //testing
        //var_dump($acl->isAllowed('admin','home'));exit;
        //true
        //setting to view
        $e->getViewModel()->acl = $acl;
    }
    
    public function checkAcl(MvcEvent $e) 
    {
        $route = $e->getRouteMatch()->getMatchedRouteName();
        $partes = explode('/', $route);
        $module = $partes[0]; // Tambien es el Modulo.
        
        //you set your role
        $userRole = 'admin';
        //$route = 'administrator';
        //
        //
        //echo $userRole;exit;
        $authService = $e->getApplication()->getServiceManager()->get('AuthService');
        $login = $authService->getIdentity();
        //$userRole = $login['rol'];
//        echo $userRole;exit;
//        var_dump($login);exit;
        
        //Obtenemos el controller
        $controller = $e->getRouteMatch()->getParam('controller');
        $action = $e->getRouteMatch()->getParam('action');
        $resource = "{$module}-{$controller}";
        //echo 'Recurso: ' . $resource;exit;
        
        $existeRecurso = $e->getViewModel()->acl->hasResource($module)? true : false;
        //var_dump($existeRecurso);exit;
        if(!$existeRecurso){
            $acceso = false;
        } else {
            $acceso = $e->getViewModel()->acl->isAllowed($userRole, $module) ? true : false;
        }
        
        //echo var_dump($acceso);exit;
//        if (!$e->getViewModel()->acl->isAllowed($userRole, $route)) {
//        if (!$e -> getViewModel() -> acl ->hasResource($route) && !$e -> getViewModel() -> acl -> isAllowed($userRole, $route)) {
        if (!$existeRecurso || !$acceso) {    
            $response = $e->getResponse();
            //location to page or what ever
            $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
            $response->setStatusCode(303);
        }
        
        //echo 'Permiso-' . $route;exit;
    }
    
}
