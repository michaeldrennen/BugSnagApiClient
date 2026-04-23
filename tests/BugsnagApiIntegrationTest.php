<?php

namespace MichaelDrennen\BugSnagApiClient\Tests;

use MichaelDrennen\BugSnagApiClient\BugsnagApiClient;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
class BugsnagApiIntegrationTest extends TestCase
{
    private ?BugsnagApiClient $client = null;
    private ?string $testOrgId = null;

    protected function setUp(): void
    {
        $token = getenv('BUGSNAG_AUTH_TOKEN');
        $this->testOrgId = getenv('BUGSNAG_TEST_ORG_ID');

        if (!$token || $token === 'your-real-token-here') {
            $this->markTestSkipped('BUGSNAG_AUTH_TOKEN is not set or is using the default placeholder in phpunit.xml. Skipping integration tests.');
        }

        $this->client = new BugsnagApiClient($token);
    }

    public function testRealGetOrganizations()
    {
        $response = $this->client->getOrganizations();

        $this->assertIsArray($response);
        
        // If an organization is returned, assert its basic structure
        if (count($response) > 0) {
            $this->assertArrayHasKey('id', $response[0]);
            $this->assertArrayHasKey('name', $response[0]);
        }
    }

    public function testRealGetProjects()
    {
        // Fetch organizations to get a valid ID for testing
        $organizations = $this->client->getOrganizations();
        
        if (empty($organizations)) {
            $this->markTestSkipped('No organizations found for this token. Cannot test retrieving projects.');
        }

        $realOrgId = $organizations[0]['id'];

        $response = $this->client->getProjects($realOrgId);

        $this->assertIsArray($response);
        
        // If projects are returned, assert basic structure
        if (count($response) > 0) {
            $this->assertArrayHasKey('id', $response[0]);
            $this->assertArrayHasKey('name', $response[0]);
        }
    }
}
