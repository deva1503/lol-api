<?php

namespace LoLApi\Api;

use LoLApi\Result\ApiResult;

/**
 * Class MatchListApi
 *
 * @package LoLApi\Api
 * @see https://developer.riotgames.com/api/methods
 */
class MatchHistoryApi extends BaseApi
{
    const API_URL_MATCH_HISTORY_BY_SUMMONER_ID = '/api/lol/{region}/v2.2/matchhistory/{summonerId}';

    /**
     * @param int   $summonerId
     *
     * @return ApiResult
     */
    public function getMatchHistoryBySummonerId($summonerId)
    {
        $url = str_replace('{summonerId}', $summonerId, self::API_URL_MATCH_HISTORY_BY_SUMMONER_ID);
        return $this->callApiUrl($url, []);
    }
}
