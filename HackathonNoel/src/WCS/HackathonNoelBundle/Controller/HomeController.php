<?php

namespace WCS\HackathonNoelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WCS\HackathonNoelBundle\Entity\Child;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use WCS\HackathonNoelBundle\Entity\Gift;

/**
 * Class HomeController
 *
 * @Route("home")
 */
class HomeController extends Controller
{
    /**
     *
     * @Route("/", name="home_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $errors = '';

        $child = new Child();
        $form = $this->createForm('WCS\HackathonNoelBundle\Form\ChildType', $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // set lat/long
            // formating string search for request
            $api_key = "AIzaSyAX-RkiYk0DFEO5jFJK4ChAvW1V8B0n9AQ";
            $address = '';
            $address .= str_replace(" ", "+", $child->getAddress());
            $address .= str_replace(" ", "+", $child->getCity());
            $address .= str_replace(" ", "+", $child->getZipcode());
            $address .= str_replace(" ", "+", $child->getCountry());

            $request = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=" . $api_key;
            $response = file_get_contents($request);
            $resp_array = json_decode($response, true);

            if ($resp_array['status'] == 'OK') {
                $resp_array = $resp_array['results'][0]['geometry']['location'];

                $child->setLatitude($resp_array['lat']);
                $child->setLongitude($resp_array['lng']);
                $em = $this->getDoctrine()->getManager();
                $em->persist($child);
                $em->flush();

                return $this->redirectToRoute('choose_gifts', array(
                    'child_id' => $child->getId()
                    ));
            } else {
                $errors = '1';
            }
        }

        return $this->render('index.html.twig', array(
            'child' => $child,
            'form' => $form->createView(),
            'errors' => $errors,
        ));

    }

    /**
     *
     * @Route("/choose/{child_id}", name="choose_gifts")
     * @Method({"GET", "POST"})
     */
    public function chooseGiftsAction(Request $request, $child_id)
    {
        $em = $this->getDoctrine()->getManager();
        $child = $em->getRepository('WCSHackathonNoelBundle:Child')->find($child_id);

        $gift = new Gift();
        $form = $this->createForm('WCS\HackathonNoelBundle\Form\GiftType', $gift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gift->setDone(false);
            $gift->setChild($child);
            $em = $this->getDoctrine()->getManager();
            $em->persist($gift);
            $em->flush();

            return $this->redirectToRoute('choose_gifts', array('child_id' => $child->getId()));
        }

        return $this->render('choosegifts.html.twig', array(
            'child' => $child,
            'form' => $form->createView(),
        ));

    }

    /**
     *
     * @Route("/success/{id}", name="success")
     * @Method({"GET", "POST"})
     */
    public
    function successAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $child = $em->getRepository('WCSHackathonNoelBundle:Child')->find($id);

        return $this->render('success.html.twig', array(
            'child' => $child,
        ));

    }
}
