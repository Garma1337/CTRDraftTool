<?php

declare(strict_types = 1);

namespace DraftTool\Services;

use DraftTool\Lib\App;

/**
 * Service to handle leaderboard functions
 * @author Garma
 */
class Leaderboard
{
    /**
     * Returns a list of all leaderboards
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findAll(): array
    {
        $leaderboards = [];
        
        $ids = App::db()
            ->executeQuery('SELECT id FROM leaderboards')
            ->fetchFirstColumn()
        ;
        
        foreach ($ids as $id) {
            $leaderboards[] = $this->findById((int) $id);
        }
        
        return $leaderboards;
    }
    
    /**
     * Returns leaderboard data by a given ID
     * @param int $id
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findById(int $id): array
    {
        $leaderboard = App::db()
            ->executeQuery('SELECT * FROM leaderboards WHERE id = ?', [$id])
            ->fetchAssociative()
        ;
        
        $matchQuery = 'SELECT
                        lm.*,
                        gf.badge
                       FROM leaderboard_matches lm
                       INNER JOIN game_formats gf
                       ON lm.formatId = gf.id
                       WHERE leaderboardId = ?';
        
        $rankQuery = 'SELECT *
                      FROM leaderboard_ranks
                      WHERE leaderboardId = ?';
        
        $matchPlayerQuery = 'SELECT *
                        FROM leaderboard_players
                        WHERE id IN (
                            SELECT playerId
                            FROM leaderboard_match_players lmp
                            INNER JOIN leaderboard_matches lm
                            ON lmp.matchId = lm.id
                            WHERE leaderboardId = ?
                        )';
        
        $playerQuery = 'SELECT *
                        FROM leaderboard_match_players lmp
                        INNER JOIN leaderboard_players lp
                        ON lmp.playerId = lp.id
                        WHERE matchId = ?';
        
        $leaderboard['matches'] = App::db()
            ->executeQuery($matchQuery, [$id])
            ->fetchAllAssociative()
        ;
        
        $leaderboard['ranks'] = App::db()
            ->executeQuery($rankQuery, [$id])
            ->fetchAllAssociative()
        ;
        
        $leaderboard['players'] = App::db()
            ->executeQuery($matchPlayerQuery, [$id])
            ->fetchAllAssociative()
        ;
        
        foreach ($leaderboard['matches'] as $index => $match) {
            $leaderboard['matches'][$index]['teams'] = App::db()
                ->executeQuery('SELECT * FROM leaderboard_match_teams WHERE matchId = ?', [$match['id']])
                ->fetchAllAssociative()
            ;
            
            $leaderboard['matches'][$index]['players'] = App::db()
                ->executeQuery($playerQuery, [$match['id']])
                ->fetchAllAssociative()
            ;
        }
        
        return $leaderboard;
    }
    
    /**
     * Calculates all player ranks
     * @param int $leaderboardId
     * @return int
     */
    public function calculateRanks(int $leaderboardId): array
    {
        $query = 'SELECT
                    lp.id,
                    lp.name,
                    lp.created,
                    l.initialRating AS rating
                  FROM leaderboard_match_players lmp
                  INNER JOIN leaderboard_players lp
                  ON lmp.playerId = lp.id
                  INNER JOIN leaderboard_matches lm
                  ON lm.id = lmp.matchId
                  INNER JOIN leaderboards l
                  ON lm.leaderboardId = l.id
                  WHERE leaderboardId = ?';
        
        $ranks = App::db()
            ->executeQuery($query, [$leaderboardId])
            ->fetchAllAssociative()
        ;
        
        $query = 'SELECT *
                  FROM leaderboard_matches lm
                  INNER JOIN game_formats gf
                  ON lm.formatId = gf.id
                  WHERE leaderboardId = ?
                  ORDER BY lm.created';
        
        $matches = App::db()->executeQuery($query, [$leaderboardId])->fetchAllAssociative();
        
        foreach ($matches as $match) {
            $match['players'] = App::db()
                ->executeQuery('SELECT * FROM leaderboard_match_players WHERE matchId = ?', [$match['id']])
                ->fetchAllAssociative()
            ;
            
            $standings = [];
            
            foreach ($match['players'] as $index => $player) {
                $match['players'][$index]['team'] = App::db()
                    ->executeQuery('SELECT * FROM leaderboard_match_teams WHERE matchId = ?', [$match['id']])
                    ->fetchAssociative()
                ;
    
                $player['data'] = App::db()
                    ->executeQuery('SELECT * FROM leaderboard_players WHERE id = ?', [$player['id']])
                    ->fetchAssociative()
                ;
    
                $standings[] = [
                    'player' => $player['data']['name'],
                    'score'  => $player['totalScore'] - $player['penalty']
                ];
            }
            
            usort($standings, function($a, $b) {
                return $b['score'] - $a['score'];
            });
            
            
        }
        
        return $ranks;
    }
}
