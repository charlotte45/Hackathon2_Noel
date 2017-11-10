<?php
/**
 * Created by PhpStorm.
 * User: emlv
 * Date: 09/11/17
 * Time: 18:20
 */

namespace WCS\HackathonNoelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class SandyClausController extends Controller
{

    /**
     * @Route("/sandyclaws",name="sandyclaws")
     */
    public function childAndGiftsDoneAction()
    {
        $em = $this->getDoctrine()->getManager();

        $children = $em->getRepository('WCSHackathonNoelBundle:Child')->findAll();
        $childrenDone = [];
        foreach ($children as $child) {
            $noGiftDone = false;
            foreach ($child->getGifts() as $gift) {
                if (!$gift->getDone()) {
                    $noGiftDone = true;
                    break;
                }
            }
            if (!$noGiftDone) {
                $childrenDone[] = $child;
            }
        }


        return $this->render('sandyClaws/listGiftsDone.html.twig', array(
            'children' => $childrenDone,
        ));
    }
}