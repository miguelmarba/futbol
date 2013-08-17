<?php

/**
 * Clase de Identidad User
 *
 * @author Miguel A. Martínez
 */

namespace Administrator\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use \DateTime;

class Personal {
    public $id_personal; //integer
    public $nombre; //string
    public $a_paterno; //string
    public $a_materno; //string
    public $fecha_nacimiento; //datatime
    public $fecha_alta; //datatime
    public $id_creado_por; //integer
    public $fecha_modificado; //datatime
    public $id_modificado_por; //int eger
    public $id_rol; //integer
    public $departamento; //string
    public $cargo; //string
    public $telefono_movil; //string
    public $telefono_casa; //string
    public $telefono_trabajo; //string
    public $email_personal; //string
    public $email_trabajo; //string
    public $fax; //string
    public $cp; //string
    public $calle; //string
    public $no_interior; //string
    public $no_exterior; //string
    public $id_pais; //integer
    public $id_estado; //integer
    public $id_municipio; //integer
    public $id_colonia; //integer
    public $esta_activo; //boolean
    public $eliminado; //boolean
    
    
    public function exchangeArray($data)
    {
        $this->id_personal = (isset ($data['id_personal'])) ? $data['id_personal'] : null;
        $this->nombre = (isset ($data['nombre'])) ? $data['nombre'] : null;
        $this->a_paterno = (isset ($data['a_paterno'])) ? $data['a_paterno'] : null;
        $this->a_materno = (isset ($data['a_materno'])) ? $data['a_materno'] : null;
        $this->fecha_nacimiento = (isset ($data['fecha_nacimiento'])) ? $data['fecha_nacimiento'] : null;
        $this->fecha_alta = (isset ($data['fecha_alta'])) ? $data['fecha_alta'] : null;
        $this->id_creado_por = (isset ($data['id_creado_por'])) ? $data['id_creado_por'] : null;
        $this->fecha_modificado = (isset ($data['fecha_modificado'])) ? $data['fecha_modificado'] : null;
        $this->id_modificado_por = (isset ($data['id_modificado_por'])) ? $data['id_modificado_por'] : null;
        $this->id_rol = (isset ($data['id_rol'])) ? $data['id_rol'] : null;
        $this->departamento = (isset ($data['departamento'])) ? $data['departamento'] : null;
        $this->cargo = (isset ($data['cargo'])) ? $data['cargo'] : null;
        $this->telefono_movil = (isset ($data['telefono_movil'])) ? $data['telefono_movil'] : null;
        $this->telefono_casa = (isset ($data['telefono_casa'])) ? $data['telefono_casa'] : null;
        $this->email_personal = (isset ($data['telefono_trabajo'])) ? $data['telefono_trabajo'] : null;
        $this->email_trabajo = (isset ($data['email_personal'])) ? $data['email_personal'] : null;
        $this->telefono_trabajo = (isset ($data['email_trabajo'])) ? $data['email_trabajo'] : null;
        $this->fax = (isset ($data['fax'])) ? $data['fax'] : null;
        $this->cp = (isset ($data['cp'])) ? $data['cp'] : null;
        $this->calle = (isset ($data['calle'])) ? $data['calle'] : null;
        $this->no_interior = (isset ($data['no_interior'])) ? $data['no_interior'] : null;
        $this->no_exterior = (isset ($data['no_exterior'])) ? $data['no_exterior'] : null;
        $this->id_pais = (isset ($data['id_pais'])) ? $data['id_pais'] : null;
        $this->id_estado = (isset ($data['id_estado'])) ? $data['id_estado'] : null;
        $this->id_municipio = (isset ($data['id_municipio'])) ? $data['id_municipio'] : null;
        $this->id_colonia = (isset ($data['id_colonia'])) ? $data['id_colonia'] : null;
        $this->esta_activo = (isset ($data['esta_activo'])) ? $data['esta_activo'] : null;
        $this->eliminado = (isset ($data['eliminado'])) ? $data['eliminado'] : null;
        
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
    
    public function getFechaNacimiento($format = null)
    {
        $parts = explode(' ', $this->fecha_nacimiento);
        $string = $parts[0] . 'T' . $parts[1];
        $date = new DateTime($string);
        
        if ( $format !== null ) {
            return $date->format($format);
        }
        
        return $date;
    }
    
    public function getFechaAlta($format = null)
    {
        $parts = explode(' ', $this->fecha_alta);
        $string = $parts[0] . 'T' . $parts[1];
        $date = new DateTime($string);
        
        if ( $format !== null ) {
            return $date->format($format);
        }
        
        return $date;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_personal',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'nombre',
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
                'name'     => 'a_paterno',
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
                'name'     => 'a_materno',
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
                'name'     => 'departamento',
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
                'name'     => 'cargo',
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
                'name'     => 'id_rol',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'telefono_movil',
                'required' => false,
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
                'name'     => 'telefono_casa',
                'required' => false,
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
                'name'     => 'telefono_trabajo',
                'required' => false,
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
                'name'     => 'fax',
                'required' => false,
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
                'name'     => 'cp',
                'required' => false,
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
                            'max'      => 5,
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'calle',
                'required' => false,
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
                'name'     => 'no_interior',
                'required' => false,
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
                'name'     => 'no_exterior',
                'required' => false,
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
                'name'     => 'id_estado',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_municipio',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_colonia',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
