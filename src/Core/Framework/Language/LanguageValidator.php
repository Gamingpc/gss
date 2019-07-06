<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Language;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\DeleteCommand;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\InsertCommand;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\UpdateCommand;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommandInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteContext;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\WriteCommandValidatorInterface;
use Shopware\Core\Framework\Validation\WriteConstraintViolationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class LanguageValidator implements WriteCommandValidatorInterface
{
    public const VIOLATION_PARENT_HAS_PARENT = 'parent_has_parent_violation';

    public const VIOLATION_CODE_REQUIRED_FOR_ROOT_LANGUAGE = 'code_required_for_root_language';

    public const VIOLATION_DELETE_DEFAULT_LANGUAGE = 'delete_default_language_violation';

    public const DEFAULT_LANGUAGES = [Defaults::LANGUAGE_SYSTEM, Defaults::LANGUAGE_SYSTEM_DE];

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function postValidate(array $commands, WriteContext $context): void
    {
        $affectedIds = $this->getAffectedIds($commands);
        if (\count($affectedIds) === 0) {
            return;
        }

        $violations = new ConstraintViolationList();
        $violations->addAll($this->getInheritanceViolations($affectedIds));
        $violations->addAll($this->getMissingTranslationCodeViolations($affectedIds));

        $this->tryToThrow($violations);
    }

    /**
     * @param WriteCommandInterface[] $commands
     */
    public function preValidate(array $commands, WriteContext $context): void
    {
        $violations = new ConstraintViolationList();

        foreach ($commands as $command) {
            if (!$command instanceof DeleteCommand || $command->getDefinition()->getClass() !== LanguageDefinition::class) {
                continue;
            }

            $pk = $command->getPrimaryKey();
            $id = \strtolower(Uuid::fromBytesToHex($pk['id']));
            if (!\in_array($id, [Defaults::LANGUAGE_SYSTEM, Defaults::LANGUAGE_SYSTEM_DE], true)) {
                continue;
            }

            $violations->add(
                $this->buildViolation(
                    'The default language {{ id }} cannot be deleted.',
                    ['{{ id }}' => $id],
                    null,
                    '/' . $id,
                    $id,
                    self::VIOLATION_DELETE_DEFAULT_LANGUAGE
                )
            );
        }

        $this->tryToThrow($violations);
    }

    private function getInheritanceViolations(array $affectedIds): ConstraintViolationList
    {
        $statement = $this->connection->executeQuery('
            SELECT child.id 
            FROM language child
            INNER JOIN language parent ON parent.id = child.parent_id
            WHERE (child.id IN (:ids) OR child.parent_id IN (:ids))
            AND parent.parent_id IS NOT NULL',
            ['ids' => $affectedIds],
            ['ids' => Connection::PARAM_STR_ARRAY]
        );
        $ids = $statement->fetchAll(FetchMode::COLUMN);

        $violations = new ConstraintViolationList();
        foreach ($ids as $binId) {
            $id = Uuid::fromBytesToHex($binId);
            $violations->add(
                $this->buildViolation(
                    'Language inheritance limit for the child {{ id }} exceeded. A Language must not be nested deeper than one level.',
                    ['{{ id }}' => $id],
                    null,
                    '/' . $id . '/parentId',
                    $id,
                    self::VIOLATION_PARENT_HAS_PARENT
                )
            );
        }

        return $violations;
    }

    private function getMissingTranslationCodeViolations(array $affectedIds): ConstraintViolationList
    {
        $statement = $this->connection->executeQuery('
            SELECT lang.id
            FROM language lang
            LEFT JOIN locale l ON lang.translation_code_id = l.id
            WHERE l.id IS NULL # no translation code
            AND lang.parent_id IS NULL # root
            AND lang.id IN (:ids)',
            ['ids' => $affectedIds],
            ['ids' => Connection::PARAM_STR_ARRAY]
        );
        $ids = $statement->fetchAll(FetchMode::COLUMN);

        $violations = new ConstraintViolationList();
        foreach ($ids as $binId) {
            $id = Uuid::fromBytesToHex($binId);
            $violations->add(
                $this->buildViolation(
                    'Root language {{ id }} requires a translation code',
                    ['{{ id }}' => $id],
                    null,
                    '/' . $id . '/translationCodeId',
                    $id,
                    self::VIOLATION_CODE_REQUIRED_FOR_ROOT_LANGUAGE
                )
            );
        }

        return $violations;
    }

    /**
     * @param WriteCommandInterface[] $commands
     *
     * @return string[]
     */
    private function getAffectedIds(array $commands): array
    {
        $ids = [];
        foreach ($commands as $command) {
            if ($command->getDefinition()->getClass() !== LanguageDefinition::class) {
                continue;
            }
            if ($command instanceof InsertCommand || $command instanceof UpdateCommand) {
                $ids[] = $command->getPrimaryKey()['id'];
            }
        }

        return $ids;
    }

    /**
     * @throws WriteConstraintViolationException
     */
    private function tryToThrow(ConstraintViolationList $violations): void
    {
        if ($violations->count() > 0) {
            throw new WriteConstraintViolationException($violations);
        }
    }

    private function buildViolation(
        string $messageTemplate,
        array $parameters,
        $root = null,
        ?string $propertyPath = null,
        $invalidValue = null,
        $code = null
    ): ConstraintViolationInterface {
        return new ConstraintViolation(
            str_replace(array_keys($parameters), array_values($parameters), $messageTemplate),
            $messageTemplate,
            $parameters,
            $root,
            $propertyPath,
            $invalidValue,
            $plural = null,
            $code,
            $constraint = null,
            $cause = null
        );
    }
}
