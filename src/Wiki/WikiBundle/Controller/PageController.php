<?php

namespace Wiki\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wiki\WikiBundle\Entity\Page;
use Wiki\WikiBundle\Form\PageType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class PageController extends Controller
{
    /**
     * @param Request $request
     *
     * @Rest\View()
     * @Rest\Get("/pages")
     *
     * @return array
     */
    public function getPagesAction(Request $request)
    {
        $pages = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findAll();

        return $pages;
    }

    /**
     * @param Request $request
     *
     * @Rest\View()
     * @Rest\Get("/pages/{slug}")
     *
     * @return object
     */
    public function getPageAction(Request $request)
    {
        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findBySlug($request->get('slug'));

        if (empty($page)) {
            return View::create(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }

        return $page;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/pages")
     */
    public function postPagesAction(Request $request)
    {
        $page = new Page();

        $form = $this->createForm(PageType::class, $page);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($page);
            $em->flush();

            return $page;
        } else {
            return $form;
        }
    }
}
