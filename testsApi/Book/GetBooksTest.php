<?php
declare(strict_types=1);

namespace App\ApiTests\Book;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\BookLibrary\Domain\Entity\Book;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class GetBooksTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    public function testGetBooks(): void
    {
        $client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        new ORMPurger($entityManager)->purge();

        $book = Book::create(123111, 'Lord of the Rings', 'J.R.R. Tolkien');
        $entityManager->persist($book);
        $entityManager->flush();

        $client->request('GET', '/api/books', [
            'headers' => ['Accept' => 'application/json'],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonContains([
            [
                'serialNumber' => 123111,
                'name' => 'Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'isBorrowed' => false,
            ],
        ]);
    }
}