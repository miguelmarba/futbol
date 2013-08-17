<?
namespace Application\Form;
 
use Zend\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class PasswordForm extends Form
{
    public function __construct ($name = null, $options = array())
    {
        parent::__construct('passwordform');
         
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal password-container');
         
        // Nombre de usuario
        $this->add(array(
            'name' => 'current_password',
            'attributes' => array(
                'type'  => 'Password',
                'id'  => 'current_password',
            ),
            'options' => array(
                'label' => 'Contraseña actual',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
         
        // Contraseña
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'Password',
                'id'  => 'password',
                'class'  => 'strong-password',
            ),
            'options' => array(
                'label' => 'Nueva contraseña',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'password_confirmation',
            'attributes' => array(
                'type'  => 'Password',
                'id'  => 'password_confirmation',
                'class'  => 'strong-password',
            ),
            'options' => array(
                'label' => 'Verificar contraseña',
                'label_attributes' => array(
                    'class'  => 'control-label',
                ),
            ),
        ));
         
        // Proteccion CSRF
//        $this->add(array(
//            'type' => 'Zend\Form\Element\Csrf',
//            'name' => 'csrfcheck',
//        ));
         
        // Botón submit
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Guardar',
                'id' => 'submitbutton',
                'disabled' => 'true',
                'class'  => 'btn btn-primary disabled',
            ),
        ));
    }
    
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'current_password',
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
                'name'     => 'password',
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
                            'min'      => 6,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'password_confirmation',
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
                            'min'      => 6,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
