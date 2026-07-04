<?php
declare(strict_types=1);

namespace App\BookLibrary\Domain\Repository;

use App\BookLibrary\Domain\Entity\Book;

interface BookRepositoryInterface
{
    public function findBySerialNumber(int $serialNumber): ?Book;

    /**
     * @return Book[]
     */
    public function findAll(): array;

    public function save(Book $book): void;

    public function remove(Book $book): void;
}
