<?php

namespace App\Service;

use App\Entity\File;
// use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use DateTime;

class FileUploader
{
    private $targetDirectory;
    private SluggerInterface $slugger;
    private ManagerRegistry $doctrine;

    public function __construct($targetDirectory, SluggerInterface $slugger, ManagerRegistry $doctrine)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->doctrine = $doctrine;
    }

    public function upload(UploadedFile $uploadedFile)
    {
        $file = new File();
        // $fileOwner = 
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        // $fileExtension = pathinfo($uploadedFile->getClientOriginalExtension(), PATHINFO_EXTENSION);
        $fileSize = $uploadedFile->getSize();
        $safeFileName = $this->slugger->slug($originalFileName);
        $fileName = $safeFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $file->setFileName($safeFileName);
            $file->setFileExtension($uploadedFile->guessExtension());
            $file->setFileSize($fileSize);
            $file->setDateUploaded(new DateTime("now"));
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($file);
            $entityManager->flush();
            $uploadedFile->move($this->getTargetDirectory(), $fileName);
        }
        catch (FileException $e) {
            //
        }

        return $file->getId();
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
// use App\Entity\File;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
// use Symfony\Component\String\Slugger\SluggerInterface;
// use Symfony\Component\Security\Core\Security;

// class FileUploader
// {
//     private $targetDirectory;
//     private Security $security;
//     private SluggerInterface $slugger;
//     private EntityManagerInterface $entityManager;

//     public function __contruct(
//         $targetDirectory,
//         Security $security,
//         SluggerInterface $sluggerInterface,
//         EntityManagerInterface $entityManager
//     ) {
//         $this->targetDirectory = $targetDirectory;
//         $this->security = $security;
//         $this->slugger = $sluggerInterface;
//         $this->entityManager = $entityManager;
//     }

//     public function upload(UploadedFile $uploadedFile)
//     {
//         $fileOwner = $this->security->getUser();
//         $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
//         $fileExtension = pathinfo($uploadedFile->getClientOriginalExtension(), PATHINFO_EXTENSION);
//         $fileSize = $uploadedFile->getSize();
//         $safeFileName = $this->slugger->slug($originalFileName);
//         $fileName = $safeFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        
//         try
//         {
//             $uploadedFile->move($this->getTargetDirectory(), $fileName);

//             $file = new File();
//             $user = $this->entityManager->getRepository(User::class)->find($fileOwner['id']);
//             $file->setFileName($safeFileName);
//             $file->setFileExtension($fileExtension);
//             $file->setFileSize($fileSize);
//             $file->setFileOwner($fileOwner);
//             $user->addFile($file);
//             $this->entityManager->persist($file);
//             $this->entityManager->persist($user);
//             $this->entityManager->flush();
//         }
//         catch (FileException $e) {
//             //
//         }

//         return $fileName;
//     }

//     public function getTargetDirectory()
//     {
//         return $this->targetDirectory;
//     }
// }

