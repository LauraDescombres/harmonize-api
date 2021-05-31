<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Form\SearchUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\HarmonizeSlugger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
* @Route("/api/v1/users", name="api_v1_users_")
*/
class UserController extends AbstractController
{
    /**
    * @Route("", name="browse", methods={"GET"})
    */
    public function browse(UserRepository $userRepository): Response
    {
        $users = $userRepository->browseUserByRoles();

        return $this->json($users, 200, [], [
            'groups' => ['user_browse'],
        ]);
    }

    /**
    * @Route("/search", name="search_users", methods={"GET"})
    */
    public function search(UserRepository $userRepository, Request $request)
    {
        $users = $userRepository->findAll();

        $form = $this->createForm(SearchUserType::class, null, [
            'csrf_protection' => false,
        ]);

        $json = json_decode($request->getContent(), true);

        $search = $form->submit($json);

        if ($form->isValid()) {
            $users = $userRepository->search($search->get('keywords')->getData());
        }

        return $this->json($users, 200, [], [
            'groups' => ['user_read'],
        ]);
    }

    /**
    * @Route("/{id}", name="read", methods={"GET"}, requirements={"id": "\d+"})
    */
    public function read(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->readUserByRoles($id);

        return $this->json($user, 200, [], [
            'groups' => ['user_read'],
        ]);
    }

    /**
    * @Route("/account/{id}", name="read_account", methods={"GET"}, requirements={"id": "\d+"})
    */
    public function readAccount(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->readUserByRoles($id);

        return $this->json($user, 200, [], [
            'groups' => ['user_account', 'user_password'],
        ]);
    }

    /**
    * @Route("", name="add", methods={"POST"})
    */
    public function add(Request $request, HarmonizeSlugger $slugger, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        //* On appelle le form et on l'associe à $user
        $form = $this->createForm(UserType::class, $user, [
            'csrf_protection' => false,
            ]);

        //* On serialise le contenu de la requête reçu en Post et on le stock dans $sentData sous forme de tableau grâce à l'argument true
        $sentData = json_decode($request->getContent(), true);

        //* On mets les données reçues dans l'objet User via le form
        $form->submit($sentData);

        //* on fait des vérifications
        if ($form->isValid()) {
            $user->setSlug($slugger->slugify($user->getUsername()));

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
          
            //* On envoie les données en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('api_login_check', [
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
            ], 307);
        } else {
            return $this->json((string) $form->getErrors(true, false), 400);
        }
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT"}, requirements={"id": "\d+"})
     */
    public function edit(UserRepository $userRepository, int $id, Request $request, UserPasswordEncoderInterface $encoder, HarmonizeSlugger $slugger): Response
    {
        $user = $userRepository->readUserByRoles($id);
        $password = $user->getPassword();

        //* On appelle le form et on l'associe à $user
        $form = $this->createForm(UserType::class, $user, [
            'csrf_protection' => false,
            ]);

        //* On serialise le contenu de la requête reçu en Post et on le stock dans $sentData sous forme de tableau grâce à l'argument true
        $sentData = json_decode($request->getContent(), true);
        $sentData['password'] = $password;

        //* On mets les données reçues dans l'objet User via le form
        $form->submit($sentData);

        //* on fait des vérifications
        if ($form->isValid()) {

            $user->setSlug($slugger->slugify($user->getUsername()));
            $this->getDoctrine()->getManager()->flush();

            return $this->json($user, 200, [], [
                'groups' => ['user_account', 'user_password'],
            ]);
        } else {
            return $this->json((string) $form->getErrors(true, false), 400);
        }
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function delete(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->readUserByRoles($id);
        
        $id = $user->getId();
        $username = $user->getUsername();
        $user->setStatus(0);
        $user->setUsername($username . ' (Utilisateur Supprimé)');

        $this->getDoctrine()->getManager()->flush();

        return $this->json(null, 204);
    }

    /**
     * @Route ("/upload/{id}", name="upload", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function upload(FileUploader $file, Request $request, UserRepository $userRepository, int $id, ValidatorInterface $validator) {
        
        // On récupére l'utilisateur avec l'id correspondant.
        $user = $userRepository->readUserByRoles($id);
        
        // On récupére les objets files contenue dans la requete.
        $picture = $request->files->get('picture');

        if ($picture !== null) {
            $violations = $validator->validate(
                $picture,
                new File([
                        'mimeTypes' => [
                            'image/*',
                        ],
                    ]),
            );
                
            if ($violations->count() > 0) {
                
                return $this->json("Mauvais format", 400);
            
            } else {
                
                $newImageName = $file->uploadImageProfile($picture);
                $user->setPicture($newImageName);
  
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
        
                return $this->json($user, 201, [], [
                'groups' => ['user_account']
                ]);
            }

            return $this->json("Le fichier n'a pas été uploader", 404);
        }
    }

}
