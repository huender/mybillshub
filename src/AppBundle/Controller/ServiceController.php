<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Service;
use AppBundle\Form\ServiceType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Service controller.
 *
 * @Route("/service")
 */
class ServiceController extends Controller
{
    /**
     * Lists all Service entities.
     *
     * @Route("/", name="service_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $services = $em->getRepository('AppBundle:Service')->findBy(
            ['user' => $this->getUser()->getId()],
            ['id' => 'DESC']
        );

        return $this->render('service/index.html.twig', array(
            'services' => $services,
        ));
    }

    /**
     * Creates a new Service entity.
     *
     * @Route("/new", name="service_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $service = new Service();
        $service->setUser($this->getUser());
        $form = $this->createForm('AppBundle\Form\ServiceType', $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($service->getIsRecurrence()){
                $service->setPaymentDate(null);
            }
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('service_show', array('id' => $service->getId()));
        }

        return $this->render('service/new.html.twig', array(
            'service' => $service,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Service entity.
     *
     * @Route("/{id}", name="service_show")
     * @Method("GET")
     */
    public function showAction(Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);

        if(!$service or $service->getUser()->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        return $this->render('service/show.html.twig', array(
            'service' => $service,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Service entity.
     *
     * @Route("/{id}/edit", name="service_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);
        $editForm = $this->createForm('AppBundle\Form\ServiceType', $service);
        $editForm->handleRequest($request);

        if(!$service or $service->getUser()->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('service_show', array('id' => $service->getId()));
        }

        return $this->render('service/edit.html.twig', array(
            'service' => $service,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Service entity.
     *
     * @Route("/{id}", name="service_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Service $service)
    {
        $form = $this->createDeleteForm($service);
        $form->handleRequest($request);

        if(!$service or $service->getUser()->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();
        }

        return $this->redirectToRoute('service_index');
    }

    /**
     * Creates a form to delete a Service entity.
     *
     * @param Service $service The Service entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Service $service)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('service_delete', array('id' => $service->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/{id}/done", name="service_done")
     */
    public function doneAction(Service $service)
    {
        if(!$service or $service->getUser()->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $service->setIsActive(true);
        $em->flush();

        return new JsonResponse(['service' => $service->getId()]);
    }

    /**
     * @Route("/{id}/cancel", name="service_cancel")
     */
    public function cancelAction(Service $service)
    {
        if(!$service or $service->getUser()->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $service->setIsActive(false);
        $em->flush();

        return new JsonResponse(['service' => $service->getId()]);
    }
}