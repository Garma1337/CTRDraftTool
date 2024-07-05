<?php

declare(strict_types = 1);

namespace DraftTool\Controllers;

use DraftTool\Lib\App;
use DraftTool\Lib\BaseController;
use DraftTool\Services\Draft;

/**
 * Draft Controller
 * @author Garma
 */
class DraftController extends BaseController
{
    /**
     * Index Action
     */
    public function indexAction(): void
    {
    }
    
    /**
     * Action to create a new draft
     */
    public function newAction(): void
    {
        $this->template->assign([
            'baseUrl'       => App::router()->getBaseUrl(),
            'formAction'    => App::router()->generateUrl('draft', 'createDraft'),
            'modes'         => App::draft()->getModes()
        ]);
    }
    
    /**
     * Action to show a draft
     */
    public function showAction(): void
    {
        $draftId = (int) $this->request->getParam('id');
        $accessKey = $this->request->getParam('accessKey');
        
        $draft = App::draft()->findById($draftId);
        
        if ($draft !== null) {
            $teamId = null;
            
            if ($accessKey !== null) {
                $teamId = App::draft()->getTeamIdbyAccessKey($draftId, $accessKey);
            }
            
            $existingTracks = App::draft()->getExistingTracks((int) $draft['mode']);
            $availableTracks = App::draft()->getAvailableTracks($draftId);
            
            /* Bad performance */
            foreach ($existingTracks as $index => $existingTrack) {
                $isAvailable = false;
                
                foreach ($availableTracks as $availableTrack) {
                    if ($availableTrack['id'] === $existingTrack['id']) {
                        $isAvailable = true;
                        
                        break;
                    }
                }
                
                $existingTracks[$index]['isAvailable'] = $isAvailable;
            }
            
            /* Add random track to the selection */
            $existingTracks[] = [
                'id'            => 0,
                'name'          => 'Random',
                'isAvailable'   => true
            ];
            
            $this->template->assign([
                'id'                        => $draftId,
                'accessKey'                 => $accessKey,
                'draft'                     => $draft,
                'tracks'                    => $existingTracks,
                'teamId'                    => $teamId,
                'selectionThumbnailSize'    => 150,
                'trackGridThumbnailSize'    => 250,
                'currentPhase'              => App::draft()->getCurrentPhase($draftId),
                'currentTurn'               => App::draft()->getCurrentTurn($draftId)
            ]);
        } else {
            $this->template->assign('id', $draftId);
        }
    }
    
    /**
     * Displays a list of all drafts
     */
    public function listAction(): void
    {
        $limit = 10;
        
        $page = (int) $this->request->getParam('page', 1);
        if ($page < 1) {
            $page = 1;
        }
        
        $totalDrafts = App::draft()->countDrafts();
        
        $pages = (int) ceil($totalDrafts / $limit);
        if ($pages <= 0) {
            $pages = 1;
        }
        
        if ($page > $pages) {
            $page = $pages;
        }
        
        $offset = ($page - 1) * $limit;
        $drafts = App::draft()->findAll($limit, $offset);
        
        $this->template->assign([
            'drafts'    => $drafts,
            'pages'     => $pages,
            'page'      => $page
        ]);
    }
    
    /**
     * Ajax action to create a draft and return the created draft's data
     */
    public function saveAction(): void
    {
        if ($this->request->isPost()) {
            $response = [];
            
            $requestParams = [
                'mode'                  => (int) $this->request->getParam('mode'),
                'teamA'                 => $this->request->getParam('teamA'),
                'teamB'                 => $this->request->getParam('teamB'),
                'bans'                  => (int) $this->request->getParam('bans'),
                'picks'                 => (int) $this->request->getParam('picks'),
                'timeout'               => (int) $this->request->getParam('timeout'),
                'enableSpyroCircuit'    => (bool) $this->request->getParam('enableSpyroCircuit'),
                'enableHyperSpaceway'   => (bool) $this->request->getParam('enableHyperSpaceway'),
                'enableRetroStadium'    => (bool) $this->request->getParam('enableRetroStadium'),
                'splitTurboRetro'       => (bool) $this->request->getParam('splitTurboRetro'),
                'allowTrackRepeats'     => (bool) $this->request->getParam('allowTrackRepeats')
            ];
            
            $response['errors'] = App::draft()->validateParams($requestParams);
            
            if (count($response['errors']) <= 0) {
                $draft = App::draft()->create(
                    $requestParams['mode'],
                    $requestParams['teamA'],
                    $requestParams['teamB'],
                    $requestParams['bans'],
                    $requestParams['picks'],
                    $requestParams['timeout'],
                    $requestParams['enableSpyroCircuit'],
                    $requestParams['enableHyperSpaceway'],
                    $requestParams['enableRetroStadium'],
                    $requestParams['splitTurboRetro'],
                    $requestParams['allowTrackRepeats']
                );
                
                $response['success'] = 'Your draft was created successfully!';
                $response['draftData'] = $draft;
            }
            
            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
    }
    
    /**
     * Action to update a draft (ban / pick)
     */
    public function updateAction(): void
    {
        if ($this->request->isPost()) {
            $draftId = (int) $this->request->getParam('draftId');
            $draft = App::draft()->findById($draftId);
            
            /* Do nothing if draft doesn't exist */
            if ($draft === null) {
                return;
            }
            
            $accessKey = $this->request->getParam('accessKey');
            $postedTeamId = (int) $this->request->getParam('teamId');
            $trackId = (int) $this->request->getParam('trackId');
            
            /* Double check - just for safety */
            $teamId = App::draft()->getTeamIdbyAccessKey($draftId, $accessKey);
            if ($teamId !== $postedTeamId) {
                return;
            }
            
            /* Check if a random track was selected */
            if ($trackId === 0) {
                $trackId = App::draft()->getRandomAvailableTrack($draftId);
            }
            
            $currentPhase = App::draft()->getCurrentPhase($draftId);
            
            if ($currentPhase === Draft::PHASE_BAN) {
                App::draft()->banTrack($draftId, $trackId, $postedTeamId);
            } else if ($currentPhase === Draft::PHASE_PICK) {
                App::draft()->pickTrack($draftId, $trackId, $postedTeamId);
            }
            
            $this->redirect('draft', 'show', ['id' => $draftId, 'accessKey' => $accessKey]);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
    }
}
