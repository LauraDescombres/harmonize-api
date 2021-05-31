<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\Admin\CommentType;
use App\Repository\CommentRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/comments", name="comments_")
     */
class CommentController extends AbstractController
{
    /**
     * @Route("/select", name="select")
     */
    public function select(Request $request): Response
    {
        $defaultData = ['message' => 'Inscrivez un Id'];
        $form = $this->createFormBuilder($defaultData)
            ->add('user', null, [
                'required' => false,
                'label' => 'Utilisateur'
            ])
            ->add('project', null, [
                'required' => false,
                'label' => 'Projet'
            ])
            ->getForm();

        $form->handleRequest($request);
        
        if($form->isSubmitted()) {
            $data = $form->getData();

            if($data['project'] !== null) {
                return $this->redirectToRoute('comments_browse_by_project', ['name' => $data['project']]);
            }

            if($data['user'] !== null) {
                return $this->redirectToRoute('comments_browse_by_user', ['username' => $data['user']]);
            }
        }

        return $this->render('admin/comment/select.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/browse/project/{name}", name="browse_by_project", requirements={"id" : "\D+"})
     */
    public function browseByProject(CommentRepository $commentRepository, string $name): Response
    {
        $comments = $commentRepository->findCommentByProjectName($name);

        return $this->render('admin/comment/browseByProject.html.twig', [
            'comments' => $comments,
            'name' => $name,
        ]);
    }

    /**
     * @Route("/browse/user/{username}", name="browse_by_user", requirements={"id" : "\D+"})
     */
    public function browseByUser(CommentRepository $commentRepository, string $username): Response
    {   
        $comments = $commentRepository->findCommentByUsername($username);

        return $this->render('admin/comment/browseByUser.html.twig', [
            'comments' => $comments,
            'username' => $username,
        ]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id" : "\d+"})
     */
    public function read(Comment $comment, int $id): Response
    {
        return $this->render('admin/comment/read.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/add", name="add")
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function add(Request $request, FileUploader $file): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $audioFile = $form->get('audio_url')->getData();

            if($audioFile) {
                $newFilename = $file->uploadAudioComment($audioFile);
                $comment->setAudioUrl($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('succes', 'Le commentaire a bien été ajouté');

            return $this->redirectToRoute('comments_browse_by_user', ['username' => $comment->getUser()->getUsername()]);
        }

        return $this->render('admin/comment/add.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function edit(comment $comment, Request $request, FileUploader $file): Response
    {
        $originalAudio = $comment->getAudioUrl();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $audioFile = $form->get('audio_url')->getData();

            if($audioFile == null) {
                $comment->setAudioUrl($originalAudio);
            } else {
                $newFilename = $file->uploadAudioComment($audioFile);
                $comment->setAudioUrl($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('succes', 'Le commentaire a bien été modifié');

            return $this->redirectToRoute('comments_browse_by_user', ['username' => $comment->getUser()->getUsername()]);
        }

        return $this->render('admin/comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function delete(Comment $comment): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $this->addFlash('succes', 'Le commentaire a bien été supprimé');

        return $this->redirectToRoute('comments_select');
    }
}
