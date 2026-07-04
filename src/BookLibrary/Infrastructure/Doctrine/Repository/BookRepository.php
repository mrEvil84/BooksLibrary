<?php
declare(strict_types=1);

namespace App\BookLibrary\Infrastructure\Doctrine\Repository;

use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Repository\BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findBySerialNumber(int $serialNumber): ?Book
    {
        return $this->findOneBy(['serialNumber' => $serialNumber]);
    }

    public function save(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function remove(Book $book): void
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }
}
