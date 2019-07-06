<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\Uuid;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException;
use Shopware\Core\Framework\Uuid\Uuid;

class UuidTest extends TestCase
{
    public function testRandomHex()
    {
        static::assertNotEquals(Uuid::randomHex(), Uuid::randomHex());
        static::assertTrue(Uuid::isValid(Uuid::randomHex()));
        static::assertStringNotContainsString('-', Uuid::randomHex());
    }

    public function testRandomBytes()
    {
        static::assertNotEquals(Uuid::randomBytes(), Uuid::randomBytes());
        static::assertSame(16, \strlen(Uuid::randomBytes()));
    }

    public function testHexRoundtrip()
    {
        $hex = Uuid::randomHex();
        $bytes = Uuid::fromHexToBytes($hex);

        static::assertSame($hex, Uuid::fromBytesToHex($bytes));
    }

    public function testBytesRoundtrip()
    {
        $bytes = Uuid::randomBytes();
        $hex = Uuid::fromBytesToHex($bytes);

        static::assertSame($bytes, Uuid::fromHexToBytes($hex));
    }

    public function testFromBytesToHexThrowsOnInvalidLength()
    {
        $this->expectException(InvalidUuidLengthException::class);
        Uuid::fromBytesToHex('a');
    }

    public function testUuidFormat()
    {
        for ($i = 0; $i < 100; ++$i) {
            $uuid = Uuid::randomHex();
            static::assertSame(32, strlen($uuid));
            // uuid 4 is mostly random except the version is at pos 13 and pos 17 is either 8, 9, a or b
            static::assertSame('4', $uuid[12]);
            static::assertContains($uuid[16], ['8', '9', 'a', 'b']);
            static::assertTrue($uuid === strtolower($uuid));
        }
    }
}
