<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase {

    public function testIndex () {

        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback!');

    }


    public function testCommentSubmission () {
        
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/conference/moscow-2025');
        

        $form = $crawler->selectButton('Submit')->form();

        $form['comment[author]'] = 'Roma';
        $form['comment[text]'] = 'That was cool !';
        $form['comment[email]'] = 'Rk@mail.ru';


        $crawler = $client->submit($form);

    }
}