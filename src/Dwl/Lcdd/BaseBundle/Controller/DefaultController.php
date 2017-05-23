<?php

namespace Dwl\Lcdd\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DwlLcddBaseBundle:Default:index.html.twig', array('name' => $name));
    }
}
