<?php

namespace LoLApi\Api;

use LoLApi\Result\ApiResult;

/**
 * Class LeagueApi
 *
 * @package LoLApi\Api
 * @see https://developer.riotgames.com/api/methods
 */
class LeagueApi extends BaseApi
{
    const API_URL_LEAGUES_BY_SIDS			= '/api/lol/{region}/v2.5/league/by-summoner/{summonerIds}';
    const API_URL_LEAGUES_ENTRY_BY_SIDS		= '/api/lol/{region}/v2.5/league/by-summoner/{summonerIds}/entry';
    const API_URL_LEAGUES_BY_TEAMIDS		= '/api/lol/{region}/v2.5/league/by-team/{teamIds}';
    const API_URL_LEAGUES_ENTRY_BY_TEAMIDS	= '/api/lol/{region}/v2.5/league/by-team/{teamIds}/entry';
    const API_URL_LEAGUES_CHALLENGER		= '/api/lol/{region}/v2.5/league/challenger';
    const API_URL_LEAGUES_MASTER			= '/api/lol/{region}/v2.5/league/master';

    public function getLeaguesBySummonersIds(array $summonerIds)
    {
        $url = str_replace('{summonerIds}', implode(',', $summonerIds), self::API_URL_LEAGUES_BY_SIDS);
        return $this->callApiUrl($url, []);
    }
    public function getLeaguesEntryBySummonersIds(array $summonerIds)
    {
        $url = str_replace('{summonerIds}', implode(',', $summonerIds), self::API_URL_LEAGUES_ENTRY_BY_SIDS);
        return $this->callApiUrl($url, []);
    }

    public function getLeaguesByTeamsIds(array $teamIds)
    {
        $url = str_replace('{teamIds}', implode(',', $teamIds), self::API_URL_LEAGUES_BY_TEAMIDS);
        return $this->callApiUrl($url, []);
    }
    public function getLeaguesEntryByTeamsIds(array $teamIds)
    {
        $url = str_replace('{teamIds}', implode(',', $teamIds), self::API_URL_LEAGUES_BY_TEAMIDS);
        return $this->callApiUrl($url, []);
    }

    public function getLeaguesChallenger()
    {
        return $this->callApiUrl(self::API_URL_LEAGUES_CHALLENGER, []);
    }
    public function getLeaguesMaster()
    {
        return $this->callApiUrl(self::API_URL_LEAGUES_MASTER, []);
    }

}
