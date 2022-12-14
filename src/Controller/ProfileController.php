<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\File;
use App\Form\ProfileUpdateFormType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request, 
        ManagerRegistry $doctrine,
        FileUploader $fileUploader
    ): Response {

        // If user not authentificated
        // redirect to login page
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // // Get current user entity
        $entityManager = $doctrine->getManager();
        $user = $entityManager
            ->getRepository(User::class)
            ->findBy(['username' => $this->getUser()->getUserIdentifier()]);
        $user2 = $this->getUser();

        $form = $this->createForm(ProfileUpdateFormType::class);
        $form->handleRequest($request);

        // if ($form->isSubmitted()) {
        //     $photoFile = $form->get('photo');
        //     $user->setFirstName('Admin');
        //     return $this->render('profile/test.html.twig', [
        //         'data' => $form->getData(),
        //         'photo' => $photoFile,
        //         'user' => $user
        //     ]);
        // }
        if ($form->isSubmitted()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $photoFileId = $fileUploader->upload($photoFile);
                $user1 = $this->getUser();
                $user1->setPhoto($photoFileId);
                $file = $entityManager->getRepository(File::class)->find($photoFileId);
                $file->setFileOwner($user1);
                $entityManager->persist($user1);
                $entityManager->flush();
            }
        }


        return $this->render('profile/index.html.twig', [
            'profileUpdateForm' => $form->createView(),
            'userfiles' => $user2->getFiles()
        ]);


    }
    // public function index(Request $request): Response
    // {
    //     // If user is not authentificated
    //     // redirect to login route
    //     if (!$this->getUser()) {
    //         return $this->redirectToRoute('app_login');
    //     }

    //     $form = $this->createForm(ProfileUpdateFormType::class);
    //     $form->handleRequest($request);

    //     return $this->render('profile/index.html.twig', [
    //         'profileUpdateForm' => $form->createView(),
    //     ]);
    // }
    // public function new(Request $request, SluggerInterface $slugger)
    // {
    //     $product = new Product();
    //     $form = $this->createForm(ProductType::class, $product);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         /** @var UploadedFile $brochureFile */
    //         $brochureFile = $form->get('brochure')->getData();

    //         // this condition is needed because the 'brochure' field is not required
    //         // so the PDF file must be processed only when a file is uploaded
    //         if ($brochureFile) {
    //             $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
    //             // this is needed to safely include the file name as part of the URL
    //             $safeFilename = $slugger->slug($originalFilename);
    //             $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

    //             // Move the file to the directory where brochures are stored
    //             try {
    //                 $brochureFile->move(
    //                     $this->getParameter('brochures_directory'),
    //                     $newFilename
    //                 );
    //             } catch (FileException $e) {
    //                 // ... handle exception if something happens during file upload
    //             }

    //             // updates the 'brochureFilename' property to store the PDF file name
    //             // instead of its contents
    //             $product->setBrochureFilename($newFilename);
    //         }

    //         // ... persist the $product variable or any other work

    //         return $this->redirectToRoute('app_product_list');
    //     }

    //     return $this->renderForm('product/new.html.twig', [
    //         'form' => $form,
    //     ]);
    // }
}
