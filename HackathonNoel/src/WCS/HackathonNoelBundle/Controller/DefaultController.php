<?php

namespace WCS\HackathonNoelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WCS\HackathonNoelBundle\Entity\Child;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->redirectToRoute('home_index');

    }
}
