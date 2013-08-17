<?php
namespace Administrator\Form;

use Zend\Form\Form;

class CampanaForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('campana');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'nom_campana',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nombre Campaña: ',
            ),
        ));
         $this->add(array(
            'name' => 'desc_campana',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Descripción Campaña: ',
            ),
        ));      
        
        $this->add(array(
            'name' => 'tipo_campana',
            'type'  => 'select',           
            'options' => array(
                'label' => 'Tipo Campaña: ',
                'empty_option' => 'Seleccione',
                'value_options' => array(
                             '1' => 'Inbound',
                             '2' => 'Outbound',
                     ),

            ),
        ));
        $this->add(array(
            'name' => 'status_prendida',
            'type'  => 'select',           
            'options' => array(
                'label' => 'Status Campaña: ',
                'empty_option' => 'Seleccione',
                'value_options' => array(
                             '1' => 'Prendida',
                             '0' => 'Apagada',
                     ),

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
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class'  => 'btn btn-primary',
            ),
        ));
    }
}