<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use \App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($userId);
        if($request->isXmlHttpRequest()) {

            $convType = $request->get('type');
            if($convType == "request") {
                $userConvs = $user->getConversations();
                $convArray = [];
                foreach($userConvs as $conv) {
                    $convId = $conv->getId();

                    $convAnimal = $conv->getAnimal();
                    $animalId = $convAnimal->getId();
                    $animalName = $convAnimal->getName();
                    $animalPhoto = $convAnimal->getPhoto();

                    $animalPlacer = $convAnimal->getPlacer();
                    $animalPlacerId = $animalPlacer->getId();
                    $animalPlacerEmail = $animalPlacer->getEmail();


                    $convMsgs = $conv->getMessages();
                    $msgArray = [];
                    $unreadMessages = false;
                    foreach($convMsgs as $msg) {
                        if ($msg->getType() == "response" && $msg->getStatus() == "unread") {
                            $unreadMessages = true;
                        }
                        $msgArray [] = array(
                            'id' => $msg->getId(),
                            'text' => $msg->getText(),
                            'attach' => $msg->getAttachment(),
                            'type' => $msg->getType()
                        );
                    }
                    $convArray [] = array(
                        'convId' => $convId,
                        'animalId' => $animalId,
                        'animalName' => $animalName,
                        'animalPhoto' => $animalPhoto,
                        'animalPlacerId' => $animalPlacerId,
                        'animalPlacerEmail' => $animalPlacerEmail,
                        'convMessages' => $msgArray,
                        'unread' => $unreadMessages
                    );

                }
                return new JsonResponse($convArray, 200);
            } else {
                $animals = $user->getAnimals();
                $allAnimalsConv = [];
                $convArray = [];

                //get user uploaded animals conversations and append them all to one array.
                foreach($animals as $animal) {
                    $animalConvs = $animal->getConversations();
                    foreach($animalConvs as $animalConv) {

                        $allAnimalsConv [] = $animalConv;

                    }
                }
                foreach($allAnimalsConv as $conv) {
                    $convId = $conv->getId();

                    $convAnimal = $conv->getAnimal();
                    $animalId = $convAnimal->getId();
                    $animalName = $convAnimal->getName();
                    $animalPhoto = $convAnimal->getPhoto();

                    $animalRequester = $conv->getRequester();
                    $animalRequesterId= $animalRequester->getId();
                    $animalRequesterEmail = $animalRequester->getEmail();

                    $convMsgs = $conv->getMessages();
                    $msgArray = [];
                    $unreadMessages = false;

                    foreach($convMsgs as $msg) {
                        if($msg->getType() == "request" && $msg->getStatus() == "unread") {
                            $unreadMessages = true;
                        }
                        $msgArray [] = array(
                            'id' => $msg->getId(),
                            'text' => $msg->getText(),
                            'attach' => $msg->getAttachment(),
                            'type' => $msg->getType()
                        );
                    }
                    $convArray [] = array(
                        'convId' => $convId,
                        'animalId' => $animalId,
                        'animalName' => $animalName,
                        'animalPhoto' => $animalPhoto,
                        'animalRequesterId' => $animalRequesterId,
                        'animalRequesterEmail' => $animalRequesterEmail,
                        'convMessages' => $msgArray,
                        'unread' => $unreadMessages
                    );

                }
                return new JsonResponse($convArray, 200);
            }


        }

        return $this->render('index.html.twig');
    }

    /**
     * @Route("/updateConversationMessages", name="updateConversationMessages")
     * @param Request $request
     * @return Response
     */
    public function addNewMessageToConversation(Request $request): Response {
        $em = $this->getDoctrine()->getManager();
        $convId = $request->get('conv-Id');
        $msgText = $request->get('msg-text');
        $msgAttach = $request->files->get('msg-attach');
        $msgType = $request->get('msg-type');

        if ($msgAttach == null) {
            $conversation = $em->getRepository(Conversation::class)->find($convId);
            $message = new Message();
            $message->setText($msgText);
            $message->setAttachment("");
            $message->setType($msgType);
            $message->setConversation($conversation);
            $message->setStatus("unread");
            $conversation->addMessage($message);
            $em->persist($message);
            $em->flush();

        } else {
            $attachName = $msgAttach->getClientOriginalName();
            $conversation = $em->getRepository(Conversation::class)->find($convId);
            $message = new Message();
            $message->setText($msgText);
            $message->setAttachment($attachName);
            $message->setType($msgType);
            $message->setConversation($conversation);
            $message->setStatus("unread");
            $conversation->addMessage($message);
            $em->persist($message);
            $em->flush();

            //reload into database after new name for file
            $id = $message->getId();
            $attachName = time().$id.$attachName;
            $destination = $this->getParameter('kernel.project_dir').'/public/assets';
            $message->setAttachment($attachName);
            $em->flush();
            $msgAttach->move($destination, $attachName);
        }






        return new JsonResponse(true, 200);

    }



    /**
     * @Route("/updateMessageStatus", name="updateMessageStatus")
     * @param Request $request
     * @return Response
     */
    public function updateMessageStatus(Request $request): Response {
        $em = $this->getDoctrine()->getManager();
        $convId = $request->get('conv-id');
        $convType = $request->get('conv-type');
        $conv = $em->getRepository(Conversation::class)->find($convId);
        $convMessages = $conv->getMessages();

        foreach ($convMessages as $msg) {
            if ($msg->getType() !== $convType) {
                $msg->setStatus("read");
            }
        }

        $em->flush();

        return new JsonResponse(true, 200);

    }


}
