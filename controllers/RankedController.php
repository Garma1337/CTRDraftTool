<?php

declare(strict_types = 1);

namespace DraftTool\Controllers;

use DraftTool\Attributes\Route;
use DraftTool\Lib\App;
use DraftTool\Lib\BaseController;
use DraftTool\Services\Request;

/**
 * Ranked Match Controller
 * @author Garma
 */
class RankedController extends BaseController
{
    /**
     * Action to enter a new ranked result
     */
    #[Route('/ranked/new', [Request::METHOD_GET])]
    public function newAction(): void
    {
        $success = $this->request->getParam('success');
        $error = $this->request->getParam('error');
        
        $this->smarty->assign('success', $success);
        $this->smarty->assign('error', $error);
        $this->smarty->assign('leaderboardId', $this->request->getParam('leaderboardId'));
    }
    
    /**
     * Action to save a ranked result
     */
    #[Route('/ranked/save', [Request::METHOD_POST])]
    public function saveAction(): void
    {
        if ($this->request->isPost()) {
            $result = json_decode($this->request->getParam('result'), true);
            $leaderboardId = (int) $this->request->getParam('leaderboardId');
            
            if (empty($result)) {
                $this->redirect('ranked', 'new', ['error' => 'You need to enter the table.']);
            } else {
                $teams = [];
                
                if (count($result['clans']) > 1) {
                    $title = $result['gamemode'];
                } else {
                    $title = $result['clans'][0]['tag'] . ' ' . $result['clans'][0]['name'];
                }
                
                foreach ($result['clans'] as $clan) {
                    $players = [];
                    
                    $team = [
                        'bonus'     => $clan['bonuses'],
                        'penalty'   => $clan['penalty']
                    ];
                    
                    if (count($result['clans']) > 1) {
                        $team['name'] = $clan['tag'];
                    } else {
                        $team['name'] = 'FFA';
                    }
                    
                    foreach ($clan['players'] as $clanPlayer) {
                        $player = [
                            'name'       => $clanPlayer['name'],
                            'raceScores' => json_encode($clanPlayer['gpScores']),
                            'penalty'    => $clanPlayer['penalties'],
                            'totalScore' => $clanPlayer['totalScore'],
                            'flagCode'   => $clanPlayer['flag'],
                        ];
                        
                        $players[] = $player;
                    }
                    
                    $team['players'] = $players;
                    $teams[] = $team;
                }
                
                App::rankedMatch()->create($leaderboardId, $title, $teams);
                $this->redirect('ranked', 'new', ['success' => 'The ranked match has been saved successfully.']);
            }
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
    }
}
