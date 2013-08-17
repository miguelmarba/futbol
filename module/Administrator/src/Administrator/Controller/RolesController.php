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
use Administrator\Model\Rol;
use Administrator\Form\RolForm;
use Administrator\Form\PermisosForm;

class RolesController extends AbstractActionController
{
    protected $rolTable;
    
    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $user = $authService->getIdentity();
        $result = $this->getRolTable()->fetchAll();
        return new ViewModel(array(
            'rol' => $result,
            'user' => $user,
        ));
    }
    
    public function addAction()
    {
        $form = new RolForm();
        $form->get('submit')->setValue('Guardar');
        
        $request = $this->getRequest();
        
        if ( $request->isPost() ) {

            $rol = new Rol();
            
            $form->setInputFilter( $rol->getInputFilter() );
            
            $datos = $request->getPost();
            //var_dump( $datos );exit;
            $form->setData($request->getPost());
            //var_dump( $form );exit;
            if ($form->isValid()) {
                $datos = $form->getData();
                $rol->exchangeArray($form->getData());
                $this->getRolTable()->save($rol);

                // Redirect to list of albums
                return $this->redirect()->toRoute('administrator/default', array(
                    'controller' => 'roles'
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
                'controller' => 'roles'
            ));
        }
        
        $rol = $this->getRolTable()->get($id);
        
        $form  = new RolForm();
        $form->bind($rol);
        $form->get('submit')->setAttribute('value', 'Editar');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setInputFilter($rol->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $this->getRolTable()->save($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('administrator/default', array( 
                    'controller' => 'roles'
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
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'roles'
            ));
        }
        $form = new PermisosForm();
        $form->get('submit')->setValue('Buscar');
        
        
        $id_rol = $this->getRolTable()->get($id);
        
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            //var_dump($data);exit;
            
            $id_rol = $data["id_rol"];
            
            //Listamos los Recursos padres
            $recursos = $this->getRecursoTable()->listarModulos();
            foreach($recursos as $row):
                $idRecursoPadre = $row->id_recurso;
                $recursosHijos = $this->getRecursoTable()->listarRecursosHijos($idRecursoPadre);
                
                foreach($recursosHijos as $rec):
                    $idRecursoHijo = $rec->id_recurso;
                    $buscar = 'permiso-' . $idRecursoPadre . '-' . $idRecursoHijo;
                    
                    //echo $idRecursoPadre . '.' . $idRecursoHijo. '.' . $data[$buscar] . '<br>';
                    
                    if(isset($data[$buscar])){
                        $datos = array(
                            'id_rol'            => $id_rol,
                            'id_recurso_padre'  => $idRecursoPadre,
                            'id_recurso_hijo'   => $idRecursoHijo,
                            'activo'            => $data[$buscar],
                        );
                        
                        $existe = $this->getPermisoTable()->existe($id_rol, $idRecursoPadre, $idRecursoHijo);
                        //var_dump($recursos);exit;
                        if($existe){ // Si ya esta registrado, solo se actualiza
                            $this->getPermisoTable()->update($datos);
                        } else {
                            if($data[$buscar] == '1')
                                $this->getPermisoTable()->save($datos);
                        }
                    }
                    
                endforeach;
            endforeach;
            //exit;
            return $this->redirect()->toRoute('administrator/default', array(
                'controller' => 'roles',
                'action' => 'permisos',
                'id' => $id_rol
            ));
        }
        
        //Listamos los Recursos padres
        $recursos = $this->getRecursoTable()->listarModulos();
        
        $acordion = '<div class="accordion" id="resources">';
        
        foreach($recursos as $row):
            $acordion .= '<div class="accordion-group">';
            
        
            $acordion .= '<div class="accordion-heading">';
            $acordion .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="#resources" href="#' .$row->nombre .'">';
            $acordion .= $row->descripcion;
            $acordion .= '</a>';
            $acordion .= '<div id="' . $row->nombre . '" class="accordion-body collapse in">';
            $acordion .= '<div class="accordion-inner">';
            
            
            $idRecursoPadre = $row->id_recurso;
            
            $recursosHijos = $this->getRecursoTable()->listarRecursosHijos($idRecursoPadre);
            $permisos = $this->getPermisoTable()->listarPermisos($id_rol, $idRecursoPadre);
                    $permisosGuardados = array();
                    foreach($permisos as $permiso):
                        $permisosGuardados[] = array(
                            'id_recurso_hijo' => $permiso['id_recurso_hijo'],
                            'activo' => $permiso['activo']
                        );
                    endforeach;
                $acordion .= '<table class="table">';
                    $acordion .= '<tr>';
                    $acordion .= '<th>Recurso</th>';
                    $acordion .= '<th>Ajustar</th>';
                    $acordion .= '<th>Seleccionado</th>';
                    $acordion .= '</tr>';
                    
                    foreach($recursosHijos as $rec):
                        //verificamos si esta activo o inactivo el permiso
                        $selecPermitido = '';
                        foreach($permisosGuardados as $permiso):
                            if($rec->id_recurso == $permiso['id_recurso_hijo']){
                                if($permiso['activo'] == 1){
                                    $selecPermitido = 'selected';
                                    break;
                                }
                            }
                        endforeach;
                        $acordion .= '<tr>';
                        $acordion .= '<th>' . $rec->descripcion . '</th>';
                        $acordion .= '<th><select class="input-medium" name="permiso-'. $idRecursoPadre . '-' . $rec->id_recurso . '">';
                        $acordion .= '<option value="0">No Permitido</option>';
                        $acordion .= '<option ' . $selecPermitido . ' value="1">Permitido</option></select></th>';
                        $acordion .= '<th>' . $rec->id_recurso . '</th>';
                        $acordion .= '</tr>';
                    endforeach;
                $acordion .= '</table>';
                
            
            $acordion .= '</div>';
            $acordion .= '</div>';
            $acordion .= '</div>';
            
            $acordion .= '</div>';
        endforeach;
        $acordion .= '</div>';
        
        return array(
            'id'        => $id,
            'rol'       => $rol->descripcion,
            'acordion'  => $acordion,
            'form'      => $form,
        );
    }
    
    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }
    public function getRolTable()
    {
        if (!$this->rolTable) {
            $sm = $this->getServiceLocator();
            $this->rolTable = $sm->get('Administrator\Model\RolTable');
        }
            
        return $this->rolTable;
    }
    
    public function getRecursoTable()
    {
        if (!$this->recursoTable) {
            $sm = $this->getServiceLocator();
            $this->recursoTable = $sm->get('Administrator\Model\RecursoTable');
        }
            
        return $this->recursoTable;
    }
    
    public function getPermisoTable()
    {
        if (!$this->permisoTable) {
            $sm = $this->getServiceLocator();
            $this->permisoTable = $sm->get('Administrator\Model\PermisoTable');
        }
            
        return $this->permisoTable;
    }
}
