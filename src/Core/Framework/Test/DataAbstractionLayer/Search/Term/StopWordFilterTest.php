<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\DataAbstractionLayer\Search\Term;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Term\StopWordFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Term\Tokenizer;

class StopWordFilterTest extends TestCase
{
    /**
     * @dataProvider cases
     */
    public function testStopWords(string $sentence, array $expected): void
    {
        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->tokenize($sentence);

        $context = Context::createDefaultContext();

        $filter = new StopWordFilter();

        $tokens = array_flip($tokens);

        $filtered = $filter->filter($tokens, $context);

        $filtered = array_flip($filtered);
        $filtered = array_values($filtered);

        static::assertSame($expected, $filtered);
    }

    public function cases(): array
    {
        return [
            [
                'Hello my name is shopware',
                ['hello', 'shopware'],
            ],
            [
                'Damit Ihr indess erkennt, woher dieser ganze Irrthum gekommen ist, und weshalb man die Lust anklagt und den Schmerz lobet, so will ich Euch Alles eröffnen und auseinander setzen, was jener Begründer der Wahrheit und gleichsam Baumeister des glücklichen Lebens selbst darüber gesagt hat. Niemand, sagt er, verschmähe, oder hasse, oder fliehe die Lust als solche, sondern weil grosse Schmerzen ihr folgen, wenn man nicht mit Vernunft ihr nachzugehen verstehe.',
                ['indess', 'erkennt', 'irrthum', 'lust', 'anklagt', 'schmerz', 'lobet', 'auseinander', 'begründer', 'wahrheit', 'gleichsam', 'baumeister', 'glücklichen', 'lebens', 'verschmähe', 'hasse', 'fliehe', 'grosse', 'schmerzen', 'folgen', 'vernunft', 'nachzugehen', 'verstehe'],
            ],
            [
                'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. ',
                ['explain', 'mistaken', 'idea', 'denouncing', 'pleasure', 'praising', 'pain', 'born', 'complete', 'account', 'expound', 'actual', 'teachings', 'great', 'explorer', 'truth', 'master', 'builder', 'human', 'happiness'],
            ],
            [
                'But I must explain to you how all this mistaken idea. Damit Ihr indess erkennt, woher dieser ganze Irrthum gekommen',
                ['explain', 'mistaken', 'idea', 'indess', 'erkennt', 'irrthum'],
            ],
        ];
    }
}
