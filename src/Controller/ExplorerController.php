<?php

namespace App\Controller;

use App\Form\ExplorerFormType;
use App\Service\FileService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ExplorerController extends AbstractController
{
    private SluggerInterface $slugger;
    private $targetDirectory;

    public function __construct(SluggerInterface $slugger, $targetDirectory)
    {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
    }

    #[Route('/explorer', name: 'app_explorer')]
    public function index(Request $request, ManagerRegistry $mr): Response
    {

        $files = $this->getUser()->getFiles();

        return $this->render(
            'explorer/index.html.twig', [
                'files' => $this->getUser()->getFiles()
            ]
        );
    }

    #[Route('/api/explorer/update', name: 'app_api_explorer_update')]
    public function update()
    {
        return $this->json([
            "message" => "0"
        ]);
    }
    // debug only
    #[Route('/explorer/upload', name: 'app_explorer_upload')]
    public function upload( Request $request, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(ExplorerFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // create new record row in hloud.file
            $fileService = new FileService($this->targetDirectory, $mr, $this->slugger);
            $fileService->uploadFile($form->get('file')->getData(), $this->getUser());

            return $this->redirectToRoute('app_explorer');
        }


        return $this->render(
            'explorer/upload.html.twig',
            [
                'explorerUploadForm' => $form->createView()
            ]
        );
    }
}
