<?php
declare(strict_types=1);

namespace App\BookLibrary\Domain\Repository;

use App\BookLibrary\Domain\Entity\LibraryMember;

interface LibraryMemberRepositoryInterface
{
    public function findByIdentificationNumber(int $identificationNumber): ?LibraryMember;
}
