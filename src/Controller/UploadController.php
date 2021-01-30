<?php

namespace App\Controller;

use App\Entity\Animals;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/uploadpet", name="uploadpet")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($userId);

        $photo = $request->files->get('pic');
        $name = $request->request->get('name');
        $type = $request->request->get('type');
        $age = $request->request->get('age');
        $size = $request->request->get('size');
        $description = $request->request->get('description');
        $status = "available";
        $photoName = $photo->getClientOriginalName();

        $animal = new Animals();
        $animal->setName($name);
        $animal->setType($type);
        $animal->setAge($age);
        $animal->setSize($size);
        $animal->setDescription($description);
        $animal->setPhoto($photoName);
        $animal->setStatus($status);
        $animal->setPlacer($user);


        $em->persist($animal);
        $em->flush();

        $id = $animal->getId();
        $photoName = time().$id.'.png';
        $destination = $this->getParameter('kernel.project_dir').'/public/assets';
        $animal->setPhoto($photoName);
        $em->flush();
        $photo->move($destination, $photoName);

        return $this->render('upload.html.twig', [
            'success' => 'true'
        ]);
    }
}
