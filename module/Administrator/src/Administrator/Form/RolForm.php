<?php
namespace Administrator\Form;

use Zend\Form\Form;

class RolForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('sys_roles');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->add(array(
            'name' => 'id_rol',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'eliminado',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Nombre',
            ),
            'options' => array(
                'label' => 'Nombre',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Descripcion',
            ),
            'options' => array(
                'label' => 'Descripción',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Guardar',
                'id' => 'submitbutton',
                'class'  => 'btn btn-primary',
            ),
        ));
    }
}