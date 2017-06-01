<?php

namespace Wiki\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

use Wiki\WikiBundle\Entity\Rating;
use Wiki\WikiBundle\Form\RatingType;

class RatingController extends Controller
{
    /**
     * Get all the reviews

     * @Rest\Get("/ratings")
     *
     * @return array
     */
    public function getRatingsAction()
    {
        $ratings = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Rating')
            ->findAll();

        return $ratings;
    }


    /**
     * Create Review
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/ratings")
     */
    public function postRatingsAction(Request $request)
    {
        $user = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User')
            ->findBy(array('token' => $request->get('userToken')));

        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findBy(array('id' => $request->get('pageId')));

        $isRated = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBubdle:Rating')
            ->findOneBy(array('user' => $user, 'page' => $page));

        if ($isRated) {
            return View::create(['error' => 'Vous avez déjà noté cette page'], Response::HTTP_NOT_FOUND);
        }

        $rating = new Rating($page, $user);

        $form = $this->createForm(RatingType::class, $rating);
        $form->submit($request->request->all()); // Validation des données
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($rating);
            $em->flush();

            return $rating;
        } else {
            return $form;
        }
    }


    /**
     * Get Page Average
     * @Rest\Get("/average/{id}")
     */
    public function getAverageAction(Request $request)
    {
        $page = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Page')
            ->findBy(array('id' => $request->get('pageId')));

        $rates = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Rating')
            ->findBy(array('page' => $page->));



        return $rates;
    }


 //    /**
//     * @param Request $request
//     *
//     * @Rest\Get("/pages/last")
//     * @QueryParam(name="offset", requirements="\d+", default="", description="Index de début de la pagination")
//     * @QueryParam(name="limit", requirements="\d+", default="", description="Index de fin de la pagination")
//     *
//     * @return array
//     */
//    public function getLastPagesAction(Request $request, ParamFetcher $paramFetcher)
//    {
//        $offset = $paramFetcher->get('offset');
//        $limit = $paramFetcher->get('limit');
//
//        $qb = $this->getDoctrine()
//            ->getManager()
//            ->createQueryBuilder();
//
//        $qb->select('p')
//            ->from('WikiWikiBundle:Page', 'p')
//            ->orderBy('p.createdAt', 'DESC');
//
//        if ($offset != "") {
//            $qb->setFirstResult($offset);
//        }
//
//        if ($limit != "") {
//            $qb->setMaxResults($limit);
//        }
//
//        $pages = $qb->getQuery()->getResult();
//
//        return $pages;
//    }
//
//
//
//    /**
//     * @param Request $request
//     *
//     * @Rest\View(serializerGroups={"page"})
//     * @Rest\Get("/pages/search")
//     * @QueryParam(name="q", requirements=".+", default="", description="Query à rechercher")
//     * @QueryParam(name="offset", requirements="\d+", default="0", description="Index de début de la pagination")
//     * @QueryParam(name="limit", requirements="\d+", default="10", description="Index de fin de la pagination")
//     *
//     * @return array
//     */
//    public function getSearchPagesAction(Request $request, ParamFetcher $paramFetcher)
//    {
//        $q = $paramFetcher->get('q');
//        $offset = $paramFetcher->get('offset');
//        $limit = $paramFetcher->get('limit');
//
//        $qb = $this->getDoctrine()
//            ->getManager()
//            ->createQueryBuilder();
//
//        $qb->select('p')
//            ->from('WikiWikiBundle:Page', 'p')
//            ->orderBy('p.createdAt', 'DESC');
//
//        if ($q != "") {
//            $qb->where('p.title LIKE :q')
//            ->setParameter('q', '%'.$q.'%');
//        }
//
//        if ($offset != "0") {
//            $qb->setFirstResult($offset);
//        }
//
//        if ($limit != "10") {
//            $qb->setMaxResults($limit);
//        }
//
//        $pages = $qb->getQuery()->getResult();
//
//        if (empty($pages)) {
//            return $this->pageNotFound();
//        }
//
//        return $pages;
//    }
//
//    /**
//     * @param Request $request
//     *
//     * @Rest\View(serializerGroups={"page"})
//     * @Rest\Get("/pages/{slug}")
//     *
//     * @return object
//     */
//    public function getPageAction(Request $request)
//    {
//        $page = $this
//            ->getDoctrine()
//            ->getManager()
//            ->getRepository('WikiWikiBundle:Page')
//            ->findBy(array('slug' => $request->get('slug')));
//
//        if (empty($page)) {
//            return $this->pageNotFound();
//        }
//
//        return $page;
//    }
//
//    /**
//     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"page"})
//     * @Rest\Delete("/pages/{slug}")
//     */
//    public function removePageAction(Request $request)
//    {
//        $em = $this->getDoctrine()
//            ->getManager();
//        $page = $em->getRepository('WikiWikiBundle:Page')
//            ->findOneBy(array('slug' => $request->get('slug')));
//
//        if (!$page) {
//            return $this->pageNotFound();
//        }
//
//        foreach ($page->getRevisions() as $pageRevision) {
//            $em->remove($pageRevision);
//        }
//
//        $em->remove($page);
//        $em->flush();
//
//        return View::create(['message' => 'Page deleted'], Response::HTTP_NOT_FOUND);
//    }
//
//    private function pageNotFound()
//    {
//        return View::create(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
//    }
}
