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
use Administrator\Model\Recurso;
use Administrator\Form\RecursoForm; 

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class RecursosController extends AbstractActionController
{
    protected $recursoTable;
    
    public function indexAction()
    {
//        $resulRecursos = $this->getRecursoTable()->listarDetalles();
//        //var_dump($resulRecursos);exit;
//        foreach($resulRecursos as $row):
//            echo $row['nombre'] . '-' . $row['hereda'] . '<br />';
//        endforeach;
//        exit;
//        var_dump($paginator);exit;
        
        $viewmodel = new ViewModel();
        
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $itemsPerPage = 2;
        
        $datos = array();
        $resulRecursos = $this->getRecursoTable()->listarDetalles();
        foreach($resulRecursos as $row):
            $datos[] = array(
                'id_recurso' => $row['id_recurso'],
                'id_padre' => $row['id_padre'],
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'eliminado' => $row['eliminado'],
                'hereda' => $row['hereda'],
            );
        endforeach;
        
        $paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($datos));

        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage);

        $viewmodel->setVariables(array(
            //'id_usuario' => $id_usuario,
            'page' => $page,
            'paginator' => $paginator,
        ));
        
        return $viewmodel;
    }
    
    public function addAction()
    {
        $form = new RecursoForm();
        $form->get('submit')->setValue('Guardar');
        
        $request = $this->getRequest();
        
        //Colocamos los recursos padres
        $resultModulos = $this->getRecursoTable()->listarModulos();
        
        $modulos['0'] = '- Ninguno -'; 
        foreach( $resultModulos as $row ):
            $modulos[$row->id_recurso] = $row->descripcion; 
        endforeach;
//        var_dump($modulos);
         
        $form->get('id_padre')->setValueOptions($modulos);

        if ( $request->isPost() ) {
            $recurso = new Recurso();
            
            $form->setInputFilter( $recurso->getInputFilter() );
            $datos = $request->getPost();
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                //echo 'es valido';exit;
                $datos = $form->getData();
                $recurso->exchangeArray($form->getData());
                
                //var_dump($recurso);exit;
                $this->getRecursoTable()->save($recurso);

                // Redirect to list of resources
                return $this->redirect()->toRoute('administrator/default', array(
                    'controller' => 'recursos'
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
                'controller' => 'recursos'
            ));
        }
        
        $recurso = $this->getRecursoTable()->get($id);
        
        $form  = new RecursoForm();
        $form->bind($recurso);
        $form->get('submit')->setAttribute('value', 'Editar');
        
        $request = $this->getRequest();
        
        //Colocamos los recursos padres
        $resultModulos = $this->getRecursoTable()->listarModulos();
        
        $modulos['0'] = '- Ninguno -'; 
        foreach( $resultModulos as $row ):
            $modulos[$row->id_recurso] = $row->descripcion; 
        endforeach;
        $form->get('id_padre')->setValueOptions($modulos);

        
        if ($request->isPost()) {
            
            $form->setInputFilter($rol->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $this->getRecursoTable()->save($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('administrator/default', array( 
                    'controller' => 'recursos'
                    ));
            }
            
        }
        //var_dump($form);exit;
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('administrator');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('administrator');
        }

        return array(
            'id'    => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }

    public function permisosAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }
    
    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }
    
    public function getRecursoTable()
    {
        if (!$this->recursoTable) {
            $sm = $this->getServiceLocator();
            $this->recursoTable = $sm->get('Administrator\Model\RecursoTable');
        }
            
        return $this->recursoTable;
    }
    
}
