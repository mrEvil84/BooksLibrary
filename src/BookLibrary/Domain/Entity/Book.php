<?php
declare(strict_types=1);

namespace App\BookLibrary\Domain\Entity;

use App\BookLibrary\Infrastructure\Doctrine\Repository\BookRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'book')]
#[ORM\UniqueConstraint(name: 'uniq_book_serial_number', columns: ['serial_number'])]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $serialNumber;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $author;

    #[ORM\ManyToOne(targetEntity: LibraryMember::class, inversedBy: 'borrowedBooks')]
    #[ORM\JoinColumn(name: 'library_member_id', nullable: true, onDelete: 'SET NULL')]
    private ?LibraryMember $borrowedBy = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $dateOfBorrowing = null;

    public static function create(int $serialNumber, string $title, string $author): self
    {
        $book = new self();
        $book->author = $author;
        $book->serialNumber = $serialNumber;
        $book->title = $title;
        return $book;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): int
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(int $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getBorrowedBy(): ?LibraryMember
    {
        return $this->borrowedBy;
    }

    public function setBorrowedBy(?LibraryMember $borrowedBy): static
    {
        $this->borrowedBy = $borrowedBy;

        return $this;
    }

    public function setBookAvailable(): void
    {
        $this->borrowedBy = null;
        $this->dateOfBorrowing = null;
    }

    public function isBorrowed(): bool
    {
        return $this->borrowedBy !== null;
    }

    public function getDateOfBorrowing(): ?DateTime
    {
        return $this->dateOfBorrowing;
    }

    public function setDateOfBorrowing(?DateTime $dateOfBorrowing): static
    {
        $this->dateOfBorrowing = $dateOfBorrowing;

        return $this;
    }
}
