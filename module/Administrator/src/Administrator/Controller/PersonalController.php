<?php
 /**
 * Itsa Interactive (http://www.itsainteractive.com/)
 *
 * @autor Miguel A. Martinez
 * @version 1.1
 * @copyright Copyright (c) 2013-2013 Itsa Interactive SA de CV
 * @license   http://www.itsainteractive.com/license/contactcenter
 */

namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administrator\Model\Personal;
use Administrator\Form\PersonalForm; 
use Administrator\Model\Cp;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;


use Zend\Db\Sql\Select;

class PersonalController extends AbstractActionController
{
    protected $personalTable;
    
    public function indexAction()
    {
        //Obtenemos la info del usuario logueado
        $user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()
                ->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
        $this->layout()->setVariable('nickname', $user['nombre_completo']);
        
        
        //var_dump($authService);exit;
        //$result = $this->getPersonalTable()->fetchAll();

        
//        $select = new Select();
//        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
//
//        $itemsPerPage = 2;
//
//        //$result->current();
//        $paginator = new Paginator(new paginatorIterator($result));
//        $paginator->setCurrentPageNumber($page)
//                ->setItemCountPerPage($itemsPerPage);
//                //->setPageRange(7);
        
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $itemsPerPage = 2;
        // grab the paginator from the PersonalTable
        $paginator = $this->getPersonalTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $page);
        // set the number of items per page to 10
        $paginator->setItemCountPerPage($itemsPerPage);

