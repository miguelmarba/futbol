<?php
namespace Administrator\Form;

use Zend\Form\Form;

class PersonalForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('cat_personal');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->add(array(
            'name' => 'id_personal',
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
            'name' => 'a_paterno',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Apellido paterno',
            ),
            'options' => array(
                'label' => 'Apellido paterno',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'a_materno',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Apellido materno',
            ),
            'options' => array(
                'label' => 'Apellido materno',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'fecha_nacimiento',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => '',
                'id' => 'fecha_nacimiento',
                'class' => 'input-small',
            ),
            'options' => array(
                'label' => 'Fecha nacimiento',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'departamento',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Departamento',
            ),
            'options' => array(
                'label' => 'Departamento',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'cargo',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Cargo',
            ),
            'options' => array(
                'label' => 'Cargo',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'id_rol',
            'type'  => 'select',
            'options' => array(
                'label' => 'Rol / Perfil',
                'class'  => 'control-label',
                'empty_option' => '- Seleccione -',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'telefono_movil',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Telefono movil',
            ),
            'options' => array(
                'label' => 'Telefono movil',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'telefono_casa',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Telefono casa',
            ),
            'options' => array(
                'label' => 'Telefono casa',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'telefono_trabajo',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Telefono trabajo',
            ),
            'options' => array(
                'label' => 'Telefono trabajo',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'fax',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Fax',
            ),
            'options' => array(
                'label' => 'Fax',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'cp',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'CP',
                'id' => 'cp',
            ),
            'options' => array(
                'label' => 'CP',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'calle',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Calle',
            ),
            'options' => array(
                'label' => 'Calle',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'no_interior',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Número interior',
            ),
            'options' => array(
                'label' => 'Número interior',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'no_exterior',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Número interior',
            ),
            'options' => array(
                'label' => 'Número exterior',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'id_estado',
            'type'  => 'select',
            'attributes'=>array(
                'id' => 'id_estado'
            ),
            'options' => array(
                'label' => 'Estado',
                'class'  => 'control-label',
                'empty_option' => '- Seleccione -',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'id_municipio',
            'type'  => 'select',
            'attributes'=>array(
                'id' => 'id_municipio'
            ),
            'options' => array(
                'label' => 'Municipio',
                'class'  => 'control-label',
                'empty_option' => '- Seleccione -',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'id_colonia',
            'type'  => 'select',
            'attributes'=>array(
                'id' => 'id_colonia'
            ),
            'options' => array(
                'label' => 'Colonia',
                'class'  => 'control-label',
                'empty_option' => '- Seleccione -',
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