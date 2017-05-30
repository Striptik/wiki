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
     * @Rest\View(serializerGroups={"page"})
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
     * @Rest\View(serializerGroups={"page"})
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
            ->findBy(array('slug' => $request->get('slug')));

        if (empty($page)) {
            return View::create(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }

        return $page;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"page"})
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

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"page"})
     * @Rest\Delete("/pages/{slug}")
     */
    public function removePageAction(Request $request)
    {
/*        $em = $this
            ->getDoctrine()
            ->getManager();

        $page = $em->getRepository('WikiWikiBundle:Page')
            ->findBySlug($request->get('slug'));*/

        $em = $this->get('doctrine.orm.entity_manager');
        $page = $em->getRepository('WikiWikiBundle:Page')
            ->findOneBy(array('slug' => $request->get('slug')));

        return $page;
        /*$em = $this
            ->getDoctrine()
            ->getManager();

        $page = $em
            ->getRepository('WikiWikiBundle:Page')
            ->find($request->get('slug'));

        if (!$page) {
            echo 'test';
            return;
        }
        echo 'test2';
        return $page;
        foreach ($page->getRevisions() as $revision) {
            $em->remove($revision);
        }

        $em->remove($page);
        $em->flush();*/
    }
}
