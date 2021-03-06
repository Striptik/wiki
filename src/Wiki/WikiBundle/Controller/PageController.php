<?php

namespace Wiki\WikiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wiki\WikiBundle\Entity\Page;
use Wiki\WikiBundle\Form\PageType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

class PageController extends Controller
{
    /**
     * @param Request $request
     *
     * @ApiDoc(
     *    description="Récupère la liste des pages du wiki",
     *    output= { "class"=Page::class, "collection"=true, "groups"={"page"} }
     * )
     *
     * @Rest\View(serializerGroups={"page"})
     * @Rest\Get("/pages")
     *
     * @return array
     */
    public function getPagesAction()
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
     * @ApiDoc(
     *    description="Récupère les 10 dernières pages publiées (10 par défaut)",
     *    output= { "class"=Page::class, "collection"=true, "groups"={"page"} }
     * )
     *
     * @Rest\View(serializerGroups={"page"})
     * @Rest\Get("/pages/last")
     * @QueryParam(name="offset", requirements="\d+", default="", description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default="", description="Index de fin de la pagination")
     *
     * @return array
     */
    public function getLastPagesAction(Request $request, ParamFetcher $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder();

        $qb->select('p')
            ->from('WikiWikiBundle:Page', 'p')
            ->orderBy('p.createdAt', 'DESC');

        if ($offset != "") {
            $qb->setFirstResult($offset);
        }

        if ($limit != "") {
            $qb->setMaxResults($limit);
        }

        $pages = $qb->getQuery()->getResult();

        return $pages;
    }

    /**
     * @param Request $request
     *
     * @ApiDoc(
     *    description="Effectue une recherche sur les titres de pages",
     *    output= { "class"=Page::class, "collection"=true, "groups"={"page"} }
     * )
     * @Rest\View(serializerGroups={"page"})
     * @Rest\Get("/pages/search")
     * @QueryParam(name="q", requirements=".+", default="", description="Query à rechercher")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Index de fin de la pagination")
     *
     * @return array
     */
    public function getSearchPagesAction(Request $request, ParamFetcher $paramFetcher)
    {
        $q = $paramFetcher->get('q');
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder();

        $qb->select('p')
            ->from('WikiWikiBundle:Page', 'p')
            ->orderBy('p.createdAt', 'DESC');

        if ($q != "") {
            $qb->where('p.title LIKE :q')
            ->setParameter('q', '%'.$q.'%');
        }

        if ($offset != "0") {
            $qb->setFirstResult($offset);
        }

        if ($limit != "10") {
            $qb->setMaxResults($limit);
        }

        $pages = $qb->getQuery()->getResult();

        if (empty($pages)) {
            return $this->pageNotFound();
        }

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
            return $this->pageNotFound();
        }

        return $page;
    }

    /**
     *
     * @ApiDoc(
     *    description="Créer une page",
     *    input={"class"=PageType::class, "name"=""}
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"page"})
     * @Rest\Post("/pages")
     */
    public function postPagesAction(Request $request)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('WikiWikiBundle:User')
            ->findOneBy(array('id' => $request->get('userId')));
        $page = new Page($user);

        $pageSub = $request->request->all();
        unset($pageSub['userId']);

        $form = $this->createForm(PageType::class, $page);

        $form->submit($pageSub); // Validation des données

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
     *
     * @ApiDoc(
     *    description="Supprime une page"
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"page"})
     * @Rest\Delete("/pages/{slug}")
     */
    public function removePageAction(Request $request)
    {

        $em = $this->getDoctrine()
            ->getManager();
        $page = $em->getRepository('WikiWikiBundle:Page')
            ->findOneBy(array('slug' => $request->get('slug')));

        if (!$page) {
            return $this->pageNotFound();
        }

        foreach ($page->getRevisions() as $pageRevision) {
            $em->remove($pageRevision);
        }

        $em->remove($page);
        $em->flush();

        return View::create(['message' => 'Page deleted'], Response::HTTP_NOT_FOUND);
    }

    private function pageNotFound()
    {
        return View::create(['error' => 'Page non trouvée'], Response::HTTP_NOT_FOUND);
    }
}
