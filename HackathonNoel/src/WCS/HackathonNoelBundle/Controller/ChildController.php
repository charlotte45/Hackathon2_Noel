<?php

namespace WCS\HackathonNoelBundle\Controller;

use WCS\HackathonNoelBundle\Entity\Child;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Child controller.
 *
 * @Route("child")
 */
class ChildController extends Controller
{
    /**
     * Lists all child entities.
     *
     * @Route("/", name="child_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $children = $em->getRepository('WCSHackathonNoelBundle:Child')->findAll();

        return $this->render('child/index.html.twig', array(
            'children' => $children,
        ));
    }

    /**
     * Creates a new child entity.
     *
     * @Route("/new", name="child_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $child = new Child();
        $form = $this->createForm('WCS\HackathonNoelBundle\Form\ChildType', $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($child);
            $em->flush();

            return $this->redirectToRoute('child_show', array('id' => $child->getId()));
        }

        return $this->render('child/new.html.twig', array(
            'child' => $child,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a child entity.
     *
     * @Route("/{id}", name="child_show")
     * @Method("GET")
     */
    public function showAction(Child $child)
    {
        $deleteForm = $this->createDeleteForm($child);

        return $this->render('child/show.html.twig', array(
            'child' => $child,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing child entity.
     *
     * @Route("/{id}/edit", name="child_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Child $child)
    {
        $deleteForm = $this->createDeleteForm($child);
        $editForm = $this->createForm('WCS\HackathonNoelBundle\Form\ChildType', $child);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('child_edit', array('id' => $child->getId()));
        }

        return $this->render('child/edit.html.twig', array(
            'child' => $child,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a child entity.
     *
     * @Route("/{id}", name="child_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Child $child)
    {
        $form = $this->createDeleteForm($child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($child);
            $em->flush();
        }

        return $this->redirectToRoute('child_index');
    }

    /**
     * Creates a form to delete a child entity.
     *
     * @param Child $child The child entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Child $child)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('child_delete', array('id' => $child->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
