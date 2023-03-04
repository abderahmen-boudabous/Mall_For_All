<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
Use App\Form\RegistrationFormType;
use App\Form\EdituserType;
use App\Form\EditProfileType;
use App\Form\AjouteruserType;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/admin', name: 'admin_')]

class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        //   $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('base1.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    

    #[Route('/utlisateurs  ', name: 'utlisateurs')]
    public function afficherutlisateurs(SessionInterface $session): Response
    {
        $r=$this->getDoctrine()->getRepository(User::class);
        $users=$r->findAll();

        $numberOfUsers = $r->count([]);
        $session->set('numberOfUsers', $numberOfUsers);

        
        return $this->render('admin/users.html.twig', [
            'user' => $users,
            'numberOfUsers' => $numberOfUsers
            
        ]);           
    }

    #[Route('/admin/profile', name: 'profile')]
    
    public function profile(): Response
    {

        $user = $this->getUser();
        return $this->render('admin/profile.html.twig', [
            'user' => $user,
        
            
        ]);
    }

     #[Route('/utlisateurs/modifier/{id}  ', name: 'modifier_utlisateurs')]
       public function editUser(User $user, Request $request, TranslatorInterface $translator){
        $form = $this->createForm(EdituserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = $translator->trans('User modified successfully');

            $this->addFlash('message', $message);
            return $this->redirectToRoute('admin_utlisateurs');
        }

        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView()
        ]);
    }  
    #[Route('/utlisaturs/supprimer/{id}', name:'supprimer_utlisateurs')]
    public function suppClassroom(User $user,$id,UserRepository $r,ManagerRegistry $doctrine): Response
    {
        
        $user= $r->find($id);
         
        $em = $doctrine->getManager();
        $em->remove($user);

        $em->flush();

        return $this->redirectToRoute('admin_utlisateurs',);
    }

     #[Route('/utlisateurs/ajouter', name:'ajouter_utlisateurs')]
public function addClassroom(ManagerRegistry  $doctrine,Request $request):Response
{
    $user=new User();
    $form=$this->createForm(AjouteruserType::class,$user);
    $form->handleRequest($request);
    if($form->issubmitted()){
        $role = $form->get('roles')->getData();
        if ($role === 'vendeur') {
            $user->setRoles(['ROLE_VENDEUR']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }
        $em=$doctrine->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('admin_utlisateurs');}
        return $this->renderForm("admin/adduser.html.twig",
        array("f"=>$form));
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
            $entityManager->flush();  // so the PDF file must be processed only when a file is uploaded
           

          

            $this->addFlash('message', ' Profil mis a jour');
            return $this->redirectToRoute('admin_profile');
        }
        
        return $this->render('admin/editprofile.html.twig', [
            'Form' => $form->createView(),
            'user' => $user
        ]);
            
       
    }






































//-----------------------------------------CRUD pour sprint mobile (utilistation de JSON )------------------------------//

    #[Route('/Allusers', name: 'Allusers')]
    public function Getusers (UserRepository $repo, SerializerInterface $serializer, NormalizerInterface $normalizer)
    {
        $user = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets categories en  tableau associatif simple.
        //$categoriesNormalises = $normalizer->normalize($suppliers, 'json', ['groups' => "suppliers"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        //$json = json_encode($categoriesNormalises);

        $json = $serializer->serialize($user, 'json', ['groups' => "user"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

    #[Route('/adduser', name: 'Adduser')]
    public function Adduser(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $user = new user();
       
        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('prenom'));
        $user->setEmail($req->get('email'));
        // $user->setRoles($req->get(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN']));
        $user->setPassword($req->get('password'));
        $user->setCodepostale($req->get('codepostale'));
        $user->setNumtel($req->get('numtel'));
        $user->setVille($req->get('ville'));
        $user->setNomBoutique($req->get('nom_boutique'));
        $em->persist($user);
        $em->flush();

        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'user']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('Updateuser/{id}', name: 'Updateuser')]
    public function Updateuser(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(UserRepository::class)->find($id);
        $user->setNom($req->get('nom'));
        $user->setPrenom($req->get('prenom'));
        $user->setEmail($req->get('email'));
        // $user->setRoles($req->get(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN']));
        $user->setPassword($req->get('password'));
        $user->setCodepostale($req->get('codepostale'));
        $user->setNumtel($req->get('numtel'));
        $user->setVille($req->get('ville'));
        $user->setNomBoutique($req->get('nom_boutique'));
        $em->flush();
        $jsonContent = $Normalizer->normalize($user, 'json', ['groups' => 'user']);
        return new Response("user updated successfully " . json_encode($jsonContent));
    }

    #[Route("deletecategorie/{id}", name: "deletecategorie")]
    public function deletecategorie(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(CategorieF::class)->find($id);
        $em->remove($category);
        $em->flush();
        $jsonContent = $Normalizer->normalize($category, 'json', ['groups' => 'categories']);
        return new Response("category deleted successfully " . json_encode($jsonContent));
    }
}