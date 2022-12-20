<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\ExplorerFormType;
use App\Service\FileService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Explorer main controller
 * In this file all explorer related
 * routes are defined
 */

class ExplorerController extends AbstractController
{
    private $targetDirectory;
    private SluggerInterface $slugger;
    private Filesystem $fileSystem;

    public function __construct($targetDirectory, SluggerInterface $slugger, FileSystem $fileSystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->fileSystem = $fileSystem;
    }
    /**
     * Returns and renders main page
     * with all user related files.
     */
    #[Route('/explorer', name: 'app_explorer')]
    public function index(): Response
    {
        return $this->render(
            'explorer/index.html.twig',
            [
                'files' => $this->getUser()->getFiles(),
            ],
        );
    }
    
    /**
     * ...
     */
    #[Route('/explorer/upload', name: 'app_explorer_upload')]
    public function upload(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(ExplorerFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // create new record in hloud.file table
            $fileService = new FileService($this->targetDirectory, $managerRegistry, $this->slugger);
            $fileService->uploadFile(
                $form->get('file')->getData(),
                $this->getUser()
            );

            return $this->redirectToRoute('app_explorer');
        }

        return $this->render(
            'explorer/upload.html.twig',
            array('explorerUploadForm' => $form->createView())
        );
    }
    

    #[Route('/explorer/delete/{fileID}', name: 'app_explorer_delete')]
    public function delete(int $fileID, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $file = $entityManager->getRepository(File::class)->find($fileID);
        $bolIsOwner = $file->getFileOwner() == $this->getUser();

        if (!$bolIsOwner) {
            return $this->json([
                'error' => 'Access denied'
            ], 403);
        }
        if ($this->getUser()->getPhoto() == $file->getId()) {
            $this->getUser()->setPhoto(null);
        }
        $this->fileSystem->remove($this->targetDirectory . DIRECTORY_SEPARATOR . $file->getFileName());
        $entityManager->remove($file);
        $entityManager->flush();

        return $this->redirectToRoute('app_explorer');

    }
}

// namespace App\Controller;

// use App\Form\ExplorerFormType;
// use App\Service\FileService;
// use Doctrine\Persistence\ManagerRegistry;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\String\Slugger\SluggerInterface;

// class ExplorerController extends AbstractController
// {
//     private SluggerInterface $slugger;
//     private $targetDirectory;

//     public function __construct(SluggerInterface $slugger, $targetDirectory)
//     {
//         $this->slugger = $slugger;
//         $this->targetDirectory = $targetDirectory;
//     }

//     #[Route('/explorer', name: 'app_explorer')]
//     public function index(Request $request, ManagerRegistry $mr): Response
//     {

//         $files = $this->getUser()->getFiles();

//         return $this->render(
//             'explorer/index.html.twig', [
//                 'files' => $this->getUser()->getFiles()
//             ]
//         );
//     }

//     #[Route('/api/explorer/update', name: 'app_api_explorer_update')]
//     public function update()
//     {
//         return $this->json([
//             "message" => "0"
//         ]);
//     }
//     // debug only
//     #[Route('/explorer/upload', name: 'app_explorer_upload')]
//     public function upload( Request $request, ManagerRegistry $mr): Response
//     {
//         $form = $this->createForm(ExplorerFormType::class);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             // create new record row in hloud.file
//             $fileService = new FileService($this->targetDirectory, $mr, $this->slugger);
//             $fileService->uploadFile($form->get('file')->getData(), $this->getUser());

//             return $this->redirectToRoute('app_explorer');
//         }


//         return $this->render(
//             'explorer/upload.html.twig',
//             [
//                 'explorerUploadForm' => $form->createView()
//             ]
//         );
//     }
// }
