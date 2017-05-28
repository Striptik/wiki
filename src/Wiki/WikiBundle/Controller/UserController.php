<?php

namespace Wiki\WikiBundle\Controller;

// Import needed for FOSRest
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;
// Entity
use Wiki\WikiBundle\Entity\User;



class UserController extends FOSRestController
{
    public function getUserAction()
    {
        $repository = $this->getDoctrine()->getRepository('WikiWikiBundle:User');

        // Define Offset and Page ?
        // Actually all the users
        $users = $repository->findAll();
        $view = $this->view($users);
        return $this->handleView($view);
    }

    // Validation a faire ? Unique pseudo ?..

    public function postUserAction(Request $request)
    {
        //Retrieve all the request Data
        $userData = $request->request->all();

        // Create the new User
        // TO BE REPLACE WHITH A FORM TYPE
        // details :
        // http://npmasters.com/2012/11/25/Symfony2-Rest-FOSRestBundle.html
        // https://github.com/FriendsOfSymfony/FOSRestBundle/issues/738
        // !!! CREATE FORM and use form->isValid()

        $date =  $date = new DateTime();
        $user = new User();
        $user->setEmail('kev1in1@nestorparis.com')
            ->setPassword('qweqwe')
            ->setPseudo('Kooks12')
            ->setRole('Admin')
            ->setStatus('Online')
            ->setCreatedAt($date);

        // If it's valid, store in the DB
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();


        // If it's not valid, send Errors

        // otherwise send Response

        $view = $this->view(array('userId' => $user->getId()));
        return $this->handleView($view);



    }
}