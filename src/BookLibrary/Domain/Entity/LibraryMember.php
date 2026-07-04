<?php
declare(strict_types=1);

namespace App\BookLibrary\Domain\Entity;

use App\BookLibrary\Infrastructure\Doctrine\Repository\LibraryMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryMemberRepository::class)]
#[ORM\Table(name: 'library_member')]
#[ORM\UniqueConstraint(name: 'uniq_library_member_identification_number', columns: ['identification_number'])]
class LibraryMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $identificationNumber;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $surname;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'borrowedBy')]
    private Collection $borrowedBooks;

    public function __construct()
    {
        $this->borrowedBooks = new ArrayCollection();
    }

    public static function create(int $identificationNumber, string $name, string $surname): self
    {
        $libraryMember = new self();
        $libraryMember->identificationNumber = $identificationNumber;
        $libraryMember->name = $name;
        $libraryMember->surname = $surname;

        return $libraryMember;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificationNumber(): int
    {
        return $this->identificationNumber;
    }

    public function setIdentificationNumber(int $identificationNumber): static
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBorrowedBooks(): Collection
    {
        return $this->borrowedBooks;
    }
}
