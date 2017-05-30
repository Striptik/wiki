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
     * @Rest\View(serializerGroups={"pageRevision"})
     * @Rest\Get("/pages/{page_slug}/revision")
     *
     * @return array
     */
    public function getPagesRevisionAction(Request $request)
    {
        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findBySlug($request->get('page_slug'));

        if (empty($page)) {
            return $this->pageNotFound();
        }

        return $page->getRevision();
    }

    private function pageNotFound()
    {
        return View::create(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
    }
}
