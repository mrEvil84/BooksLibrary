<?php
declare(strict_types=1);

namespace App\BookLibrary\Domain\Repository;

use App\BookLibrary\Domain\Entity\LibraryMember;

interface LibraryMemberRepositoryInterface
{
    public function findByIdentificationNumber(int $identificationNumber): ?LibraryMember;

    /**
     * @return LibraryMember[]
     */
    public function findAll(): array;

    public function save(LibraryMember $libraryMember): void;

    public function remove(LibraryMember $libraryMember): void;
}
