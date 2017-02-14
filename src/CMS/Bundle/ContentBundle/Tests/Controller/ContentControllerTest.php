<?php

namespace CMS\Bundle\ContentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContentControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/login');
        $form = $crawler->selectButton('Valider')->form(array(
            '_username'  => 'admin',
            '_password' => 'admin',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/content/10/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/content/");

        $crawler = $client->request('GET', '/admin/content/new/post');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/content/new/post");
        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'content[title]'  => 'Test',
            'content[url]' => 'test',
            'content[description]' => "Donec sodales sagittis magna. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. In auctor lobortis lacus. Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Pellentesque egestas, neque sit amet convallis pulvinar, justo nulla eleifend augue, ac auctor orci leo non est.Etiam iaculis nunc ac metus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Cras non dolor. Maecenas egestas arcu quis ligula mattis placerat. Phasellus volutpat, metus eget egestas mollis, lacus lacus blandit dui, id egestas quam mauris ut lacus.",
            'content[chapo]' => "Donec sodales sagittis magna. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. In auctor lobortis lacus.",
            'content[language]' => 1,
            'content[categories]' => array(2)
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Test")')->count(), 'Missing element html:contains("Test")');
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Non catégorisé")')->count(), 'Missing element td:contains("Non catégorisé")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'content[title]'  => 'Foo',
            'content[url]' => 'foo',
            'content[categories]' => array(3)
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Foo")')->count(), 'Missing element td:contains("Foo")');
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Endroits à visiter")')->count(), 'Missing element td:contains("Endroits à visiter")');

        // Delete the entity
//        $client->submit($crawler->selectButton('Delete')->form());
//        $crawler = $client->followRedirect();
//
//        // Check the entity has been delete on the list
//        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
}
