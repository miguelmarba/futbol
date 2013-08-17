<?php
 /**
 * Itsa Interactive (http://www.itsainteractive.com/)
 *
 * @autor Miguel A. Martinez
 * @version 1.1
 * @copyright Copyright (c) 2013-2013 Itsa Interactive SA de CV
 * @license   http://www.itsainteractive.com/license/contactcenter
 */

namespace Application\Controller;

use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Model\User;
use Application\Form\PasswordForm; 

class SettingsController extends AbstractActionController
{
    protected $userTable;
    
    public function indexAction()
    {
        echo 'Hola que hace';exit;
        return new ViewModel();
    }
    
    public function passwordAction()
    {
        $form = new PasswordForm();
        $form->get('submit')->setValue('Guardar cambios');
        
        $request = $this->getRequest();
        
        if ( $request->isPost() ) {

            $data = $request->getPost();
            $current_password = $data['current_password'];
            
            $authService = $this->getServiceLocator()->get('AuthService');
            $identity = $authService->getIdentity();
            $user = $this->getUserTable()->getByUser($identity);
            
            $bcrypt = new Bcrypt();
            
            if (!$bcrypt->verify($current_password, $user->password)) {
                
                return array('form' => $form, 'msg_error' => 'La contraseña es incorrecta');
                
            } else {
                $array_user = array(
                    'id_usuario' => $user->id_usuario,
                    'password' => crypt($current_password),
                );
                $this->getUserTable()->changePassword($array_user);
                
                // Redirect to list of formulario
                return $this->redirect()->toRoute('administrator/default', array( 
                    'controller' => 'personal'
                    ));
            }
            
        }
        
        return array('form' => $form);
    }
    
    public function verificapassAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $id = 6;
        
        if ($request->isPost()) {
            $post_data = $request->getPost();
            //$id = $post_data['id'];
            $current_password = $post_data['current_password'];
            
            $authService = $this->getServiceLocator()->get('AuthService');
            $identity = $authService->getIdentity();
            $user = $this->getUserTable()->getByUser($identity);
            
            $bcrypt = new Bcrypt();
            if (!$bcrypt->verify($current_password, $user->password)) {
                $response->setContent(\Zend\Json\Json::encode( array('response' => false )));
            } else {
                $response->setContent(\Zend\Json\Json::encode( array('response' => true )));
            }
        }else {
            $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
        }
        
        return $response;
    }
    
    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Application\Model\UserTable');
        }
            
        return $this->userTable;
    }
}
