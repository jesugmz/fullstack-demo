<?php declare(strict_types = 1);

namespace Joking\Application;

use Joking\Domain\Joke;

interface MailClientInterface
{
    public function send(string $userMail, Joke $joke): bool;
}
