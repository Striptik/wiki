<?php

namespace Wiki\WikiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *    description="Récupère les révisions d'une page en fonction du statut",
     *    output= { "class"=PageRevision::class, "collection"=true, "groups"={"pageRevision"} }
     * )
     *
     * @Rest\View(serializerGroups={"pageRevision"})
     * @Rest\Get("/pages/{page_slug}/revision")
     *
     * @return array
     */
    public function getPageRevisionsAction(Request $request)
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
     * @ApiDoc(
     *    description="Récupère les révisions d'une page en fonction du statut",
     *    output= { "class"=PageRevision::class, "collection"=true, "groups"={"pageRevision"} }
     * )
     *
     * @Rest\View(serializerGroups={"pageRevision"})
     * @Rest\Get("/pages/{page_slug}/revision/{status}")
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

        $pageRevisions = $page->getRevisions();

        foreach ($pageRevisions as $revision) {
            if ($request->get('status') == 'online' && $revision->getStatus() == 'online') {
                return $revision;
            } else if ($request->get('status') == 'pending' && $revision->getStatus() == 'pending') {
                $arrayRevisions[] = $revision;
            } else if ($request->get('status') == 'canceled' && $revision->getStatus() == 'canceled') {
                $arrayRevisions[] = $revision;
            } else if ($request->get('status') == 'draft' && $revision->getStatus() == 'draft') {
                $arrayRevisions[] = $revision;
            }
        }

        if (empty($arrayRevisions)) {
            return $this->revisionNotFound();
        }

        return $arrayRevisions;
    }

    /**
     * @param Request $request
     *
     * @ApiDoc(
     *    description="Créer une révision de page",
     *    input={"class"=PageRevisionType::class, "name"=""}
     * )
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

    /**
     * @param Request $request
     *
     * @ApiDoc(
     *    description="Passe une révision de page online",
     *    input={"class"=PageRevisionType::class, "name"=""}
     * )
     *
     * @Rest\View(serializerGroups={"pageRevision"})
     * @Rest\Patch("/pages/{page_slug}/revision/{id_revision}/online")
     *
     * @return array
     */
    public function patchPageRevisionAction(Request $request)
    {
        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findOneBy(array('slug' => $request->get('page_slug')));

        if (empty($page)) {
            return $this->pageNotFound();
        }

        if (count($page->getRevisions()) !== 0) {
            $pageRevisions = $page->getRevisions();

            foreach ($pageRevisions as $revision) {
                if ($revision->getStatus() == 'online') {
                    $revision->setStatus('canceled');
                    break;
                }
            }
        } else {
            return $this->revisionNotFound();
        }

        $pageRevision = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:PageRevision')
            ->find($request->get('id_revision'));

        $pageRevision->setStatus('online');

        $form = $this->createForm(PageRevisionType::class, $pageRevision);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->merge($pageRevision);
            $em->flush();

            return $pageRevision;
        } else {
            return $form;
        }
    }

    /**
     *
     * @ApiDoc(
     *    description="Supprime une page révision"
     * )
     *
     * @Rest\View(serializerGroups={"pageRevision"})
     * @Rest\Delete("/pages/{page_slug}/revision/{id}")
     */
    public function removePageRevisionAction(Request $request)
    {
        $em = $this->getDoctrine()
            ->getManager();
        $page = $em->getRepository('WikiWikiBundle:Page')
            ->findOneBy(array('slug' => $request->get('page_slug')));

        if (!$page) {
            return $this->pageNotFound();
        }

        if (count($page->getRevisions()) !== 0) {
            $pageRevisions = $page->getRevisions();

            foreach ($pageRevisions as $pageRevision) {
                if ($pageRevision->getId() == $request->get('id')) {
                    $em->remove($pageRevision);
                    $em->flush();

                    return View::create(['message' => 'Revision deleted'], Response::HTTP_NOT_FOUND);
                }
            }
        }

        return $this->revisionNotFound();
    }

    private function pageNotFound()
    {
        return View::create(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
    }

    private function revisionNotFound()
    {
        return View::create(['message' => 'Revision not found'], Response::HTTP_NOT_FOUND);
    }
}
