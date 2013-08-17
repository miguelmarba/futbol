<?php

/**
 * Description of Campana
 *
 * @author Alika Mena
 */

namespace Administrator\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use \DateTime;

class Campana {
    public $id;
    public $nom_campana;
    public $desc_campana;
    public $tipo_campana;
    public $status_prendida;
    public $fecha_inicio;
    public $fecha_fin;
    
    public function exchangeArray($data)
    {
        $this->id = (isset ($data['id'])) ? $data['id'] : null;
        $this->nom_campana = (isset ($data['nom_campana'])) ? $data['nom_campana'] : null;
        $this->desc_campana = (isset ($data['desc_campana'])) ? $data['desc_campana'] : null;
        $this->tipo_campana = (isset ($data['tipo_campana'])) ? $data['tipo_campana'] : null;
        $this->status_prendida = (isset ($data['status_prendida'])) ? $data['status_prendida'] : null;
        $this->fecha_inicio = (isset ($data['fecha_inicio'])) ? $data['fecha_inicio'] : null;
        $this->fecha_fin = (isset ($data['fecha_fin'])) ? $data['fecha_fin'] : null;
       
    }
     public function getFormatoFecha($date, $format = null)
    {
        $parts = explode(' ', $date);
        $string = $parts[0] . 'T' . $parts[1];
        $date = new DateTime($string);
        
        if ( $format !== null ) {
            return $date->format($format);
        }
        
        return $date;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    // Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'nom_campana',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));  
            $inputFilter->add($factory->createInput(array(
                'name'     => 'desc_campana',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'tipo_campana',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ))); 
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'status_prendida',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ))); 
            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}
