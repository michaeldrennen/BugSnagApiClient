<?php

namespace MichaelDrennen\BugSnagApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BugsnagApiClient
{
    private Client $httpClient;
    private string $baseUrl = 'https://api.bugsnag.com';

    public function __construct(string $personalAuthToken, ?string $baseUrl = null, array $guzzleOptions = [])
    {
        if ($baseUrl) {
            $this->baseUrl = rtrim($baseUrl, '/');
        }

        $options = array_merge([
            'base_uri' => $this->baseUrl . '/',
            'headers' => [
                'Authorization' => 'token ' . $personalAuthToken,
                'X-Version' => '2',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ], $guzzleOptions);

        $this->httpClient = new Client($options);
    }

    /**
     * Send a GET request to the Bugsnag API
     *
     * @param string $endpoint
     * @param array $query
     * @return array
     * @throws GuzzleException
     */
    public function get(string $endpoint, array $query = []): array
    {
        $response = $this->httpClient->get(ltrim($endpoint, '/'), [
            'query' => $query
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get a list of organizations
     *
     * @return array
     * @throws GuzzleException
     */
    public function getOrganizations(): array
    {
        return $this->get('organizations');
    }

    /**
     * Get an organization by ID
     *
     * @param string $organizationId
     * @return array
     * @throws GuzzleException
     */
    public function getOrganization(string $organizationId): array
    {
        return $this->get("organizations/{$organizationId}");
    }

    /**
     * Get a list of projects for an organization
     *
     * @param string $organizationId
     * @return array
     * @throws GuzzleException
     */
    public function getProjects(string $organizationId): array
    {
        return $this->get("organizations/{$organizationId}/projects");
    }

    /**
     * Get a list of errors for a project
     *
     * @param string $projectId
     * @param array $filters Additional filters
     * @return array
     * @throws GuzzleException
     */
    public function getErrors(string $projectId, array $filters = []): array
    {
        return $this->get("projects/{$projectId}/errors", $filters);
    }

    /**
     * Get details of a specific error
     *
     * @param string $projectId
     * @param string $errorId
     * @return array
     * @throws GuzzleException
     */
    public function getError(string $projectId, string $errorId): array
    {
        return $this->get("projects/{$projectId}/errors/{$errorId}");
    }

    /**
     * Get a list of events for a project
     *
     * @param string $projectId
     * @param array $filters Additional filters
     * @return array
     * @throws GuzzleException
     */
    public function getEvents(string $projectId, array $filters = []): array
    {
        return $this->get("projects/{$projectId}/events", $filters);
    }

    /**
     * Get details of a specific event
     *
     * @param string $projectId
     * @param string $eventId
     * @return array
     * @throws GuzzleException
     */
    public function getEvent(string $projectId, string $eventId): array
    {
        return $this->get("projects/{$projectId}/events/{$eventId}");
    }
}
