<?php

declare(strict_types = 1);

namespace DraftTool\Controllers;

use DraftTool\Attributes\Route;
use DraftTool\Lib\App;
use DraftTool\Lib\BaseController;
use DraftTool\Services\Request;

/**
 * Controller to manage leaderboards
 */
class LeaderboardController extends BaseController
{
    /**
     * Index action
     */
    #[Route('/leaderboard/index', [Request::METHOD_GET])]
    public function indexAction(): void
    {
        $this->smarty->assign('leaderboards', App::leaderboard()->findAll());
    }
    
    /**
     * Show action
     */
    #[Route('/leaderboard/show', [Request::METHOD_GET])]
    public function showAction(): void
    {
        $leaderboardId = (int) $this->request->getParam('id');
        $this->smarty->assign('leaderboard', App::leaderboard()->findById($leaderboardId));
        
        App::leaderboard()->calculateRanks($leaderboardId);
    }
}
