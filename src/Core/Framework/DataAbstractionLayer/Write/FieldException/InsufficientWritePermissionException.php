<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\Write\FieldException;

use Symfony\Component\Validator\ConstraintViolationList;

class InsufficientWritePermissionException extends WriteFieldException
{
    /**
     * @var ConstraintViolationList
     */
    private $constraintViolationList;

    /**
     * @var string
     */
    private $path;

    public function __construct(string $path, ConstraintViolationList $constraintViolationList)
    {
        parent::__construct(
            'Caught {{ count }} permission errors.',
            ['count' => count($constraintViolationList)]
        );

        $this->constraintViolationList = $constraintViolationList;
        $this->path = $path;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->constraintViolationList as $violation) {
            $result[] = [
                'message' => $violation->getMessage(),
                'messageTemplate' => $violation->getMessageTemplate(),
                'parameters' => $violation->getParameters(),
                'propertyPath' => $violation->getPropertyPath(),
            ];
        }

        return $result;
    }

    public function getViolations(): ConstraintViolationList
    {
        return $this->constraintViolationList;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getConcern(): string
    {
        return 'insufficient-permission';
    }
}
