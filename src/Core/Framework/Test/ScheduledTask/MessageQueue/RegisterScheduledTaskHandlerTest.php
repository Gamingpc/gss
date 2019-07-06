<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\ScheduledTask\MessageQueue;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\ScheduledTask\MessageQueue\RegisterScheduledTaskHandler;
use Shopware\Core\Framework\ScheduledTask\MessageQueue\RegisterScheduledTaskMessage;
use Shopware\Core\Framework\ScheduledTask\Registry\TaskRegistry;

class RegisterScheduledTaskHandlerTest extends TestCase
{
    public function testItHandlesTheRightMessage()
    {
        static::assertEquals([RegisterScheduledTaskMessage::class], RegisterScheduledTaskHandler::getHandledMessages());
    }

    public function testItCallsRegister()
    {
        $registry = $this->createMock(TaskRegistry::class);
        $registry->expects(static::once())
            ->method('registerTasks');
        $handler = new RegisterScheduledTaskHandler($registry);

        $message = new RegisterScheduledTaskMessage();

        $handler->handle($message);
    }
}
