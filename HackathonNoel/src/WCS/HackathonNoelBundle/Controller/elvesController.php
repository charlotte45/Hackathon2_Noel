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
     * @Route("/", name="show_all")
     */
    public function showAll()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Child::class);

        $listChildren = [];
        $children = $repository->findAll();
        foreach ($children as $child) {
            foreach ($child->getGifts() as $gift) {
                if(!$gift->getDone()) {
                    $listChildren[$child->getId()] = $child;
                }
            }
        }

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

    /**
     * @param Request $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/updateDone/{id}", name="done")
     */
    public function doneAction(Gift $gift)
    {
        if ($gift->getDone() == false) {
            $em = $this->getDoctrine()->getManager();
            $gift->setDone(true);
            $em->flush();
        } else {
            $em = $this->getDoctrine()->getManager();
            $gift->setDone(false);
            $em->flush();
        }


        return $this->redirectToRoute('show_all');
    }

}
