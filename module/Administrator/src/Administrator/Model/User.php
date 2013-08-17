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

class User 
{
    public $id_usuario;
    public $username;
    public $password;
    public $hash;
    public $password_generado_por_sistema;
    public $fecha_cambio_password;
    public $id_personal;
    public $bloqueado;
    public $eliminado;
    public $numero_intentos_acceso;
    public $ultimo_acceso;
    
    public function exchangeArray($data)
    {
        $this->id_usuario = (isset ($data['id_usuario'])) ? $data['id_usuario'] : null;
        $this->username = (isset ($data['username'])) ? $data['username'] : null;
        $this->password = (isset ($data['password'])) ? $data['password'] : null;
        $this->hash = (isset ($data['hash'])) ? $data['hash'] : null;
        $this->password_generado_por_sistema = (isset ($data['password_generado_por_sistema'])) ? $data['password_generado_por_sistema'] : null;
        $this->fecha_cambio_password = (isset ($data['fecha_cambio_password'])) ? $data['fecha_cambio_password'] : null;
        $this->id_personal = (isset ($data['id_personal'])) ? $data['id_personal'] : null;
        $this->bloqueado = (isset ($data['bloqueado'])) ? $data['bloqueado'] : null;
        $this->eliminado = (isset ($data['eliminado'])) ? $data['eliminado'] : null;
        $this->numero_intentos_acceso = (isset ($data['numero_intentos_acceso'])) ? $data['numero_intentos_acceso'] : null;
        $this->ultimo_acceso = (isset ($data['ultimo_acceso'])) ? $data['ultimo_acceso'] : null;
        
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
    
    public function getUltimoAcceso($format = null)
    {
        $parts = explode(' ', $this->ultimo_acceso);
        $string = $parts[0] . 'T' . $parts[1];
        $date = new DateTime($string);
        
        if ( $format !== null ) {
            return $date->format($format);
        }
        
        return $date;
    }
    
    public function getMinutosTrancurridos()
    {
        $date1 = $this->getUltimoAcceso('d-m-Y H:i');
        $date2 = date('d-m-Y H:i');
        
        $segundos = strtotime($date2) - strtotime($date1); //Segundos transcurridos
        $minutos = ($segundos / 60); // Minutos transcurridos
        return $minutos;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_usuario',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_personal',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'username',
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

//            $inputFilter->add($factory->createInput(array(
//                'name'     => 'hash',
//                'required' => true,
//                'filters'  => array(
//                    array('name' => 'StripTags'),
//                    array('name' => 'StringTrim'),
//                ),
//                'validators' => array(
//                    array(
//                        'name'    => 'StringLength',
//                        'options' => array(
//                            'encoding' => 'UTF-8',
//                            'min'      => 1,
//                            'max'      => 100,
//                        ),
//                    ),
//                ),
//            )));
            
//            $inputFilter->add($factory->createInput(array(
//                'name'     => 'real_name',
//                'required' => true,
//                'filters'  => array(
//                    array('name' => 'StripTags'),
//                    array('name' => 'StringTrim'),
//                ),
//                'validators' => array(
//                    array(
//                        'name'    => 'StringLength',
//                        'options' => array(
//                            'encoding' => 'UTF-8',
//                            'min'      => 1,
//                            'max'      => 100,
//                        ),
//                    ),
//                ),
//            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
