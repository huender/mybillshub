<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getDoctrine()->getManager()->getRepository("AppBundle:Category")->findBy(['user' => $this->getUser(), 'isActive' => true], ['name' => 'asc']);

        return $this->render('default/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('default/about.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request)
    {
        $term = $request->query->get("search");

        $this->get('session')->set('search_term', $term);

        if($term){
            $results = $this->get('app.search')->findByTerm($term);
        }else{
            //TODO: remover gambi
            $results['categories'] = [];
            $results['services'] = [];
            $results['users'] = [];
        }

        return $this->render('default/search.html.twig', array(
            'results' => $results,
            'term' => $term
        ));
    }
}
