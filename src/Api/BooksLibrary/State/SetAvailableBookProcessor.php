<?php
declare(strict_types=1);

namespace App\Api\BooksLibrary\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\BooksLibrary\ResourceDto\SetAvailableBookDto;
use App\BookLibrary\Application\BookAvailabilitySetter;
use App\BookLibrary\Application\Exception\BookNotFoundException;
use App\BookLibrary\Application\Exception\InvalidBookSerialNumberException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<SetAvailableBookDto, void>
 */
final readonly class SetAvailableBookProcessor implements ProcessorInterface
{
    public function __construct(
        private BookAvailabilitySetter $bookAvailabilitySetter,
    ) {
    }

    /**
     * @param SetAvailableBookDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        try {
            $this->bookAvailabilitySetter->setAvailable($data->bookSerialNumber);
        } catch (InvalidBookSerialNumberException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        } catch (BookNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }
}
