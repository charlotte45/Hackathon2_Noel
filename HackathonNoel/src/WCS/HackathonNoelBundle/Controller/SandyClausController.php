<?php
/**
 * Created by PhpStorm.
 * User: emlv
 * Date: 09/11/17
 * Time: 18:20
 */

namespace WCS\HackathonNoelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use WCS\HackathonNoelBundle\Entity\Child;

/**
 * Class SandyClausController
 * @package WCS\HackathonNoelBundle\Controller
 * @Route("sandyclaws")
 */
class SandyClausController extends Controller
{

    /**
     * @Route("/",name="sandyclaws")
     */
    public function childAndGiftsDoneAction()
    {
        $em = $this->getDoctrine()->getManager();

        $children = $em->getRepository('WCSHackathonNoelBundle:Child')->findAll();
        $childrenDone = [];
        $stay = [];
        foreach ($children as $child) {
            $noGiftDone = false;
            foreach ($child->getGifts() as $gift) {
                if (!$gift->getDone()) {
                    $noGiftDone = true;
                    break;
                }
            }
            if (!$noGiftDone) {
                $child->visited = false;
                $stay[] = $child;
                $childrenDone[] = $child;
            }
        }

        // Traitement des coord
        $destination = array('northpole' => ['lat' => 90, 'lng' => 0]);
        $nb_stay = 0;
        foreach ($stay as $child) {
            $nb_stay += ($child->visited) ? 0 : 1;
        }

        while ($nb_stay) {
            reset($stay);
            $key = key($stay);
            $first = $stay[$key];

            if (count($destination) == 1) {
                $a = $destination['northpole'];
            } else {
                $obj = $destination[count($destination) - 2];
                $a = ['lat' => $obj->getLatitude(), 'lng' => $obj->getLongitude()];
            }

            $first = ['lat' => $first->getLatitude(), 'lng' => $first->getLongitude()];
            $min = $this->distOrtho($a, $first);
            $indMin = count($destination) - 1;

            foreach ($stay as $ind => $currentStay) {
                if ($currentStay->visited) {
                    continue;
                }
                $b = ['lat' => $currentStay->getLatitude(), 'lng' => $currentStay->getLongitude()];
                $dist = $this->distOrtho($a, $b);
                if ($dist < $min) {
                    $min = $dist;
                    $indMin = $ind;
                }
            }
            $stay[$indMin]->visited = true;
            $destination[] = $stay[$indMin];
            $nb_stay--;
        }

        unset($destination['northpole']);

        return $this->render('sandyClaws/listGiftsDone.html.twig', array(
            'children' => $childrenDone,
        ));
    }

    /**
     * Deletes a child entity.
     *
     * @Route("/suppr/{id}", name="childsuppr")
     * @Method("GET")
     */
    public function deleteAction(Child $child)
    {
        $em = $this->getDoctrine()->getManager();

        $child = $em->getRepository('WCSHackathonNoelBundle:Child')->find($child);

        $em = $this->getDoctrine()->getManager();
        $em->remove($child);
        $em->flush();

        return $this->redirectToRoute('sandyclaws');
    }

    public function distOrtho($a, $b, $precision = 3, $r = 6378.14)
    {
        $x1 = $a['lat'];
        $x2 = $b['lat'];
        $y1 = $a['lng'];
        $y2 = $b['lng'];
        // La variable $r correspond au rayon de la Terre.
        // $x1, $x2 sont les latitudes de chaques points respectifs.
        // $y1, $y2 sont les longitudes de chaques points respectifs.
        // $precision permet d'obtenir le nombre de chiffre après la virgule.
        // Elle est définit à 3 par défaut permettant d'obtenir une précision au mètre. Il vous suffira de la multiplier par 1000.

        // On convertit les latitudes et longitudes en radian.
        $x1 = deg2rad($x1);
        $x2 = deg2rad($x2);
        $y1 = deg2rad($y1);
        $y2 = deg2rad($y2);

        // Calcule des distances entre les deux points.
        $dlat = $x2 - $x1;
        $dlong = $y2 - $y1;

        // On applique la formule.
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($x1) * cos($x2) * sin($dlong / 2) * sin($dlong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // On récupère la valeur du résutat arrondi avec la précision.
        $d = round($r * $c, $precision);

        // On renvoit la distance en km
        return $d;
    }
}