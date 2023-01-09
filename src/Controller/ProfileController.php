<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Form\ProfileUpdateFormType;
use App\Service\FileService;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    private SluggerInterface $slugger;
    private $targetDirectory;

    public function __construct(SluggerInterface $slugger, $targetDirectory)
    {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
    }
    #[Route('/profile', name: 'app_profile')]
    public function index( Request $request, ManagerRegistry $mr ): Response
    {

        // Create form
        $form = $this->createForm(ProfileUpdateFormType::class);
        $form->handleRequest($request);

        /**
         * Handle user profiile update
         */
        if ( $form->isSubmitted() ) {
            // update record in hloud.user table
            $userService = new UserService($mr);
            $userService->updateBaseData($this->getUser(), $form->getData());

            // check if form has photo attached
            if ($form->get('photo')->getData()) {
                // create new record in hloud.file table
                $fileService = new FileService($this->targetDirectory, $mr, $this->slugger);
                $photoID = $fileService->uploadFile($form->get('photo')->getData(), $this->getUser());
                $userService->updateProfilePhoto($this->getUser(), $photoID);
            }
        }
        // get photo url
        // better set default value in database
        $entityManager = $mr->getManager();
        $bolHasPhoto = (bool)$entityManager->getRepository(User::class)->find($this->getUser())->getPhoto();
        if ( !$bolHasPhoto ) {
            $imageURL = '/uploads/default_photo.png';
        } else {
            $photo = $entityManager->getRepository(File::class)->find($this->getUser()->getPhoto());
            $imageURL = '/uploads/' . $photo->getFileName();
        }
        

        // Render view
        return $this->render(
            'profile/index.html.twig',
            [
                'profileUpdateForm' => $form->createView(),
                'imageURL' => $imageURL
            ]
        );
    }
}
