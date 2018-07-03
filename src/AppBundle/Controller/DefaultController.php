<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Team;
use AppBundle\Entity\League;

/**
 * @Route(service="app.default_controller")
 */

class DefaultController extends Controller
{
    private $data;
    private $token = '9F86D081884C7D659A2FEAA0C55AD015A3BF4F1B2B0B822CD15D6C15B0F00A08';

    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();

        $this->data = $request->request->get('data');
        $token = $request->headers->get('token');

        if($token != $this->token){
            throw new \Exception('Access for token denied');
        }
    }

    /**
     * @Route("/get-teams-list", name="get_teams_list")
     */
    public function getTeamsListAction()
    {
        if(!isset($this->data['league_id'])){
            throw new \Exception('Please provide league id');
        }

        $leagueTeams = $this->getDoctrine()->getRepository(Team::class)->findBy(array('strip' => $this->data['league_id']));

        return new JsonResponse(
            $leagueTeams
        );

    }

    /**
     * @Route("/create-league", name="create_league")
     */
    public function createLeagueAction()
    {
        if(!isset($this->data['name'])){
            throw new \Exception('Please provide league name');
        }

        $newLeague = $this->getDoctrine()->getRepository(League::class)->createLeague($this->data);

        return new JsonResponse(
            array(
                'id' => $newLeague->getId(),
                'name' => $newLeague->getName()
            )
        );

    }

    /**
     * @Route("/delete-league", name="delete_league")
     */
    public function deleteLeagueAction()
    {
        if(!isset($this->data['league_id'])){
            throw new \Exception('Please provide league id');
        }

        $this->getDoctrine()->getRepository(League::class)->deleteLeague($this->data);

        return new JsonResponse(
            true
        );
    }

    /**
     * @Route("/create-team", name="create_team")
     */
    public function createTeamAction()
    {
        if(!isset($this->data['name']) || !isset($this->data['strip'])){
            throw new \Exception('Please provide team name and league id');
        }

        $newTeam = $this->getDoctrine()->getRepository(Team::class)->createTeam($this->data);

        return new JsonResponse(
            array(
                'id' => $newTeam->getId(),
                'name' => $newTeam->getName(),
                'strip' => $newTeam->getStrip()
            )
        );

    }

    /**
     * @Route("/update-team", name="update_team")
     */
    public function updateTeamAction()
    {
        if(!isset($this->data['id']) || !isset($this->data['name']) || !isset($this->data['strip'])){
            throw new \Exception('Please provide team id, name and league id');
        }

        $team = $this->getDoctrine()->getRepository(Team::class)->findOneById($this->data['id']);

        if(!$team){
            throw new \Exception('Please provide correct team id');
        }

        $newTeam = $this->getDoctrine()->getRepository(Team::class)->updateTeam($team, $this->data);

        return new JsonResponse(
            array(
                'id' => $newTeam->getId(),
                'name' => $newTeam->getName(),
                'strip' => $newTeam->getStrip()
            )
        );

    }

    /**
     * @Route("/delete-team", name="delete_team")
     */
    public function deleteTeamAction()
    {
        if(!isset($this->data['id'])){
            throw new \Exception('Please provide team id');
        }

        $this->getDoctrine()->getRepository(Team::class)->deleteTeam($this->data);

        return new JsonResponse(
            true
        );

    }
}
