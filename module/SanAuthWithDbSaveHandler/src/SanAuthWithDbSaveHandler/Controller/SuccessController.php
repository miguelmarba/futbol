<?php
//filename : module/SanAuthWithDbSaveHandler/src/SanAuthWithDbSaveHandler/Controller/SuccessController.php
namespace SanAuthWithDbSaveHandler\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
 
class SuccessController extends AbstractActionController
{   
    public function indexAction()
    {
        //here for test only, you should check session
        //for real application
        
        $viewModel = new ViewModel();
        
        $user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()
                ->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
        
        //var_dump($user);exit;
        
        $viewModel->setVariable('user', 'Algun usuario');
        return $viewModel;
    }
}
