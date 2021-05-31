<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\HarmonizeSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* @Route("/users", name="users_")
*/
class UsersController extends AbstractController
{
    /**
     * @Route("/browse", name="browse")
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/browse.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id": "\d+"})
     */
    public function read(User $user): Response
    {
        return $this->render('admin/users/read.html.twig', [
            'user' => $user,
        ]);
    }

    /**
    * @Route("/add", name="add")
    *
    * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
    */
    public function add(Request $request, HarmonizeSlugger $slugger, UserPasswordEncoderInterface $encoder, FileUploader $file): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setSlug($slugger->slugify($user->getUsername()));
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $newFilename = $file->uploadImageProfile($pictureFile);
                $user->setPicture($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('succes', 'Le user ' . $user->getUsername() . ' a bien été modifié');

            return $this->redirectToRoute('users_browse');
        }
        
        return $this->render('admin/users/add.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function edit(User $user, Request $request, HarmonizeSlugger $slugger, FileUploader $file): Response
    {
        $originalPicture = $user->getPicture();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setSlug($slugger->slugify($user->getUsername()));

            $pictureFile = $form->get('picture')->getData();

            if($pictureFile == null) {
                $user->setPicture($originalPicture);
            } else {
                $newFilename = $file->uploadImageProfile($pictureFile);
                $user->setPicture($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('succes', 'L\'utilisateur ' . $user->getUsername() . ' a bien été modifié');

            return $this->redirectToRoute('users_browse');
        }
        
        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function delete(User $user): Response
    {
        $username = $user->getUsername();
        $user->setStatus(0);
        $user->setUsername($username . ' (Utilisateur Supprimé)');

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('users_browse');
    }
}