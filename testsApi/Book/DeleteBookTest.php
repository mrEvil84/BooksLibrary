<?php
declare(strict_types=1);

namespace App\ApiTests\Book;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\BookLibrary\Domain\Entity\Book;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class DeleteBookTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    public function testDeleteBook(): void
    {
        $client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        new ORMPurger($entityManager)->purge();

        $book = Book::create(123111, 'Lord of the Rings', 'J.R.R. Tolkien');
        $entityManager->persist($book);
        $entityManager->flush();

        $client->request('DELETE', '/api/book/123111');

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $entityManager->clear();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['serialNumber' => 123111]);

        self::assertNull($book);
    }
}
