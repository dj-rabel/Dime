<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ServicesController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return array();
    }
}