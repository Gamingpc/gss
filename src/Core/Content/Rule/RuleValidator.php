<?php declare(strict_types=1);

namespace Shopware\Core\Content\Rule;

use Shopware\Core\Content\Rule\Aggregate\RuleCondition\RuleConditionDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\InsertCommand;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\UpdateCommand;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommandInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteContext;
use Shopware\Core\Framework\Rule\Collector\RuleConditionRegistry;
use Shopware\Core\Framework\Rule\Rule;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\WriteCommandValidatorInterface;
use Shopware\Core\Framework\Validation\WriteConstraintViolationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RuleValidator implements WriteCommandValidatorInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var RuleConditionRegistry
     */
    private $ruleConditionRegistry;

    public function __construct(ValidatorInterface $validator, RuleConditionRegistry $ruleConditionRegistry)
    {
        $this->validator = $validator;
        $this->ruleConditionRegistry = $ruleConditionRegistry;
    }

    /**
     * @param WriteCommandInterface[] $commands
     */
    public function preValidate(array $commands, WriteContext $context): void
    {
        $violationList = new ConstraintViolationList();
        foreach ($commands as $command) {
            if (!($command instanceof InsertCommand || $command instanceof UpdateCommand) || $command->getDefinition()->getClass() !== RuleConditionDefinition::class) {
                continue;
            }

            $payload = $command->getPayload();
            $currentId = Uuid::fromBytesToHex($command->getPrimaryKey()['id']);
            $basePath = sprintf('/conditions/%s', $currentId);

            /** @var string|null $type */
            $type = null;
            if (array_key_exists('type', $payload)) {
                $type = $payload['type'];
            }

            if (!$this->isRule($type)) {
                $violationList->add(
                    $this->buildViolation(
                        'This "type" value (%value%) is invalid.',
                        ['%value%' => $type ?? 'NULL'],
                        null,
                        $basePath . '/type'
                    )
                );
                continue;
            }

            /** @var Rule $rule */
            $rule = $this->ruleConditionRegistry->getRuleInstance($type);
            $validations = $rule->getConstraints();

            $violationList->addAll($this->validateConsistence($basePath, $validations, $this->extractValue($payload)));
        }

        $this->tryToThrow($violationList);
    }

    public function postValidate(array $writeCommands, WriteContext $context): void
    {
        // nth
    }

    private function tryToThrow(ConstraintViolationList $violations): void
    {
        if ($violations->count() > 0) {
            throw new WriteConstraintViolationException($violations);
        }
    }

    private function isRule(?string $type): bool
    {
        if (!$type) {
            return false;
        }

        return $this->ruleConditionRegistry->has($type);
    }

    private function extractValue(array $payload): array
    {
        if (!array_key_exists('value', $payload) || $payload['value'] === null) {
            return [];
        }

        $ret = json_decode($payload['value'], true);

        return $ret;
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

    private function validateConsistence(string $basePath, array $fieldValidations, array $payload): ConstraintViolationList
    {
        $list = new ConstraintViolationList();
        foreach ($fieldValidations as $fieldName => $validations) {
            $currentPath = sprintf('%s/%s', $basePath, $fieldName);
            $list->addAll(
                $this->validator->startContext()
                    ->atPath($currentPath)
                    ->validate($payload[$fieldName] ?? null, $validations)
                    ->getViolations()
            );
        }

        foreach ($payload as $fieldName => $value) {
            $currentPath = sprintf('%s/%s', $basePath, $fieldName);

            if (!array_key_exists($fieldName, $fieldValidations) && $fieldName !== '_name') {
                $list->add(
                    $this->buildViolation(
                        'The property "{{ fieldName }}" is not allowed.', ['{{ fieldName }}' => $fieldName],
                        null,
                        $currentPath
                    )
                );
            }
        }

        return $list;
    }
}
