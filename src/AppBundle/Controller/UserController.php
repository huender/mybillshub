<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\EditProfileType;
use AppBundle\Form\Model\ChangePassword;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user_index")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $users = $this->getDoctrine()->getManager()->getRepository("AppBundle:User")->findBy([],['id' => 'DESC']);

        return $this->render(
            'user/index.html.twig',
            array('users' => $users)
        );
    }

    /**
     * @Route("/user/{id}/changepassword", name="user_change_password")
     * @Security("has_role('ROLE_USER')")
     */
    public function changePasswdAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")->findOneBy(["id" => $id]);

        if(!$user or $user->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get("newPassword")->getData();
            $encoder = $this->get('service_container')->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($encoded);

            $em->flush();
            // perform some action,
            // such as encoding with MessageDigestPasswordEncoder and persist
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render(':user:changePasswd.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/user/{id}/editprofile", name="user_edit_profile")
     * @Security("has_role('ROLE_USER')")
     */
    public function editProfileAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")->findOneBy(["id" => $id]);

        if(!$user or $user->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            // perform some action,
            // such as encoding with MessageDigestPasswordEncoder and persist
            return $this->redirect($this->generateUrl('user_show_profile', ['id' => $user->getId()]));
        }

        return $this->render(':user:edit.html.twig', array(
            'edit_form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * Finds and displays a Service entity.
     *
     * @Route("/user/{id}", name="user_show_profile")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        if(!$user or $user->getId() != $this->getUser()->getId() and !$this->isGranted("ROLE_SUPER_ADMIN")){
            throw new AccessDeniedException();
        }

        return $this->render('user/show.html.twig', array(
            'user' => $user,
        ));
    }
}