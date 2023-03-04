<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
Use App\Form\EditProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/user', name: 'user_')]

class UserController extends AbstractController


{
    #[Route('/user', name: 'user')]
    
    public function index(): Response
    {

        return $this->render('base.html.twig', [
            
        ]);
    }

    #[Route('/user/profile', name: 'profile')]
    
    public function profile(): Response
    {

        $user = $this->getUser();
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        
            
        ]);
    }
    #[Route('/profile/modifier/{id}', name: 'modifier_profile')]
    
    public function editprofile(User $user, Request $request, TranslatorInterface $translator,SluggerInterface $slugger)
    {
        
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);
       

        if($form->isSubmitted() && $form->isValid()){



            $photo = $form->get('photo')->getData();


            // pour creer le nom de fichier en cas de redandance par example et pour que l image soit unique a sa  propriaitaire 
                        // this condition is needed because the 'brochure' field is not required
                       if ($photo) {
                            $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                            $safeFilename = $slugger->slug($originalFilename);
                            $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
            
                            // Move the file to the directory where brochures are stored
                            try {
                                $photo->move(
                                    $this->getParameter('user_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                // ... handle exception if something happens during file upload
                            }
            
                            // updates the 'brochureFilename' property to store the PDF file name
                            // instead of its contents
                            $user->setImage($newFilename);
                        }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

          

            $this->addFlash('message', ' Profil mis a jour');
            return $this->redirectToRoute('user_profile');
        }
        
        return $this->render('user/editprofile.html.twig', [
            'Form' => $form->createView()
        ]);
            
       
    }
}
