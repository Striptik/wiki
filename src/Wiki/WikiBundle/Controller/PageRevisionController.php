<?php

namespace Wiki\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wiki\WikiBundle\Entity\PageRevision;
use Wiki\WikiBundle\Entity\Page;
use Wiki\WikiBundle\Form\PageRevisionType;
use Wiki\WikiBundle\Form\PageType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class PageRevisionController extends Controller
{
    /**
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"pageRevision"})
     * @Rest\Get("/pages/{page_slug}/revision")
     *
     * @return array
     */
    public function getPageRevisionAction(Request $request)
    {
        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findOneBy(array('slug' => $request->get('page_slug')));

        if (empty($page)) {
            return $this->pageNotFound();
        }

        return $page->getRevisions();
    }

    /**
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"pageRevision"})
     * @Rest\Post("/pages/{page_slug}/revision")
     *
     * @return array
     */
    public function postPageRevisionAction(Request $request)
    {
        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findOneBy(array('slug' => $request->get('page_slug')));

        if (empty($page)) {
            return $this->pageNotFound();
        }

        $pageRevision = new PageRevision($page);

        $form = $this->createForm(PageRevisionType::class, $pageRevision);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($pageRevision);
            $em->flush();

            return $pageRevision;
        } else {
            return $form;
        }
    }

    private function pageNotFound()
    {
        return View::create(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
    }
}
