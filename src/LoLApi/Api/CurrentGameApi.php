<?php

namespace LoLApi\Api;

use LoLApi\Result\ApiResult;

/**
 * Class CurrentGameApi
 *
 * @package LoLApi\Api
 * @see https://developer.riotgames.com/api/methods
 */
class CurrentGameApi extends BaseApi
{
    const API_URL_CURRENT_GAME_BY_SUMMONER_ID = '/observer-mode/rest/consumer/getSpectatorGameInfo/{platformId}/{summonerId}';

    /**
     * @param string $platformId
     * @param string $summonerId
     *
     * @return ApiResult
     */
    public function getCurrentGameByPlatformIdAndSummonerId($summonerId)
    {
		$pi	= array(
			'na'	=> 'NA1',
			'euw'	=> 'EUW1',
			'eune'	=> 'EUN1',
			'kr'	=> 'KR',
			'oce'	=> 'OC1',
			'br'	=> 'BR1',
			'lan'	=> 'LA1',
			'las'	=> 'LA2',
			'ru'	=> 'RU',
			'tr'	=> 'TR1',
			'pbe'	=> 'PBE1');

        $url = str_replace('{platformId}', $pi[ $this->apiClient->getRegion() ], self::API_URL_CURRENT_GAME_BY_SUMMONER_ID);
        $url = str_replace('{summonerId}', $summonerId, $url);
        return $this->callApiUrl($url, []);
    }
}
