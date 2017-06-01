<?php

namespace Wiki\WikiBundle\Controller;

// Import needed for FOSRest
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
// Symfony
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
// Entity
use Wiki\WikiBundle\Entity\User;
use Wiki\WikiBundle\Form\LoginType;
use Wiki\WikiBundle\Form\SignUpType;


class UserController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{id}")
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
        return $users;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/signup")
     */
    public function SignUpAction(Request $request)
    {
        $userData = $request->request->all();

        $user = new User();
        $user->setEnabled(1);
        $form = $this->createForm(SignUpType::class, $user);
        $form->submit($userData); // Handle Date, Password repeat, status and role


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        }
        return $form;

    }


    /**
     * @Rest\View(statusCode=Response::HTTP_ACCEPTED, )
     * @Rest\Post("/signin")
     */
    public function LoginAction(Request $request)
    {
        $userData = $request->request->all();

        $session = new Session();
        $session->start();

        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->submit($userData);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $session->set('userId', $user->getId());

            // Serialize

            return View::create(['login' => 'OK', 'userId' => $user->getId()],Response::HTTP_ACCEPTED);
        }
        return View::create(['error' => 'Erreur login'],Response::HTTP_NOT_FOUND);
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_ACCEPTED)
     * @Rest\Post('/signout')
     */
    public function LogoutAction(Request $request) {
        $session = $this->get('session');
        $session->clear();
        return View::create(['logout' => 'OK'],Response::HTTP_NOT_FOUND);
    }

}