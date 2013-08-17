<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Reportes\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Reportes\Model\ReporteUsuarios;
use Reportes\Form\ReportesForm; 

use Reportes\Model\Album;


use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Zend\Db\Sql\Select;

class UsuariosController extends AbstractActionController
{
    protected $ReporteTable;
    
    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $user = $authService->getIdentity();
        $form = new ReportesForm();        
       
//        foreach($usuarios as $row):
//            echo $row['perfil'];
//        endforeach;
//        var_dump($usuarios);exit;
        
        $resultRoles = $this->getRolTable()->fetchAll();
        $roles = array();
        foreach( $resultRoles as $row ):
            $roles[$row->id_rol] = $row->descripcion; 
        endforeach;
        
        $form->get('id_perfil')->setValueOptions($roles);
        
        $resultUsers = $this->getUserTable()->fetchAll();
        $usuarios = array();
        foreach( $resultUsers as $row ):
            $usuarios[$row->id_usuario] = $row->username; 
        endforeach;
        
        $form->get('id_usuario')->setValueOptions($usuarios);
        
        return new ViewModel(array(
            'form' => $form,
            'user' => $user,
        ));
    }
    public function imprimirAction()
    {
        //Busqueda de usuarios del reporte
        $viewmodel = new ViewModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        //disable layout if request by Ajax
        //$viewmodel->setTerminal($request->isXmlHttpRequest());
        $viewmodel->setTerminal(true);
        $datos = array();
        if ( $request->isPost() ) {
            $data = $request->getPost();
            $id_usuario = $data['id_usuario'];
            $id_perfil = $data['id_perfil'];
            $fecha_inicio = $data['fecha_inicio'];
            $fecha_fin = $data['fecha_fin'];
            
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $datos = array();
            $datos=new ReporteUsuarios($this->dbAdapter);
            
            
            $usuarios=$datos->getReporteLlamadasPorOperador();
            
//            var_dump($datos);
//            exit;
        }
        
        $viewmodel->setVariables(array(
            'id_usuario' => $id_usuario,
            // is_xmlhttprequest is needed for check this form is in modal dialog or not
            // in view
            'datos' => $usuarios
        ));
        return $viewmodel;
         
    }
    
    public function displayAction()
    {
        //Busqueda de usuarios del reporte
        $viewmodel = new ViewModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        //disable layout if request by Ajax
        $viewmodel->setTerminal($request->isXmlHttpRequest());
        //$viewmodel->setTerminal(true);
        $datos = array();
        if ( $request->isPost() ) {
            $data = $request->getPost();
            $id_usuario = $data['id_usuario'];
            $id_perfil = $data['id_perfil'];
            $fecha_inicio = $data['fecha_inicio'];
            $fecha_fin = $data['fecha_fin'];
            
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
            $itemsPerPage = 2;
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $datos = array();
            $objRep = new ReporteUsuarios($this->dbAdapter);
            $usuarios = $objRep->getReporteLlamadasPorOperador();
            
            foreach($usuarios as $row):
                $datos[] = array(
                    'id_usuario'=> $row['id_usuario'],
                    'username'  => $row['username'],
                    'nombre'    => $row['nombre'],
                    'a_paterno' => $row['apaterno'],
                    'a_materno' => $row['amaterno'],
                    'perfil'    => $row['perfil'],
                );
            endforeach;
            
            $paginator = new Paginator( new \Zend\Paginator\Adapter\ArrayAdapter($datos));

            $paginator->setCurrentPageNumber($page)
                    ->setItemCountPerPage($itemsPerPage);
        
        }else{
            
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
            $itemsPerPage = 2;
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $datos = array();
            $objRep = new ReporteUsuarios($this->dbAdapter);
            $usuarios = $objRep->getReporteLlamadasPorOperador();
            
            foreach($usuarios as $row):
                $datos[] = array(
                    'id_usuario'=> $row['id_usuario'],
                    'username'  => $row['username'],
                    'nombre'    => $row['nombre'],
                    'a_paterno' => $row['apaterno'],
                    'a_materno' => $row['amaterno'],
                    'perfil'    => $row['perfil'],
                );
            endforeach;
            
            $paginator = new Paginator( new \Zend\Paginator\Adapter\ArrayAdapter($datos));

            $paginator->setCurrentPageNumber($page)
                    ->setItemCountPerPage($itemsPerPage);
        }
        
        $viewmodel->setVariables(array(
            'id_usuario'    => $id_usuario,
            'page'          => $page,
            'paginator'     => $paginator,
        ));
        return $viewmodel;
    }
        
    public function getRolTable()
    {
        if (!$this->rolTable) {
            $sm = $this->getServiceLocator();
            $this->rolTable = $sm->get('Administrator\Model\RolTable');
        }
            
        return $this->rolTable;
    }
    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Administrator\Model\UserTable');
        }
            
        return $this->userTable;
    }
    
    public function albumAction()
    {
        $config = array ('db' => array(
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=test;host=localhost',
            'username' => 'root',
            'password' => ''));
        
        //var_dump($config['db']);exit;
        $adapter = new Zend\Db\Adapter\Adapter($config['db']);
        
//        $adapter = new Zend\Db\Adapter\Adapter(array(
//                    'driver' => 'Pdo_Mysql',
//                    'database' => 'test',
//                    'username' => 'root',
//                    'password' => 'developer-password'
//                ));
        
        
        var_dump($adapter);exit;
        
        $albums = array();
        $objetoAbum = new Album($adapter);
        $albums = $objetoAbum->getAll();
        var_dump($albums);exit;
        echo 'Este es un album';exit;
    }
}
