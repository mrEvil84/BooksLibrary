<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\BookLibrary\Domain\Entity\LibraryMember;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LibraryMemberFixtures extends Fixture
{
    private const MEMBERS = [
        [200001, 'Jan', 'Kowalski'],
        [200002, 'Anna', 'Nowak'],
        [200003, 'Piotr', 'Wiśniewski'],
        [200004, 'Katarzyna', 'Wójcik'],
        [200005, 'Tomasz', 'Kowalczyk'],
        [200006, 'Magdalena', 'Kamińska'],
        [200007, 'Krzysztof', 'Lewandowski'],
        [200008, 'Agnieszka', 'Zielińska'],
        [200009, 'Michał', 'Szymański'],
        [200010, 'Ewa', 'Woźniak'],
        [200011, 'Paweł', 'Dąbrowski'],
        [200012, 'Joanna', 'Kozłowska'],
        [200013, 'Marcin', 'Jankowski'],
        [200014, 'Aleksandra', 'Mazur'],
        [200015, 'Grzegorz', 'Kwiatkowski'],
        [200016, 'Monika', 'Krawczyk'],
        [200017, 'Adam', 'Piotrowski'],
        [200018, 'Natalia', 'Grabowska'],
        [200019, 'Rafał', 'Pawłowski'],
        [200020, 'Karolina', 'Michalska'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MEMBERS as [$identificationNumber, $name, $surname]) {
            $manager->persist(LibraryMember::create($identificationNumber, $name, $surname));
        }

        $manager->flush();
    }
}
