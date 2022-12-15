<?php

namespace App\Service;

use App\Entity\File;
use App\Entity\User;
use DateTime;
            use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService
{
    private $targetDirectory;
    private ManagerRegistry $mr;
    private SluggerInterface $slugger;

    public function __construct($targetDirectory, ManagerRegistry $mr, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->mr = $mr;
        $this->slugger = $slugger;
    }

    public function uploadFile(UploadedFile $uploadedFile, User $user)
    {
        $file = new File();
        $entityManager = $this->mr->getManager();

        $origFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($origFileName);
        $fileName = $safeFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $fileSize = $uploadedFile->getSize();

        try {
            $file->setFileName($fileName);
            $file->setFileExtension($uploadedFile->guessExtension());
            $file->setFileSize($fileSize);
            $file->setFileOwner($user);
            $file->setDateUploaded(new DateTime());
            $entityManager->persist($file);
            $entityManager->flush();
            $uploadedFile->move($this->getTargetDirectory(), $fileName);
        }
        catch (FileException $exc) {
            //
        }

        return $file->getId();
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}