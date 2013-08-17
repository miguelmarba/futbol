<?php
//filename : module/SanAuthWithDbSaveHandler/src/SanAuthWithDbSaveHandler/Form/LoginForm.php
namespace SanAuthWithDbSaveHandler\Form;
 
use Zend\Form\Form;
use Zend\InputFilter;
 
class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct();
          
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-signin');
         
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Usuario: '
            ),
        ));
         
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password: '
            ),
        ));
         
         $this->add(array(
            'name' => 'Loginsubmit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Login',
                'id' => 'Loginsubmit',
                'class' => 'btn btn-large btn-primary'
            ),
        ));
          
        $this->setInputFilter($this->createInputFilter());
    }
     
    public function createInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
 
        //username
        $username = new InputFilter\Input('username');
        $username->setRequired(true);
        $inputFilter->add($username);
         
        //password
        $password = new InputFilter\Input('password');
        $password->setRequired(true);
        $inputFilter->add($password);
 
        return $inputFilter;
    }
}