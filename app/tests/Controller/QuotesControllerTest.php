<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuotesControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Stocks Quotes', $client->getResponse()->getContent());
    }

    public function testGetQuotes(): void
    {
        $client = static::createClient();

        // Fail
        $client->request('GET', '/quotes');
        $response = $client->getResponse();
        $parsedResponse = json_decode($response->getContent(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('status', $parsedResponse);
        $this->assertArrayHasKey('errors', $parsedResponse);
        $this->assertEquals('validation-error', $parsedResponse['status']);
        foreach ($parsedResponse['errors'] as $errors) {
            $this->assertEquals('This value should not be blank.', $errors[0]);
        }

        //Success
        $client->request('GET', '/quotes', [
            'company-symbol' => 'AAPL',
            'start-date' => '2003-01-01',
            'end-date' => '2003-03-06',
            'email' => 'foo@bar.com'
        ]);

        $response = $client->getResponse();
        $parsedResponse = json_decode($response->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(44, count($parsedResponse));
        $this->assertEquals('14.36', $parsedResponse[0]['Open']);
    }

    public function testGetCompany(): void
    {
        $client = static::createClient();

        // Success
        $client->request('GET', '/companies/AAPL');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $parsedResponse = json_decode($response->getContent(), true);
        $this->assertEquals('Apple Inc.', $parsedResponse[1]);

        // Fail
        $client->request('GET', '/companies/!y7%');
        $this->assertTrue($client->getResponse()->isNotFound());
    }
}