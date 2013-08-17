<?php
//filename : module/SanAuthWithDbSaveHandler/src/SanAuthWithDbSaveHandler/Controller/AuthController.php
namespace SanAuthWithDbSaveHandler\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
 
class AuthController extends AbstractActionController
{
    protected $authService;
     
    //we will inject authService via factory
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }
     
    public function indexAction()
    {
        $this->layout('layout/login');
        if ($this->authService->getStorage()->getSessionManager()
                 ->getSaveHandler()
                 ->read($this->authService->getStorage()->getSessionId())) {
            //redirect to success controller...
            return $this->redirect()->toRoute('success');
        }
         
        $form = $this->getServiceLocator()
                     ->get('FormElementManager')
                     ->get('SanAuthWithDbSaveHandler\Form\LoginForm');   
        $viewModel = new ViewModel();
         
        //initialize error...
        $viewModel->setVariable('error', '');
        //authentication block...
        $this->authenticate($form, $viewModel);
         
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }
     
    /** this function called by indexAction to reduce complexity of function */
    protected function authenticate($form, $viewModel)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $dataform = $form->getData();
                
                $this->authService->getAdapter()
                                       ->setIdentity($dataform['username'])
                                       ->setCredential(md5($dataform['password']));
                $result = $this->authService->authenticate();
                
                if ($result->isValid()) {
                    //authentication success
                    $resultRow = $this->authService->getAdapter()->getResultRowObject();
                    
                    /* 
                     * Detalles del usuario loqgueado
                     * Obtiene los datos adicional del personal.
                     */
                    $user = $this->getUserTable()->getByUser($dataform['username']);
                    $id_personal = $user->id_personal;
                    $person = $this->getPersonalTable()->get($id_personal);
                    $nombre_completo = $person->nombre . ' ' . $person->a_paterno;
                    $id_rol = $person->id_rol;
                    $id_personal = $person->id_personal;
                    
                    /**
                     * Obtener el rol del usuario logueado
                     */
                    $objRol = $this->getRolTable()->get($id_rol);
                    $rol = $objRol->nombre;
                    
                    
                    $this->authService->getStorage()->write(
                         array('id'                 => $user->id_usuario,
                                'username'          => $dataform['username'],
                                'nombre_completo'   => $nombre_completo,
                                'id_personal'       => $id_personal,
                                'rol'               => $rol,
                                'ip_address'        => $this->getRequest()->getServer('REMOTE_ADDR'),
                                'user_agent'        => $request->getServer('HTTP_USER_AGENT'))
                    );
                     
                    return $this->redirect()->toRoute('success', array('action' => 'index'));;       
                } else {
                    $viewModel->setVariable('error', 'Login Error');
                }
            }
        }
    }
     
    public function logoutAction()
    {
        $this->authService->getStorage()->clear();
        return $this->redirect()->toRoute('auth');
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
    
    public function getRolTable()
    {
        if (!$this->rolTable) {
            $sm = $this->getServiceLocator();
            $this->rolTable = $sm->get('Administrator\Model\RolTable');
        }
            
        return $this->rolTable;
    }
}