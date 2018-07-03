<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DefaultControllerTest extends WebTestCase
{
    private $client;
    private $token = '9F86D081884C7D659A2FEAA0C55AD015A3BF4F1B2B0B822CD15D6C15B0F00A08';

    public function __construct()
    {
        $this->client = static::createClient(array(
            'debug' => true,
        ));
    }

    protected function createRequest($path, $data){
        return $this->client->request(
            Request::METHOD_POST,
            $path,
            array('data' => $data),
            array(),
            array('HTTP_TOKEN' => $this->token)
        );
    }

    public function testCreateTeam()
    {
        $leagueId = $this->createLeague();

        $this->createRequest(
            '/create-team',
            array(
                'name' => 'Dynamo',
                'strip' => $leagueId
            )
        );

        $res = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Dynamo', $res->name);
        $this->assertEquals($leagueId, $res->strip);

        $this->getTeamsList($res->strip);
        $this->updateTeam($res->id);
        $this->deleteTeam($res->id);

        $this->deleteLeague($res->strip);
    }

    public function updateTeam($id)
    {
        $this->createRequest(
            '/update-team',
            array(
                'id' => $id,
                'name' => 'Dnipro',
                'strip' => 1
            )
        );

        $res = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($id, $res->id);
        $this->assertContains('Dnipro', $res->name);
        $this->assertEquals(1, $res->strip);
    }

    public function deleteTeam($id)
    {
        $this->createRequest(
            '/delete-team',
            array(
                'id' => $id
            )
        );

        $res = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($res);
    }

    public function getTeamsList($leagueId)
    {
        $this->createRequest(
            '/get-teams-list',
            array(
                'league_id' => $leagueId
            )
        );

        $res = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, count($res));
    }

    public function createLeague()
    {
        $this->createRequest(
            '/create-league',
            array(
                'name' => 'High League'
            )
        );

        $res = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('High League', $res->name);

        return $res->id;
    }

    public function deleteLeague($id)
    {
        $this->createRequest(
            '/delete-league',
            array(
                'league_id' => $id
            )
        );

        $res = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($res);
    }
}
