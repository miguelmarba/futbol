<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administrator\Model\Campana;
use Administrator\Form\CampanaForm; 

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class CampanasController extends AbstractActionController
{
    protected $campanaTable;
    protected $validator;
    protected $prueba;
    
    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $user = $authService->getIdentity();
        
        //$result = $this->getCampanaTable()->fetchAll();
        
        
//        return new ViewModel(array(
//            'campanas' => $result,
//            'user' => $user,
//        ));
        
        
        
        
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $itemsPerPage = 2;
        // grab the paginator from the AlbumTable
        $paginator = $this->getCampanaTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $page);
        // set the number of items per page to 10
        $paginator->setItemCountPerPage($itemsPerPage);

        //var_dump($paginator);exit;
        return new ViewModel(array(
            'campanas' => $paginator,
            'user' => $user,
        ));
    }
    
    public function addAction()
    {
        $form = new CampanaForm();
        $form->get('submit')->setValue('Agregar');
        $form->get('fecha_inicio')->setValue(date('d/m/Y'));
        $form->get('fecha_fin')->setValue(date('d/m/Y'));
                
        $request = $this->getRequest();
        if ($request->isPost()) {            
            
            $campana = new Campana();
            
            $form->setInputFilter($campana->getInputFilter());
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $campana->exchangeArray($form->getData());                  
                
                $this->getCampanaTable()->saveCampana($campana);

                // Redirect to list of albums
                return $this->redirect()->toRoute('campana');
            }
        }        
        return array('form' => $form);
    }
    
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'campanas'
            ));
        }
        $campana = $this->getCampanaTable()->getCampana($id);
        //var_dump($campana);exit;
        $form  = new CampanaForm();
        $form->bind($campana);
        $form->get('submit')->setAttribute('value', 'Guardar');
        $form->get('fecha_inicio')->setValue($campana->getFormatoFecha($campana->fecha_inicio, 'd/m/Y'));
        $form->get('fecha_fin')->setValue($campana->getFormatoFecha($campana->fecha_fin, 'd/m/Y'));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($campana->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCampanaTable()->saveCampana($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'campanas'
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
            return $this->redirect()->toRoute('campana');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCampanaTable()->deleteCampana($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('campana');
        }

        return array(
            'id'    => $id,
            'campana' => $this->getCampanaTable()->getCampana($id)
        );
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }
    
    public function getCampanaTable()
    {
        if (!$this->campanaTable) {
            $sm = $this->getServiceLocator();
            $this->campanaTable = $sm->get('Administrator\Model\CampanaTable');
        }
            
        return $this->campanaTable;
    }
    
     /*public function getValidator()
    {
        if (null === $this->validator) {
            $validator = new RegexValidator('/^\+?\d{11,12}$/');
            $validator->setMessage('Please enter 11 or 12 digits only!',
                                    RegexValidator::NOT_MATCH);

            $this->validator = $validator;
        }

        return $this->validator;
    }*/

}
