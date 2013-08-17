<?php
namespace Administrator\Form;

use Zend\Form\Form;

class PermisosForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('sys_recursos');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'id_recurso',
            'type'  => 'select',
            'options' => array(
                'label' => 'Modulo ',
                'class'  => 'control-label',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'texto',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Texto',
            ),
            'options' => array(
                'label' => 'Texto',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Buscar',
                'id' => 'submitbutton',
                'class'  => 'btn btn-primary',
            ),
        ));
    }
}