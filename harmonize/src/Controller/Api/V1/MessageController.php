<?php

namespace App\Controller\Api\V1;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/messages", name="api_v1_messages_")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/browse/{id}", name="browse", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function browse(MessageRepository $messageRepository, int $id): Response
    {
        $Message = $messageRepository->findUserMessages($id);

        return $this->json($Message, 200, [], [
            'groups' => ['message_browse'],
        ]);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function read(Message $message): Response
    {
        return $this->json($message, 200, [], [
            'groups' => ['message_read'],
        ]);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, ['csrf_protection' => false]);

        $sentData = json_decode($request->getContent(), true);
        $form->submit($sentData);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->json($message, 201, [], [
                'groups' => ['message_read'],
            ]);
        }
        return $this->json($form->getErrors(true, false)->__toString(), 400);
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT"}, requirements={"id": "\d+"})
     */
    public function edit(Message $message, Request $request): Response
    {   
        $sender = $message->getSender()->getId();
        $recipient = $message->getRecipient()->getId();
        $title = $message->getTitle();
        $content = $message->getMessage();

        $form = $this->createForm(MessageType::class, $message, ['csrf_protection' => false]);

        $sentData = json_decode($request->getContent(), true);
        $sentData['title'] = $title;
        $sentData['message'] = $content;
        $sentData['sender'] = $sender;
        $sentData['recipient'] = $recipient;

        $form->submit($sentData);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->json($message, 200, [], [
                'groups' => ['message_edit'],
            ]);
        }
        return $this->json($form->getErrors(true, false)->__toString(), 400);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id": "\d+"})
     */
    public function delete(Message $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->json(null, 204);
    }
}
