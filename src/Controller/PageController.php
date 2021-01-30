<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Animals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homePage(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $adoptions = $em->getRepository(Animals::class)->findBy(['status'=>'adopted'], ['id'=>'DESC'], 3);


        return $this->render('index.html.twig', [
            'adoptions' => $adoptions
        ]);
    }

    /**
     * @Route("/gallery", name="gallery")
     * @param Request $request
     * @return Response
     */
    public function galleryPage(Request $request): Response
    {
        return $this->render('gallery.html.twig');
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function uploadPage(): Response
    {
        return $this->render('upload.html.twig', [
            'success' => false,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }


}