        //var_dump($paginator);exit;
        return new ViewModel(array(
            'paginator' => $paginator,
        ));

    }
    
    public function addAction()
    {
//        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//        $datos = array();
//        $localidad = new Cp($this->dbAdapter);
//        //echo $usuario[0]['fecha_cambio_password'];exit;
//        //$datos = $localidad->fetchAll();
//        $datos = $localidad->findByCp('43050');
//        
//        foreach($datos as $dato):
//            echo $dato['estado'];exit;
//        endforeach;
//        
//        var_dump($datos);exit; 
        
        //Obtenemos la info del usuario logueado
        $user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()
                ->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
        $this->layout()->setVariable('nickname', $user['nombre_completo']);
        
        
        $form = new PersonalForm();
        $form->get('submit')->setValue('Agregar');
        
        $resultRoles = $this->getRolTable()->fetchAll();
        $roles = array();
        foreach( $resultRoles as $row ):
            $roles[$row->id_rol] = $row->descripcion; 
        endforeach;
        
        //Obtenemos los estados
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $estados = array();
        $Cp = new Cp($this->dbAdapter);
        $resultEstados = $Cp->getMunicipios();
        foreach( $resultEstados as $row ):
            $estados[$row['id_estado']] = $row['descripcion']; 
        endforeach;
        
        $form->get('id_rol')->setValueOptions($roles);
        $form->get('id_estado')->setValueOptions($estados);
        
        $request = $this->getRequest();
        
        if ( $request->isPost() ) {
            
            $datos = $request->getPost();
            $id_estado = $datos['id_estado'];
            $id_municipio = $datos['id_municipio'];
            
            if( $id_estado != '' ){
                //Cargamos los municipios
                $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $Cp = new Cp($this->dbAdapter);
                $datos = $Cp->findMunicipiosByEstado($id_estado);

                $municipios = array();
                foreach ($datos as $row):
                    $municipios[$row['id_municipio']] = $row['descripcion'];
                endforeach;
                $form->get('id_municipio')->setValueOptions($municipios);
            }
            
            if( $id_municipio != '' ){
                //Cargamos las localidades
                $datosCol = $Cp->findColonias($id_estado, $id_municipio);

                $localidades = array();
                foreach ($datosCol as $row):
                    $localidades[$row['id_localidad']] = $row['descripcion'];
                endforeach;
                $form->get('id_colonia')->setValueOptions($localidades);
            }
            
            $personal = new Personal();
            $form->setInputFilter($personal->getInputFilter());
            
            $datos = $request->getPost();
            //var_dump( $datos );exit;
            $form->setData($request->getPost());
            //var_dump( $form );exit;
            if ($form->isValid()) {
                $datos = $form->getData();
                //var_dump($datos);exit;
                
                $personal->exchangeArray($form->getData());
                $personal->fecha_alta = date('d-m-Y H:i:s');
                $this->getPersonalTable()->save($personal);

                // Redirect to list of personal
                return $this->redirect()->toRoute('administrator/default', array( 
                    'controller' => 'personal'
                    ));
            }
        }
        
        return array('form' => $form, 'roles' => $roles);

    }
    
    public function editAction()
    {
        //Obtenemos la info del usuario logueado
        $user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()
                ->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
        $this->layout()->setVariable('nickname', $user['nombre_completo']);
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'personal'
            ));
        }
        $person = $this->getPersonalTable()->get($id);
        $form  = new PersonalForm();
        $form->bind($person);
        $form->get('submit')->setAttribute('value', 'Guardar cambios');
        $form->get('fecha_nacimiento')->setValue($person->getFechaNacimiento('d-m-Y'));
        
        //LLenamos los roles
        $resultRoles = $this->getRolTable()->fetchAll();
        $roles = array();
        foreach( $resultRoles as $row ):
            $roles[$row->id_rol] = $row->descripcion; 
        endforeach;
        
        //Obtenemos los estados
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $estados = array();
        $Cp = new Cp($this->dbAdapter);
        $resultEstados = $Cp->getMunicipios();
        foreach( $resultEstados as $row ):
            $estados[$row['id_estado']] = $row['descripcion']; 
        endforeach;
        
        $form->get('id_rol')->setValueOptions($roles);
        $form->get('id_estado')->setValueOptions($estados);
        
        if ( $person->id_estado != 0 ){
            $datos = $Cp->findMunicipiosByEstado($person->id_estado);
            
            $municipios = array();
            foreach($datos as $row):
                $municipios[$row['id_municipio']] = $row['descripcion'];
            endforeach;
            $form->get('id_municipio')->setValueOptions($municipios);
        }
        
        if ( $person->id_municipio != 0 ){
            $datos = $Cp->findColonias($person->id_estado, $person->id_municipio);
            
            $localidades = array();
            foreach($datos as $row):
                $localidades[$row['id_localidad']] = $row['descripcion'];
            endforeach;
            
            $form->get('id_colonia')->setValueOptions($localidades);
        }
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $datos = $request->getPost();
            $id_estado = $datos['id_estado'];
            $id_municipio = $datos['id_municipio'];
            $id_colonia = $datos['id_colonia'];
            
            //if( $id_municipio != '' && $person->id_municipio != 0 ){
                //Cargamos los municipios
                $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
                $Cp = new Cp($this->dbAdapter);
                $datos = $Cp->findMunicipiosByEstado($id_estado);

                $municipios = array();
                foreach ($datos as $row):
                    $municipios[$row['id_municipio']] = $row['descripcion'];
                endforeach;
                $form->get('id_municipio')->setValueOptions($municipios);
            //}
            
            //if( $id_colonia != '' && $person->id_colonia != 0 ){
                //Cargamos las localidades
                $datosCol = $Cp->findColonias($id_estado, $id_municipio);

                $localidades = array();
                foreach ($datosCol as $row):
                    $localidades[$row['id_localidad']] = $row['descripcion'];
                endforeach;
                $form->get('id_colonia')->setValueOptions($localidades);
            //}

            $form->setInputFilter($person->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $this->getPersonalTable()->save($form->getData());

                // Redirect to list of personal
                return $this->redirect()->toRoute('administrator/default', array( 
                    'controller' => 'personal'
                    ));
            }
            
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function deleteAction()
    {
        //Obtenemos la info del usuario logueado
        $user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()
                ->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
        $this->layout()->setVariable('nickname', $user['nombre_completo']);
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'personal'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getPersonalTable()->delete($id);
            }

            // Redirect to list of personal
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'personal'
            ));
        }

        return array(
            'id'    => $id,
            'personal' => $this->getPersonalTable()->get($id)
        );
    }
    
    public function cpAction()
    {
        //Busqueda de localidades
        $viewmodel = new ViewModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        //disable layout if request by Ajax
        //$viewmodel->setTerminal($request->isXmlHttpRequest());
        $viewmodel->setTerminal(true);
        $datos = array();
        if ( $request->isPost() ) {
            $data = $request->getPost();
            $cp = $data['cp'];
            
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $datos = array();
            $localidad = new Cp($this->dbAdapter);
            //echo $usuario[0]['fecha_cambio_password'];exit;
            //$datos = $localidad->fetchAll();
            $datos = $localidad->findByCp($cp);
            
//            var_dump($datos);
//            exit;
        }
        
        $viewmodel->setVariables(array(
            'cp' => $cp,
            // is_xmlhttprequest is needed for check this form is in modal dialog or not
            // in view
            'datos' => $datos
        ));
        return $viewmodel;
    }

    public function municipiosAction()
    {
        //Busqueda de localidades
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $id_estado = 1;
        
        $datos = array();
        if ( $request->isPost() ) {
            $data = $request->getPost();
            $id_estado = $data['id_estado'];
            
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $Cp = new Cp($this->dbAdapter);
            $datos = $Cp->findMunicipiosByEstado($id_estado);
            
            $json = array();
            foreach($datos as $row):
                $items[] = array('id_municipio'=> $row['id_municipio'], 'descripcion'=> $row['descripcion']);
            endforeach;
            $json['respuesta'] = 'succces';
            $json['items'] = $items;
            $respuesta = array(
                'respuesta' => 'succes',
                'items'     => $items,
            );
            $response->setContent(\Zend\Json\Json::encode( $respuesta ));
        }
        
        return $response;
    }
    
    public function localidadesAction()
    {
        //Busqueda de localidades
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $id_estado = 1;
        $id_municipio = 1;
        
        $datos = array();
        if ( $request->isPost() ) {
            $data = $request->getPost();
            $id_estado = $data['id_estado'];
            $id_municipio = $data['id_municipio'];
            
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $Cp = new Cp($this->dbAdapter);
            $datos = $Cp->findColonias($id_estado, $id_municipio);
            
            $json = array();
            foreach($datos as $row):
                $items[] = array('id_localidad'=> $row['id_localidad'], 'descripcion'=> $row['descripcion']);
            endforeach;
            $json['respuesta'] = 'succces';
            $json['items'] = $items;
            $respuesta = array(
                'respuesta' => 'succes',
                'items'     => $items,
            );
            $response->setContent(\Zend\Json\Json::encode( $respuesta ));
        }
        
        return $response;
    }
    
    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }
    
    public function getPersonalTable()
    {
        if (!$this->personalTable) {
            $sm = $this->getServiceLocator();
            $this->personalTable = $sm->get('Administrator\Model\PersonalTable');
        }
            
        return $this->personalTable;
    }
    
    public function getRolTable()
    {
        if (!$this->rolTable) {
            $sm = $this->getServiceLocator();
            $this->rolTable = $sm->get('Administrator\Model\RolTable');
        }
            
        return $this->rolTable;
    }
    
}
