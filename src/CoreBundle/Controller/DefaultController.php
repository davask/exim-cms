<?php

namespace Dwl\Exim\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DwlEximCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
