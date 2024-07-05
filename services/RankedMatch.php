<?php

declare(strict_types = 1);

namespace DraftTool\Services;

use Carbon\Carbon;
use DraftTool\Lib\App;

/**
 * Service to handle ranked functions
 * @author Garma
 */
class RankedMatch
{
    /**
     * Game format IDs
     * @var int
     */
    public const GAME_FORMAT_FFA = 1;
    public const GAME_FORMAT_DUOS = 2;
    public const GAME_FORMAT_3V3 = 3;
    public const GAME_FORMAT_4V4 = 4;
    public const GAME_FORMAT_1V1 = 5;
    public const GAME_FORMAT_SURVIVAL = 6;
    
    /**
     * Creates a ranked match
     * @param int $leaderboardId
     * @param string $title
     * @param array $teams
     * @return int
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function create(int $leaderboardId, string $title, array $teams): int
    {
        App::db()->beginTransaction();
        
        App::db()->insert('leaderboard_matches', [
            'leaderboardId' => $leaderboardId,
            'title'         => $title,
            'created'       => Carbon::now()->toDateTimeString()
        ]);
        
        $matchId = (int) App::db()->lastInsertId();
        $query = 'SELECT id FROM leaderboard_players WHERE name LIKE ?';
        
        foreach ($teams as $team) {
            App::db()->insert('leaderboard_match_teams', [
                'matchId' => $matchId,
                'name'    => $team['name'],
                'bonus'   => (int) $team['bonus'],
                'penalty' => (int) $team['penalty']
            ]);
            
            $teamId = (int) App::db()->lastInsertId();
            
            foreach ($team['players'] as $player) {
                $playerId = App::db()
                    ->executeQuery($query, [$player['name']])
                    ->fetchOne()
                ;
                
                if ($playerId === false) {
                    $playerId = $this->addPlayer($player['name']);
                }
                
                App::db()->insert('leaderboard_match_players', [
                    'matchId'    => $matchId,
                    'teamId'     => $teamId,
                    'playerId'   => $playerId,
                    'raceScores' => $player['raceScores'],
                    'penalty'    => (int) $player['penalty'],
                    'totalScore' => (int) $player['totalScore'],
                    'flagCode'   => $player['flagCode']
                ]);
            }
        }
        
        App::db()->commit();
        
        return $matchId;
    }
    
    /**
     * @param string $name
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception
     */
    public function addPlayer(string $name): int
    {
        App::db()->insert('leaderboard_players', [
            'name'    => $name,
            'created' => Carbon::now()->toDateTimeString()
        ]);
        
        return (int) App::db()->lastInsertId();
    }
    
    /**
     * Calculates the gains and losses for a match
     * @param int $matchId
     * @return array
     */
    public function calculateScoreChanges(int $matchId): array
    {
    
    }
}
