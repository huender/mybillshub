<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Notepad;
use AppBundle\Form\NotepadType;

/**
 * Notepad controller.
 *
 * @Route("/notepad")
 */
class NotepadController extends Controller
{
    /**
     * Lists all Notepad entities.
     *
     * @Route("/", name="notepad_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $notepads = $em->getRepository('AppBundle:Notepad')->findBy(
            ['user' => $this->getUser()->getId()],
            ['id' => 'DESC']
        );

        return $this->render('notepad/index.html.twig', array(
            'notepads' => $notepads,
        ));
    }

    /**
     * Creates a new Notepad entity.
     *
     * @Route("/new", name="notepad_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $notepad = new Notepad();
        $service = $request->query->get("service");
        if ($service) {
            $service = $em->getRepository("AppBundle:Service")->findOneBy(["id" => (int)$service]);
            $notepad->setService($service);
        }
        $notepad->setUser($this->getUser());

        $form = $this->createForm('AppBundle\Form\NotepadType', $notepad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($notepad);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['true']);
            } else {
                return $this->redirectToRoute('notepad_show', array('id' => $notepad->getId()));
            }
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('notepad/modal.html.twig', array(
                'notepad' => $notepad,
                'form' => $form->createView(),
            ));
        } else {
            return $this->render('notepad/new.html.twig', array(
                'notepad' => $notepad,
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * Finds and displays a Notepad entity.
     *
     * @Route("/{id}", name="notepad_show")
     * @Method("GET")
     */
    public function showAction(Notepad $notepad)
    {
        $deleteForm = $this->createDeleteForm($notepad);

        return $this->render('notepad/show.html.twig', array(
            'notepad' => $notepad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Notepad entity.
     *
     * @Route("/{id}/edit", name="notepad_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Notepad $notepad)
    {
        $deleteForm = $this->createDeleteForm($notepad);
        $editForm = $this->createForm('AppBundle\Form\NotepadType', $notepad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($notepad);
            $em->flush();

            return $this->redirectToRoute('notepad_show', array('id' => $notepad->getId()));
        }

        return $this->render('notepad/edit.html.twig', array(
            'notepad' => $notepad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Notepad entity.
     *
     * @Route("/{id}", name="notepad_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Notepad $notepad)
    {
        $form = $this->createDeleteForm($notepad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notepad);
            $em->flush();
        }

        return $this->redirectToRoute('notepad_index');
    }

    /**
     * Creates a form to delete a Notepad entity.
     *
     * @param Notepad $notepad The Notepad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Notepad $notepad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notepad_delete', array('id' => $notepad->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}