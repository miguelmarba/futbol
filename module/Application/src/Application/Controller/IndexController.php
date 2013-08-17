<?php
 /**
 * Itsa Interactive (http://www.itsainteractive.com/)
 *
 * @autor Miguel A. Martinez
 * @version 1.1
 * @copyright Copyright (c) 2013-2013 Itsa Interactive SA de CV
 * @license   http://www.itsainteractive.com/license/contactcenter
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        //echo 'Hola que hace';exit;
        return new ViewModel();
    }
    
    public function holaAction()
    {
        echo 'Hola que hace';exit;
        return new ViewModel();
    }
}
