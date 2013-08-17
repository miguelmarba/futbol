<?php
namespace Administrator\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('sys_usuarios');
        $this->setAttribute('method', 'post');
        //$this->setAttribute('class', 'form-horizontal');
        $this->add(array(
            'name' => 'id_usuario',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'id_personal',
            'type'  => 'select',
            'options' => array(
                'label' => 'Personal',
                'class'  => 'control-label',
                'empty_option' => '- Seleccione -',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
                //'autocomplete'  => 'off',
            ),
            'options' => array(
                'label' => 'Cuenta de usuario',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Crea una contraseña',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'password2',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Confirme contraseña',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'fecha_cambio_password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Fecha cambio de password',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'bloqueado',
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
            'name' => 'hash',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'password_generado_por_sistema',
            'attributes' => array(
                'type'  => 'hidden',
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