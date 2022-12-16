<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    private ?string $fileExtension = null;

    #[ORM\Column(length: 255)]
    private ?string $fileSize = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $fileOwner = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => "current_timestamp"])]
    private ?\DateTimeInterface $date_uploaded = null;

    #[ORM\Column(length: 255)]
    private ?string $originalFileName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(string $fileExtension): self
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function getFileSize(): ?string
    {
        return $this->fileSize;
    }

    public function setFileSize(string $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getFileOwner(): ?User
    {
        return $this->fileOwner;
    }

    public function setFileOwner(?User $fileOwner): self
    {
        $this->fileOwner = $fileOwner;

        return $this;
    }

    public function getDateUploaded(): ?\DateTimeInterface
    {
        return $this->date_uploaded;
    }

    public function setDateUploaded(?\DateTimeInterface $date_uploaded): self
    {
        $this->date_uploaded = $date_uploaded;

        return $this;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $originalFileName): self
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }
}
