<?php

namespace Wiki\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wiki\WikiBundle\Entity\User;
use Wiki\WikiBundle\Form\Type\UserType;

class UserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User')
            ->findAll();

        return $users;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{user_id}")
     */
    public function getUserAction(Request $request)
    {
        $user = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('WikiWikiBundle:User')
            ->find($request->get("user_id"));

        if (empty($user)) {
            return View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user, ['validation_groups'=>['Default', 'New']]);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($user);
            $em->flush();
        } else {
            return $user;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{user_id}")
     */
    public function removeUserAction(Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();

        $user = $em->getRepository('WikiWikiBundle:User')
            ->find($request->get('user_id'));

        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Put("/users/{user_id}")
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("users/{user_id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    public function updateUser(Request $request, $clearMissing)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();

        $user = $em->getRepository('WikiWikiBundle:User')
            ->find($request->get('user_id'));

        if (empty($user)) {
            return View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if ($clearMissing) {
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = [];
        }

        $form = $this->createForm(UserType::class, $user, $options);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            // Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);
            }

            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->merge($user);
            $em->flush();

            return $user;
        } else {
            return $form;
        }
    }

}
