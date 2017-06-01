<?php

namespace Wiki\WikiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *    description="Récupère toutes les notes",
     *    output= { "class"=Rating::class, "collection"=true, "groups"={"rating"} }
     * )
     *
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
     * @ApiDoc(
     *    description="Ajoute une note",
     *    input={"class"=RatingType::class, "name"=""}
     * )
     *
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
     * @ApiDoc(
     *    description="Récupère la note moyenne d'une page",
     *    output= { "class"=Rating::class, "collection"=true, "groups"={"rating"} }
     * )
     *
     * @Rest\Get("/average/page/{id}")
     */
    public function getAveragePageAction(Request $request)
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
            ->findBy(array('page' => $page));

        if (empty($rates))
        {
            return $this->noRates('La page');
        }

        $avg = 0;
        $nb = 0;
        foreach($rates as $rate)
        {
            $avg = $rate->getRating();
            $nb++;
        }
        $avg = $avg/$nb;

        return View::create(['average' => $avg], Response::HTTP_CREATED);
    }

     /**
     * @ApiDoc(
     *    description="Récupère la note moyenne d'un utilisateur",
     *    output= { "class"=Rating::class, "collection"=true, "groups"={"rating"} }
     * )
     * @Rest\Get("/average/user/{id}")
     */
    public function getAverageUserAction(Request $request)
    {
        $user = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User`')
            ->findBy(array('' => $request->get('userToken')));

        $rates = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:Rating')
            ->findBy(array('user' => $user));

        if (empty($rates))
        {
            return $this->noRates('L\'utilisateur');
        }

        $avg = 0;
        $nb = 0;
        foreach($rates as $rate)
        {
            $avg = $rate->getRating();
            $nb++;
        }
        $avg = $avg/$nb;

        return View::create(['average' => $avg], Response::HTTP_CREATED);
    }

    public function noRates($type)
    {
        return View::create(['error' => $type.' ne possède aucune note'],Response::HTTP_NOT_FOUND);
    }
}
