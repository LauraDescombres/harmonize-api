<?php

namespace App\Controller\Api\V1;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/comments", name="api_v1_comments_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/{id}", name="browse", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function browse(CommentRepository $commentRepository, int $id): Response
    {
        $comment = $commentRepository->findByProjectId($id);

        return $this->json($comment, 200, [], [
            'groups' => ['project_read'],
        ]);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, ['csrf_protection' => false]);

        $sentData = json_decode($request->getContent(), true);
        
        $form->submit($sentData);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->json($comment, 201, [
                'id_comment' => $comment->getId(),
            ], 
            [
                'groups' => ['project_read'],
            ]);
        }
        return $this->json($form->getErrors(true, false)->__toString(), 400);
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"}, requirements={"id": "\d+"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment, ['csrf_protection' => false]);

        $sentData = json_decode($request->getContent(), true);
        $form->submit($sentData);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->json($comment, 200, [], [
                'groups' => ['project_read'],
            ]);
        }
        return $this->json($form->getErrors(true, false)->__toString(), 400);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id": "\d+"})
     */
    public function delete(Comment $comment): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->json(null, 204);
    }

    /**
     * @Route ("/upload/{id}", name="upload", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function upload(FileUploader $file, Request $request, CommentRepository $commentRepository, int $id, ValidatorInterface $validator) {
        

        $comment = $commentRepository->find($id);
        $audio = $request->files->get('audio_url');
        
        if($audio !== null) {
            
            $violations = $validator->validate(
                $audio,
                    new File([
                        'mimeTypes' => [
                            'audio/*',
                        ],
                    ]),     
            ); 
                
            if($violations->count() > 0) {
                return $this->json("Mauvais format", 400);
            } else {

                $newAudioName = $file->uploadAudioComment($audio);
                $comment->setAudioUrl($newAudioName);
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->json($comment, 201, [], [
                    'groups' => ['project_read']
                ]);
            }
        }
       
        return $this->json("Le fichier n'a pas été uploader", 404);
        
    }
}