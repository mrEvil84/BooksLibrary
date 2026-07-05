<?php
declare(strict_types=1);

namespace App\ApiTests\Book;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\BookLibrary\Domain\Entity\Book;
use App\BookLibrary\Domain\Entity\LibraryMember;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class BorrowBookTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    public function testBorrowBook(): void
    {
        $client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        new ORMPurger($entityManager)->purge();

        $book = Book::create(123111, 'Lord of the Rings', 'J.R.R. Tolkien');
        $entityManager->persist($book);

        $libraryMember = LibraryMember::create(654321, 'John', 'Doe');
        $entityManager->persist($libraryMember);

        $entityManager->flush();

        $client->request('PATCH', '/api/book/borrow', [
            'headers' => ['Content-Type' => 'application/merge-patch+json'],
            'json' => [
                'bookSerialNumber' => 123111,
                'libraryMemberNumber' => 654321,
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $entityManager->clear();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['serialNumber' => 123111]);

        self::assertInstanceOf(Book::class, $book);
        self::assertTrue($book->isBorrowed());
        self::assertNotNull($book->getBorrowedBy());
        self::assertSame(654321, $book->getBorrowedBy()->getIdentificationNumber());
        self::assertNotNull($book->getDateOfBorrowing());
    }
}
