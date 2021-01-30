<?php

namespace App\Controller;

use App\Entity\Animals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends AbstractController
{
    /**
     * @Route("/details", name="details")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $animal = $em->getRepository(Animals::class)->find($id);

        return $this->render('detail.html.twig', [
            'animal' => $animal
        ]);
    }
}
