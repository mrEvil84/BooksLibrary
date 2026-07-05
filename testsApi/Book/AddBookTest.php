<?php
declare(strict_types=1);

namespace App\ApiTests\Book;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\BookLibrary\Domain\Entity\Book;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AddBookTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    public function testAddBook(): void
    {
        $client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        new ORMPurger($entityManager)->purge();

        $client->request('POST', '/api/book/add', [
            'json' => [
                'serialNumber' => 123111,
                'title' => 'Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertJsonContains([
            'serialNumber' => 123111,
            'name' => 'Lord of the Rings',
            'author' => 'J.R.R. Tolkien',
            'isBorrowed' => false,
        ]);

        $entityManager->clear();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['serialNumber' => 123111]);

        self::assertInstanceOf(Book::class, $book);
        self::assertSame('Lord of the Rings', $book->getTitle());
        self::assertSame('J.R.R. Tolkien', $book->getAuthor());
        self::assertFalse($book->isBorrowed());
    }
}
