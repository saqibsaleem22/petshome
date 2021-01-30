<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Animals;
use App\Form\AnimalType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{

    /**
     * @Route("/upload", name="upload")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $animal->setUser($user);
            $em->persist($animal);
            $em->flush();
            $this->addFlash('success', 'Your animal have been successfully uploaded!');
            return $this->redirectToRoute('index');
        }
        return $this->render('upload.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/status", name="status")
     * @param Request $request
     * @return Response
     */
    public function setStatus(Request $request): Response
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $animalId = $request->get('animal-id');
            $animal = $em->getRepository(Animals::class)->find($animalId);
            $animal->setStatus('adopted');
            $em->flush();
            return new JsonResponse(true);
        }

    }
}
