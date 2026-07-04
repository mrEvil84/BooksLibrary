<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\ResourceDto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Api\BooksLibrary\State\AddBookProcessor;
use Symfony\Component\Validator\Constraints as Assert;

class InputBookDto
{
    public function __construct(
        public int $serialNumber,
        public string $title,
        public string $author,
    ) {}
}
