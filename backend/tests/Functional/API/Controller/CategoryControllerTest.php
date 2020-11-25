<?php declare(strict_types = 1);

namespace Joking\Tests\Functional\API\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testSmokeTest()
    {
        $want = '["animal","career","celebrity","dev","explicit","fashion","food","history","money","movie","music","political","religion","science","sport","travel"]';

        $client = static::createClient();
        $client->request('GET', '/category');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($want, $client->getResponse()->getContent());
    }
}
