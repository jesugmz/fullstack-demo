<?php declare(strict_types = 1);

namespace Joking\Infrastructure\Mailing;

use Joking\Application\MailClientInterface;
use Joking\Domain\Joke;

class MailgunClient implements MailClientInterface
{
    public function send(string $userMail, Joke $joke): bool
    {
        // TO-DO in another life
    }
}
