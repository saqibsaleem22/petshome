<?php

namespace App\Controller;

use App\Entity\Animals;
use App\Entity\User;
use App\Form\AnimalType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GalleryController extends AbstractController
{
    /**
     * @Route("/galleryload", name="galleryload")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $animals = $em->getRepository(Animals::class)->findAll();
        $arrayCollection = array();

        foreach($animals as $animal) {
            $arrayCollection[] = array(
                'id' => $animal->getId(),
                'name' => $animal->getName(),
                'type' => $animal->getType(),
                'age' => $animal->getAge(),
                'size' => $animal->getSize(),
                'photo' => $animal->getPhoto(),
                'status' => $animal->getStatus()
                // ... Same for each property you want
            );
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($arrayCollection);
        }


    }
}
