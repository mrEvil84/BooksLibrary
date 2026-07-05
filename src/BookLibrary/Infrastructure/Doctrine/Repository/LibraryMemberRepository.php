<?php
declare(strict_types=1);

namespace App\BookLibrary\Infrastructure\Doctrine\Repository;

use App\BookLibrary\Domain\Entity\LibraryMember;
use App\BookLibrary\Domain\Repository\LibraryMemberRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LibraryMember>
 */
class LibraryMemberRepository extends ServiceEntityRepository implements LibraryMemberRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryMember::class);
    }

    public function findByIdentificationNumber(int $identificationNumber): ?LibraryMember
    {
        return $this->findOneBy(['identificationNumber' => $identificationNumber]);
    }
}
