<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\Event;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Read\EntityReaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearcherInterface;
use Shopware\Core\Framework\Event\BusinessEventDispatcher;
use Shopware\Core\Framework\Event\BusinessEvents;
use Shopware\Core\Framework\Event\EventAction\EventActionCollection;
use Shopware\Core\Framework\Event\EventAction\EventActionDefinition;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BusinessEventDispatcherTest extends TestCase
{
    use IntegrationTestBehaviour;

    /**
     * @var EntityRepositoryInterface
     */
    private $eventActionRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ActionTestSubscriber
     */
    private $testSubscriber;

    protected function setUp(): void
    {
        $this->testSubscriber = new ActionTestSubscriber();

        $this->eventActionRepository = $this->getContainer()->get('event_action.repository');
        $this->dispatcher = $this->getContainer()->get('event_dispatcher');
        $this->dispatcher->addSubscriber($this->testSubscriber);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->dispatcher->removeSubscriber($this->testSubscriber);
    }

    public function testAllEventsPassthru(): void
    {
        $context = Context::createDefaultContext();
        $event = new TestBusinessEvent($context);

        $searcher = static::createMock(EntitySearcherInterface::class);
        $reader = static::createMock(EntityReaderInterface::class);
        $eventDispatcherMock = static::createMock(EventDispatcherInterface::class);
        $eventDispatcherMock->expects(static::exactly(2))
            ->method('dispatch')
            ->willReturn($event);

        $reader->expects(static::once())
            ->method('read')
            ->willReturn(new EventActionCollection());

        $dispatcher = new BusinessEventDispatcher($eventDispatcherMock, $searcher, $reader, $this->getContainer()->get(EventActionDefinition::class));
        $dispatcher->dispatch($event->getName(), $event);
    }

    public function testSingleEventActionIsDispatched(): void
    {
        $context = Context::createDefaultContext();
        $event = new TestBusinessEvent($context);

        $this->eventActionRepository->create([[
            'eventName' => TestBusinessEvent::EVENT_NAME,
            'actionName' => 'unit_test_action',
        ]], $context);

        $this->dispatcher->dispatch($event->getName(), $event);

        static::assertEquals(1, $this->testSubscriber->events[BusinessEvents::GLOBAL_EVENT] ?? 0);
        static::assertEquals(1, $this->testSubscriber->actions['unit_test_action'] ?? 0);
    }

    public function testMultipleEventActionIsDispatched(): void
    {
        $context = Context::createDefaultContext();
        $event = new TestBusinessEvent($context);

        $this->eventActionRepository->create([
            [
                'eventName' => TestBusinessEvent::EVENT_NAME,
                'actionName' => 'unit_test_action',
            ],
            [
                'eventName' => TestBusinessEvent::EVENT_NAME,
                'actionName' => '2nd_unit_test_action',
            ],
        ], $context);

        $this->dispatcher->dispatch($event->getName(), $event);

        static::assertEquals(1, $this->testSubscriber->events[BusinessEvents::GLOBAL_EVENT] ?? 0, 'Global action event should only be dispatched once');
        static::assertEquals(1, $this->testSubscriber->events[TestBusinessEvent::EVENT_NAME] ?? 0);
        static::assertEquals(1, $this->testSubscriber->actions['unit_test_action'] ?? 0);
        static::assertEquals(1, $this->testSubscriber->actions['2nd_unit_test_action'] ?? 0);
    }

    public function testEventActionWithEmptyConfigReturnsArray(): void
    {
        $context = Context::createDefaultContext();
        $event = new TestBusinessEvent($context);

        $this->eventActionRepository->create([[
            'eventName' => TestBusinessEvent::EVENT_NAME,
            'actionName' => 'unit_test_action',
        ]], $context);

        $this->dispatcher->dispatch($event->getName(), $event);

        static::assertIsArray($this->testSubscriber->lastActionConfig);
        static::assertEmpty($this->testSubscriber->lastActionConfig);
    }

    public function testEventActionWithConfig(): void
    {
        $context = Context::createDefaultContext();
        $event = new TestBusinessEvent($context);

        $eventConfig = [
            'foo' => 'bar',
            'wusel' => 'dusel',
        ];

        $this->eventActionRepository->create([
            [
                'eventName' => TestBusinessEvent::EVENT_NAME,
                'actionName' => 'unit_test_action',
                'config' => $eventConfig,
            ],
        ], $context);

        $this->dispatcher->dispatch($event->getName(), $event);

        static::assertEquals(1, $this->testSubscriber->events[BusinessEvents::GLOBAL_EVENT] ?? 0, 'Global action event should only be dispatched once');
        static::assertEquals(1, $this->testSubscriber->events[TestBusinessEvent::EVENT_NAME] ?? 0);
        static::assertEquals(1, $this->testSubscriber->actions['unit_test_action'] ?? 0);

        static::assertEquals($eventConfig, $this->testSubscriber->lastActionConfig);
    }
}
