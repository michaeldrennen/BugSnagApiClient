# BugSnagApiClient

A robust PHP client library designed to easily interface with the **Bugsnag Data Access API (v2)**.

## Installation

Install the package via Composer:

```bash
composer require michaeldrennen/bugsnag-api-client
```

## Authentication

To use this client, you must generate a **Personal Auth Token** from your Bugsnag dashboard.
1. Log in to Bugsnag.
2. Go to **Settings > My Account > Personal auth tokens**.
3. Generate a new token.

> **Note:** This token is different from your project-level "API Key" used for reporting errors.

## Finding IDs and Credentials

Many API endpoints require specific IDs (like an Organization ID or Project ID). While you can extract these programmatically using the API itself (e.g., calling `$client->getOrganizations()`), you can also find them manually in your Bugsnag dashboard:

*   **Organization ID:** In the Bugsnag dashboard, navigate to **Settings > Organization settings**. The Organization ID is typically the 24-character hexadecimal string found in the URL. (e.g., `https://app.bugsnag.com/settings/organizations/50baed0d9bf39c1431000003/...`)
*   **Project ID:** Navigate to **Settings > Project settings**. Similar to the organization, the Data Access API requires the 24-character hexadecimal Project ID, which can be found in the URL while viewing the project settings.
*   **Error ID & Event ID:** When viewing a specific error or event in your Bugsnag dashboard, the long alphanumeric strings present in the URL are the respective IDs.

## Basic Usage

Instantiate the client by passing your Personal Auth Token.

```php
use MichaelDrennen\BugSnagApiClient\BugsnagApiClient;

require 'vendor/autoload.php';

// Initialize the client
$token = 'your-personal-auth-token';
$client = new BugsnagApiClient($token);
```

### Retrieving Organizations

Get a list of all organizations your user account belongs to:

```php
$organizations = $client->getOrganizations();

foreach ($organizations as $org) {
    echo "Organization ID: " . $org['id'] . " - Name: " . $org['name'] . "\n";
}
```

Get details for a specific organization by its ID:

```php
$orgId = '50baed0d9bf39c1431000003';
$organization = $client->getOrganization($orgId);
```

### Retrieving Projects

Fetch all projects under a specific organization:

```php
$projects = $client->getProjects($orgId);

foreach ($projects as $project) {
    echo "Project ID: " . $project['id'] . " - Name: " . $project['name'] . "\n";
}
```

### Retrieving Errors and Events

Fetch a list of errors for a given project. You can optionally pass an array of query parameters to filter the results according to the Bugsnag API documentation.

```php
$projectId = 'project-id-string';

// Fetch open errors
$filters = [
    'base_error.status' => 'open'
];

$errors = $client->getErrors($projectId, $filters);
```

Get details for a specific error:

```php
$errorId = 'error-id-string';
$error = $client->getError($projectId, $errorId);
```

Fetch events (individual occurrences of an error):

```php
$events = $client->getEvents($projectId);
```

Get details for a specific event:

```php
$eventId = 'event-id-string';
$event = $client->getEvent($projectId, $eventId);
```

### Making Custom Requests

If you need to hit an endpoint that isn't explicitly mapped in the client, you can use the generic `get()` method.

```php
// Fetch users for an organization
$users = $client->get("organizations/{$orgId}/users");
```

## Testing

This package uses PHPUnit for testing. The test suite includes both mocked unit tests and live integration tests.

### Running Unit Tests

Unit tests mock the Guzzle HTTP client, so no real API requests are made.

```bash
composer run-script test
# or
./vendor/bin/phpunit
```

### Running Integration Tests

To run the integration tests against the live Bugsnag API:

1. Copy `phpunit.xml.dist` to `phpunit.xml`.
2. Open `phpunit.xml` and replace `your-real-token-here` with your actual Bugsnag Personal Auth Token.
3. Run PHPUnit:

```bash
./vendor/bin/phpunit
```

> **Warning:** Do not commit `phpunit.xml` to version control if it contains your real API token. The `.gitignore` file is already configured to ignore it.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
