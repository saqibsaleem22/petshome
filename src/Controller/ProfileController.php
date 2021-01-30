<?php

namespace App\Controller;


use App\Entity\Animals;
use App\Entity\Conversation;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($userId);

        if ($request->isXmlHttpRequest()) {

            $animalId = $request->request->get('animalId');
            $animal = $em->getRepository(Animals::class)->find($animalId);
            $placer = $animal->getPlacer();
            $result = false;
            if($user != $placer) {
                $conversations = $animal->getConversations();
                $exist = $this->find_user_in_conversations($userId, $conversations);

                if ($exist) {
                    $result = $exist->getId();
                } else {
                    $conv = new Conversation();
                    $conv->setAnimal($animal);
                    $conv->setRequester($user);
                    $animal->addConversation($conv);
                    $user->addConversation($conv);
                    $em->persist($conv);
                    $em->flush();
                    $result = $conv->getId();
                }
            }


            return new JsonResponse($result, 200);

        } else {
            $uploadedAnimals = $user->getAnimals();
            $total = count($uploadedAnimals);
            $totalAdopted = 0;
            foreach ($uploadedAnimals as $animal) {
                if($animal->getStatus() == "adopted") {
                    $totalAdopted = $totalAdopted + 1;
                }
            }
            $totalAvailable = $total - $totalAdopted;

            return $this->render('profile.html.twig', [
                'myUploads' => $uploadedAnimals,
                'total' => count($uploadedAnimals),
                'adopted' => $totalAdopted,
                'available' => $totalAvailable
            ]);
        }

    }


    public function find_user_in_conversations($id, $conversations)
    {
        $found = false;
        foreach($conversations as $conv) {
            if ($conv->getRequester()->getId() === $id) {
                $found = $conv;
                break;
            }
        }
        return $found;
    }
}
