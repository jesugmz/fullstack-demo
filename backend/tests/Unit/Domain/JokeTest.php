<?php declare(strict_types = 1);

namespace Joking\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Joking\Domain\Joke;

class JokeTest extends TestCase
{
    public function testJsonSerialize()
    {
        $input = Joke::createFromRaw(
            '123',
            'http:://sample.tld',
            'sample value'
        );

        $want = (array) json_decode('{"id":"123","url":"http:://sample.tld","value":"sample value","category":""}');

        $this->assertEquals($want, $input->jsonSerialize());
    }
}
