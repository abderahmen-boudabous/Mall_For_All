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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\SendMailService;



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
    public function forgotPassword(Request $request, UserRepository $userRepository,Swift_Mailer $mailer, TokenGeneratorInterface  $tokenGenerator
    ,EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();//reccuperer la valeur de l email  


            $user = $userRepository->findOneBy(['email'=>$donnees]); // pour tester la presance de l email dans la base
            if($user){
                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
            
                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                
                // // On crée les données du mail
                $context = compact('url', 'user');
                 
            
                // Envoi du mail
                $message = (new \Swift_Message('Réinitialisation de mot de passe'))
                    ->setFrom('skanderbey2040@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/password_reset.html.twig',
                            ['context' => $context, 'user' => $user]
                        ),
                        'text/html'
                    );
            
                // Envoyer le message
                $mailer->send($message);
                // dump($context);
            
                 $this->addFlash('success', 'Email envoyé avec succès');
                 return $this->redirectToRoute('app_login');
            }
    }  
    return $this->render("security/forgotPassword.html.twig",['form'=>$form->createView()]);

}




#[Route('/oubli-pass/{token}', name:'reset_pass')]
public function resetPass(
    string $token,
    Request $request,
    UsersRepository $usersRepository,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
): Response
{
    // On vérifie si on a ce token dans la base
    $user = $usersRepository->findOneByResetToken($token);
    
    if($user){
        $form = $this->createForm(ResetPasswordFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // On efface le token
            $user->setResetToken('');
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe changé avec succès');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'passForm' => $form->createView()
        ]);
    }
    $this->addFlash('danger', 'Jeton invalide');
    return $this->redirectToRoute('app_login');
}
     


}

//  token :  c est un jeton generer lors de l authentification 