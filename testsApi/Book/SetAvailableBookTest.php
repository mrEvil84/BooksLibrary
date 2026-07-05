<?php
declare(strict_types=1);

namespace App\ApiTests\Book;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Entity\LibraryMember;
use DateTime;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class SetAvailableBookTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    public function testSetAvailableBook(): void
    {
        $client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        new ORMPurger($entityManager)->purge();

        $libraryMember = LibraryMember::create(654321, 'John', 'Doe');
        $entityManager->persist($libraryMember);

        $book = Book::create(123111, 'Lord of the Rings', 'J.R.R. Tolkien');
        $book->setBorrowedBy($libraryMember);
        $book->setDateOfBorrowing(new DateTime());
        $entityManager->persist($book);

        $entityManager->flush();

        $client->request('PATCH', '/api/book/setAvailable', [
            'headers' => ['Content-Type' => 'application/merge-patch+json'],
            'json' => [
                'bookSerialNumber' => 123111,
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $entityManager->clear();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['serialNumber' => 123111]);

        self::assertInstanceOf(Book::class, $book);
        self::assertFalse($book->isBorrowed());
        self::assertNull($book->getBorrowedBy());
        self::assertNull($book->getDateOfBorrowing());
    }
}
