<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SecurityController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response

     {
    //     $user = $this->getUser();
    //     if ($user !== null && in_array('ROLE_ADMIN', $user->getRoles())) {
    //         return $this->redirectToRoute('admin_index');
    //     }
    //     //  return $this->redirectToRoute('user_user');
    //     if ($user !== null && in_array('ROLE_USER', $user->getRoles())) {
    //         return $this->redirectToRoute('user_user');
    //     }
         
         
         
         

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    
               }

    
    #[Route(path: '/logout', name: 'app_logout')]
      public function logout() 
    {
        return $this->redirectToRoute("app_login");
    }
   

    #[Route(path: '/forgot', name: 'Forgot')]
    public function forgotPassword(Request $request, UserRepository $userRepository,Swift_Mailer $mailer, TokenGeneratorInterface  $tokenGenerator)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();//reccuperer la valeur de l email  


            $user = $userRepository->findOneBy(['email'=>$donnees]); // pour tester la presance de l email dans la base 
            if(!$user) {
                $this->addFlash('danger','cette adresse n\'existe pas');
                return $this->redirectToRoute("forgot");

            }
            $token = $tokenGenerator->generateToken(); // generer un token parce qu il faut que l utlisateur soit presant dans la bd 

            try{
                $user->setResetToken($token); // enregistrer le token generer dans la base (colone  token ) 
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();




            }catch(\Exception $exception) {
                $this->addFlash('warning','une erreur est survenue :'.$exception->getMessage());
                return $this->redirectToRoute("app_login");


            }
             // generer un url pour le lien envoyer par mail 
    
            $url = $this->generateUrl('app_reset_password',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);
           
             //BUNDLE MAILER
             $message = (new Swift_Message('Mot de password oublié'))
             ->setFrom('skanderbey2040@gmail.com') // le source de l email 
             ->setTo($user->getEmail()) //  destination email
             ->setBody("<p> Bonjour</p> unde demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant :".$url,
             "text/html");  // descreption de l email 
             $mailer->send($message);
             $this->addFlash('message','E-mail  de réinitialisation du mp envoyé :');

    }  
    return $this->render("security/forgotPassword.html.twig",['form'=>$form->createView()]);

}
     


}

//  token :  c est un jeton generer lors de l authentification 