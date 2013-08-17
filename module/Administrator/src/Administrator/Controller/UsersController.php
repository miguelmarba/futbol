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
use Administrator\Model\User;
use Administrator\Form\UserForm; 

use Administrator\Model\Usuario;

class UsersController extends AbstractActionController
{
    protected $userTable;
    protected $personalTable;
    
    protected $dbAdapter;
    
    public function indexAction()
    {
//        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//        $datos = array();
//        $usuario = new Usuario($this->dbAdapter);
        //echo $usuario[0]['fecha_cambio_password'];exit;
//        $datos = $usuario->fetchAll();
//        $datos = $usuario->getUserPersonal();
        
//        foreach($datos as $dato):
//            echo $dato->username;exit;
//        endforeach;
        
//        var_dump($datos);exit;       
        
        
        
        
        
        $authService = $this->getServiceLocator()->get('AuthService');
        $user = $authService->getIdentity();
        
        
        $result = $this->getUserTable()->fetchAll();
//        var_dump($result);exit;
        
//        foreach($result as $dato):
//            //var_dump($dato);exit;
//            echo $dato['username'] . $dato['nombre_user'];
//        endforeach;
//        exit;
        return new ViewModel(array(
            'users' => $result,
            'user' => $user,
        ));
    }
    
    public function getTiempoTrascurrido($date1, $date2)
    {
        $segundos = strtotime($date2) - strtotime($date1); //Segundos transcurridos
        $minutos = ($segundos / 60); // Minutos transcurridos
        return $minutos;
    }
    
    function diff_sinp($fecha1, $fecha2, $tiempo1, $tiempo2) {
        $dif = date("H:i", strtotime("00:00") + strtotime($tiempo2) - strtotime($tiempo1));
        if ($dif == '00:00') {
            $dif = null;
        }
        $difd = date_diff(date_create($fecha1), date_create($fecha2));
        $difd = $difd->format('%a dias');
        return $difd . ' ' . $dif;
    }
    
    public function addAction()
    {
        $form = new UserForm();
        $form->get('submit')->setValue('Guardar');
        
        
//        $resultPersonal = $this->getPersonalTable()->fetchPersonalSinUsuario();
//        var_dump($resultPersonal);exit;
        
        $resultPersonal = $this->getPersonalTable()->fetchAll();
        $roles = array();
        foreach( $resultPersonal as $row ):
            $personal[$row->id_personal] = $row->nombre . ' ' . $row->a_paterno; 
        endforeach;
        $form->get('id_personal')->setValueOptions($personal);
                
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $user = new User();
            
            $form->setInputFilter($user->getInputFilter());
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $user->exchangeArray($form->getData());
                
                $user->password = crypt( $user->password );
                $user->password_generado_por_sistema = 1;
                $user->fecha_cambio_password = date('d-m-Y H:i:s');
                $user->bloqueado = 0;
                $user->eliminado = 0;
                
                $this->getUserTable()->save($user);

                // Redirect to list of usuarios
                return $this->redirect()->toRoute('administrator/default', array(
                    'controller' => 'users'
                ));
            }
        }
        
        return array('form' => $form);

    }
    
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'users'
            ));
        }
        $user = $this->getUserTable()->get($id);
        
        $passwordAnterior = $user->password;
        
        $form  = new UserForm();
        $form->bind($user);
        
        $form->get('submit')->setAttribute('value', 'Guardar');
        $form->get('username')->setAttribute('disabled', 'disabled');

        $resultPersonal = $this->getPersonalTable()->fetchAll();
        $roles = array();
        foreach( $resultPersonal as $row ):
            $personal[$row->id_personal] = $row->nombre . ' ' . $row->a_paterno; 
        endforeach;
        $form->get('id_personal')->setValueOptions($personal);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter( $user->getInputFilter() );
            $form->setData( $request->getPost() );    
                                
            if ($form->isValid()) {
                $datos = array();
                
                $pass = $form->get('password')->getValue();
                if($pass){
                    $datos['password'] = crypt( $form->get('password')->getValue() );
                    $datos['hash'] = crypt( $form->get('hash')->getValue() );
                    $datos['password_generado_por_sistema'] = 1;
                } else {
                    $datos['password'] = $passwordAnterior;
                    $datos['hash'] = $passwordAnterior;
                     
                }
                $datos['id_usuario'] = $form->get('id_usuario')->getValue();
                $datos['username'] = $form->get('username')->getValue();
                $datos['id_personal'] = $form->get('id_personal')->getValue();
                        
                $datos['fecha_cambio_password'] = date('d-m-Y H:i:s');
                $datos['bloqueado'] = 0;
                $datos['eliminado'] = 0;
                
                $user->exchangeArray($datos);
                
                $this->getUserTable()->save($user);

                // Redirect to list of users
                return $this->redirect()->toRoute('administrator/default', array(
                    'controller' => 'users'
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
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'users'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getUserTable()->delete($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'users'
            ));
        }

        return array(
            'id'    => $id,
            'user' => $this->getUserTable()->get($id)
        );
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }
    
    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Administrator\Model\UserTable');
        }
            
        return $this->userTable;
    }
    
    public function getPersonalTable()
    {
        if (!$this->personalTable) {
            $sm = $this->getServiceLocator();
            $this->personalTable = $sm->get('Administrator\Model\PersonalTable');
        }
            
        return $this->personalTable;
    }
    
}
