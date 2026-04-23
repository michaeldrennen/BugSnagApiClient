<?php

namespace MichaelDrennen\BugSnagApiClient\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\BugSnagApiClient\BugsnagApiClient;
use PHPUnit\Framework\TestCase;

class BugsnagApiClientTest extends TestCase
{
    private function getClient(array $mockedResponses, string $token = 'test-token', ?string $baseUrl = null): BugsnagApiClient
    {
        $mock = new MockHandler($mockedResponses);
        $handlerStack = HandlerStack::create($mock);
        
        return new BugsnagApiClient($token, $baseUrl, ['handler' => $handlerStack]);
    }

    public function testGetOrganizations()
    {
        $expectedBody = json_encode([['id' => 'org_123', 'name' => 'My Org']]);
        $client = $this->getClient([
            new Response(200, [], $expectedBody)
        ]);

        $response = $client->getOrganizations();

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals('org_123', $response[0]['id']);
        $this->assertEquals('My Org', $response[0]['name']);
    }

    public function testGetOrganization()
    {
        $expectedBody = json_encode(['id' => 'org_123', 'name' => 'My Org']);
        $client = $this->getClient([
            new Response(200, [], $expectedBody)
        ]);

        $response = $client->getOrganization('org_123');

        $this->assertIsArray($response);
        $this->assertEquals('org_123', $response['id']);
    }

    public function testGetProjects()
    {
        $expectedBody = json_encode([['id' => 'proj_123', 'name' => 'My Project']]);
        $client = $this->getClient([
            new Response(200, [], $expectedBody)
        ]);

        $response = $client->getProjects('org_123');

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals('proj_123', $response[0]['id']);
    }

    public function testGetErrors()
    {
        $expectedBody = json_encode([['id' => 'err_123', 'class' => 'Exception']]);
        $client = $this->getClient([
            new Response(200, [], $expectedBody)
        ]);

        $response = $client->getErrors('proj_123', ['base_error.status' => 'open']);

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals('err_123', $response[0]['id']);
    }

    public function testGetError()
    {
        $expectedBody = json_encode(['id' => 'err_123', 'class' => 'Exception']);
        $client = $this->getClient([
            new Response(200, [], $expectedBody)
        ]);

        $response = $client->getError('proj_123', 'err_123');

        $this->assertIsArray($response);
        $this->assertEquals('err_123', $response['id']);
    }

    public function testGetEvents()
    {
        $expectedBody = json_encode([['id' => 'evt_123', 'exceptions' => []]]);
        $client = $this->getClient([
            new Response(200, [], $expectedBody)
        ]);

        $response = $client->getEvents('proj_123');

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals('evt_123', $response[0]['id']);
    }

    public function testGetEvent()
    {
        $expectedBody = json_encode(['id' => 'evt_123', 'exceptions' => []]);
        $client = $this->getClient([
            new Response(200, [], $expectedBody)
        ]);

        $response = $client->getEvent('proj_123', 'evt_123');

        $this->assertIsArray($response);
        $this->assertEquals('evt_123', $response['id']);
    }
}
