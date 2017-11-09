<?php

namespace WCS\HackathonNoelBundle\Controller;

use WCS\HackathonNoelBundle\Entity\Gift;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Gift controller.
 *
 * @Route("gift")
 */
class GiftController extends Controller
{
    /**
     * Lists all gift entities.
     *
     * @Route("/", name="gift_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gifts = $em->getRepository('WCSHackathonNoelBundle:Gift')->findAll();

        return $this->render('gift/index.html.twig', array(
            'gifts' => $gifts,
        ));
    }

    /**
     * Creates a new gift entity.
     *
     * @Route("/new", name="gift_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $gift = new Gift();
        $form = $this->createForm('WCS\HackathonNoelBundle\Form\GiftType', $gift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gift);
            $em->flush();

            return $this->redirectToRoute('gift_show', array('id' => $gift->getId()));
        }

        return $this->render('gift/new.html.twig', array(
            'gift' => $gift,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a gift entity.
     *
     * @Route("/{id}", name="gift_show")
     * @Method("GET")
     */
    public function showAction(Gift $gift)
    {
        $deleteForm = $this->createDeleteForm($gift);

        return $this->render('gift/show.html.twig', array(
            'gift' => $gift,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing gift entity.
     *
     * @Route("/{id}/edit", name="gift_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Gift $gift)
    {
        $deleteForm = $this->createDeleteForm($gift);
        $editForm = $this->createForm('WCS\HackathonNoelBundle\Form\GiftType', $gift);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gift_edit', array('id' => $gift->getId()));
        }

        return $this->render('gift/edit.html.twig', array(
            'gift' => $gift,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a gift entity.
     *
     * @Route("/{id}", name="gift_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Gift $gift)
    {
        $form = $this->createDeleteForm($gift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($gift);
            $em->flush();
        }

        return $this->redirectToRoute('gift_index');
    }

    /**
     * Creates a form to delete a gift entity.
     *
     * @param Gift $gift The gift entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Gift $gift)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gift_delete', array('id' => $gift->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
