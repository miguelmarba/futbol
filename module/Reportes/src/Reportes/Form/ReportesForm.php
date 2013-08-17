<?php
namespace Reportes\Form;

use Zend\Form\Form;

class ReportesForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('reportes');
        $this->setAttribute('method', 'post');
        
         $this->add(array(
            'name' => 'id_usuario',
            'type'  => 'select',           
            'options' => array(
                'label' => 'Usuario: ',
                'empty_option' => 'Todos',
                
            ),
        ));
        $this->add(array(
            'name' => 'id_perfil',
            'type'  => 'select',           
            'options' => array(
                'label' => 'Perfil: ',
                'empty_option' => 'Todos',
                
            ),
        ));
        
        $this->add(array(
            'name' => 'fecha_inicio',            
            'type'  => 'text',
            'options' => array(
                'label' => 'Fecha Inicio: ',                
            ),
            'attributes' => array(
                'id' => 'fecha_inicio',
            ),
        ));
        $this->add(array(
            'name' => 'fecha_fin',
            'type'  => 'text',
            'options' => array(
                'label' => 'Fecha Fin: ',
            ),
            'attributes' => array(
                'id' => 'fecha_fin',
            ),
        ));     
         $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'button',
                'value' => 'Consultar',
                'id' => 'submit',
                'class'  => 'btn btn-primary',
            ),
        ));
    }
}