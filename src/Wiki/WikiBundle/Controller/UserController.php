<?php

namespace Wiki\WikiBundle\Controller;

// Import needed for FOSRest
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
// Symfony
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Datetime
use \DateTime;
// Entity
use Symfony\Component\HttpFoundation\Session\Session;
use Wiki\WikiBundle\Entity\User;
use Wiki\WikiBundle\Form\LoginType;
use Wiki\WikiBundle\Form\SignUpType;


class UserController extends Controller
{
    /**
     *
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users")
     *
     * @return array
     */
    public function getUsersAction()
    {
        $users = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User')
            ->findAll();

        // Define Offset and Page ?
        // Actually all the users

        return $users;
    }


    /**
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{id}")
     *
     * @return object
     */
    public function getUserAction(Request $request)
    {
        $user = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User')
            ->findBy(array('id' => $request->get('id')));

        if(empty($user))
        {
            return View::create([
                'message'   => 'User not foud',
                'error'     => 'No user with this id',
                'id'        => $request->get('id')
                ], Response::HTTP_NOT_FOUND
                );
        }

        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/signup")
     */
    public function SignUpAction(Request $request)
    {
        $userData = $request->request->all();

        //Check mdp identinque

        $user = new User();
        $user->setEnabled(1);
        $form = $this->createForm(SignUpType::class, $user);
        $form->submit($userData); // Handle Date, Password repeat, status and role

        $check_email = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User')
            ->findBy(array('email' => $request->get('email')));

        $check_username = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User')
            ->findBy(array('username' => $request->get('username')));

        if($check_email) {
            return new JsonResponse(['error' => 'Cet email est déjà utilisé'], Response::HTTP_CONFLICT);
        }

        if($check_username) {
            return new JsonResponse(['error' => 'Ce username est déjà utilisé'], Response::HTTP_CONFLICT);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        }
        return $form;

    }
}