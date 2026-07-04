<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\ResourceDto;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource()]
class OutputBookDto
{
    public function __construct(
        #[ApiProperty(example: '123456')]
        public int $serialNumber,
        #[ApiProperty(example: 'Lord of the rings')]
        public string $name,
        #[ApiProperty(example: 'J.R.R Tolkien')]
        public string $author,
    ) {}
}
