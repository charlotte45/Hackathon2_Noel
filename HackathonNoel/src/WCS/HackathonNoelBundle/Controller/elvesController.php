<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 09/11/17
 * Time: 17:04
 */

namespace WCS\HackathonNoelBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use WCS\HackathonNoelBundle\Entity\Child;
use WCS\HackathonNoelBundle\Entity\Gift;

/**
 * Class elvesController
 * @package WCS\HackathonNoelBundle\Controller
 * @Route("elves")
 */
class elvesController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/")
     */
    public function showAll()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Child::class);

        $listChildren = $repository->findAll();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Gift::class);

        $listGifts = $repository->findBy(['done' => 'false']);

        return $this->render('elves/elves.html.twig',
            array(
                'children' => $listChildren,
                'gifts' => $listGifts)
        );
    }

//    public function updateDone($id)
//    {
//        $repository = $this
//            ->getDoctrine()
//            ->getManager()
//            ->getRepository(Gift::class);
//
//        $done = $repository->find($id->)
//    }



}