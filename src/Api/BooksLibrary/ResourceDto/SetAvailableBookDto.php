<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\ResourceDto;

use Symfony\Component\Validator\Constraints as Assert;

class SetAvailableBookDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Range(min: 100000, max: 999999)]
        public int $bookSerialNumber,
    ) {}
}
