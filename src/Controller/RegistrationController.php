<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Security\LoginFormAuthenticator;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManager;
use phpDocumentor\Reflection\Types\This;
use phpDocumentor\Reflection\Types\True_;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Constraints\Json;

class RegistrationController extends AbstractController
{


    /**
     * @Route("/registration", name="registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer): Response
    {

        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $error = false;
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $code = $request->request->get('code');
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($code === "true") {
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($encoder->encodePassword($user, $password));
                $avatar = "avatar".rand(0, 4).".png";
                $user->setAvatar($avatar);
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse(['registered' => true]);
            } else {
                $code = false;
                if ($user) {
                    $error = true;
                } else {
                    $error = false;
                    $code = $this->sendEmailVerification($email, $mailer);

                }

                if ($error) {
                    return new JsonResponse(['exist-error' => true]);
                } else {
                    return new JsonResponse(['code' => $code]);
                }
            }



        } else {
            return $this->render('index.html.twig');
        }

    }

    /**
     * @Route("/loginCustom", name="loginCustom")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $formAuthenticator
     * @return Response
     */
    public function login(Request $request, UserPasswordEncoderInterface $encoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator): Response
    {

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $result = false;
            $email = $request->get('login-email');
            $password = $request->get('login-password');
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if($user) {
                $result = $encoder->isPasswordValid($user, $password);
                if ($result) {
                    $token = new UsernamePasswordToken($user, $user->getPassword(), 'dev', []);

                    $this->get('session')->set('api_token', $token);

                    $guardHandler->authenticateUserAndHandleSuccess($user, $request, $formAuthenticator, 'dev');

                }
            }

            return new JsonResponse($result);

        } else {
            $this->render('index.html.twig');
        }



    }

    /**
     * @Route("/recover", name="recover")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function recoverPassword(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $email = $request->get('forgot-email');
            $result = false;
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if($user) {
                $password = $this->generate_password();
                $user->setPassword($encoder->encodePassword($user, $password));
                $em->persist($user);
                $em->flush();
                $result = $this->sendForgotEmail($email, $mailer, $password);
            }
            return new JsonResponse($result);
        }
        return $this->render('index.html.twig');

    }

    /**
     * @Route("/updatePassword", name="updatePassword")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword(Request $request,  UserPasswordEncoderInterface $encoder) {
        if ($request->isXmlHttpRequest()) {
            $userId = $this->getUser()->getId();
            $em = $this->getDoctrine()->getManager();
            $oldPassword = $request->get('old-password');
            $newPassword = $request->get('new-password');
            $user = $em->getRepository(User::class)->find($userId);
            $result = $encoder->isPasswordValid($user, $oldPassword);
            if ($result) {
                $user->setPassword($encoder->encodePassword($user, $newPassword));
                $em->flush();
            }

            return new JsonResponse($result);
        }
        return $this->render('index.html.twig');

    }

    /**
     * @Route("/contactUs", name="contactUs")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function contactUs(Request $request, \Swift_Mailer $mailer) {
        if ($request->isXmlHttpRequest()) {
            $name = $request->get('name');
            $email = $request->get('email');
            $phone = $request->get('phone');
            $message = $request->get('message');
            $result = $this->sendContactUsEmail($name, $email, $phone, $message, $mailer);

            return new JsonResponse($result);
        }
        return $this->render('index.html.twig');

    }


    private function sendContactUsEmail($name, $email, $phone, $message, \Swift_Mailer $mailer): int
    {
        $message = (new \Swift_Message('PetsHome Contact Us Message'))
            ->setFrom('notifier.metriplica@gmail.com')
            ->setTo('saqibsaleem22@gmail.com')
            ->setBody('<h3>Email:</h3><p>'.$email.'</p><h4>Phone:</h4><p>'.$phone.'</p><h4>Message: </h4><p>'.$message.'</p>', 'text/html');
        return $mailer->send($message);
    }


    private function sendForgotEmail($email, \Swift_Mailer $mailer, $password) {
        $message = (new \Swift_Message('PetsHome password recovery'))
            ->setFrom('notifier.metriplica@gmail.com')
            ->setTo($email)
            ->setBody('<h3>Password</h3><p style="font-size: medium">Your temporary password is <span style="color: blue; font-weight: bold ">'.$password. '</span> Please update it as soon as possible. Thanks</p>', 'text/html');
        $result = $mailer->send($message);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    private function sendEmailVerification($email, \Swift_Mailer $mailer)
    {
        $code = md5($email.rand(0, 1000));
        $message = (new \Swift_Message('PetsHome verification'))
            ->setFrom('notifier.metriplica@gmail.com')
            ->setTo($email)
            ->setBody('<h3>Verification code</h3><p style="font-size: medium">Your verification code is <span style="color: blue; font-weight: bold ">'.$code.'</span></p>', 'text/html');
        $result = $mailer->send($message);
        if ($result) {
            return $code;
        } else {
            return false;
        }
    }

    private function generate_password($length = 10){
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
            '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i=0; $i < $length; $i++)
            try {
                $str .= $chars[random_int(0, $max)];
            } catch (\Exception $e) {
            }

        return $str;
    }



}
