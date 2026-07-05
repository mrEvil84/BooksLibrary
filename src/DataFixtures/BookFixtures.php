<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\BookLibrary\Domain\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    private const BOOKS = [
        [100001, 'Pan Tadeusz', 'Adam Mickiewicz'],
        [100002, 'Lalka', 'Bolesław Prus'],
        [100003, 'Quo Vadis', 'Henryk Sienkiewicz'],
        [100004, 'Ferdydurke', 'Witold Gombrowicz'],
        [100005, 'Wiedźmin: Ostatnie życzenie', 'Andrzej Sapkowski'],
        [100006, '1984', 'George Orwell'],
        [100007, 'Folwark zwierzęcy', 'George Orwell'],
        [100008, 'Zbrodnia i kara', 'Fiodor Dostojewski'],
        [100009, 'Mistrz i Małgorzata', 'Michaił Bułhakow'],
        [100010, 'Duma i uprzedzenie', 'Jane Austen'],
        [100011, 'Władca Pierścieni: Drużyna Pierścienia', 'J.R.R. Tolkien'],
        [100012, 'Hobbit, czyli tam i z powrotem', 'J.R.R. Tolkien'],
        [100013, 'Harry Potter i Kamień Filozoficzny', 'J.K. Rowling'],
        [100014, 'Rzeźnia numer pięć', 'Kurt Vonnegut'],
        [100015, 'Sto lat samotności', 'Gabriel García Márquez'],
        [100016, 'Proces', 'Franz Kafka'],
        [100017, 'Stary człowiek i morze', 'Ernest Hemingway'],
        [100018, 'Buszujący w zbożu', 'J.D. Salinger'],
        [100019, 'Solaris', 'Stanisław Lem'],
        [100020, 'Cyberiada', 'Stanisław Lem'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::BOOKS as [$serialNumber, $title, $author]) {
            $manager->persist(Book::create($serialNumber, $title, $author));
        }

        $manager->flush();
    }
}
