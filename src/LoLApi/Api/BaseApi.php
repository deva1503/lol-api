<?php

namespace LoLApi\Api;

use GuzzleHttp\Exception\ClientException;
use LoLApi\ApiClient;
use LoLApi\Handler\ClientExceptionHandler;
use LoLApi\Result\ApiResult;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseApi
 *
 * @package LoLApi\Api
 */
abstract class BaseApi
{
    /**
     * @var ApiClient
     */
    protected $apiClient;

    /**
     * @param ApiClient $apiClient
     */
    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param string $url
     * @param array  $queryParameters
     * @param bool   $global
     *
     * @return ApiResult|null
     */
    protected function callApiUrl($url, array $queryParameters = [], $global = false)
    {
        $baseUrl         = $global ? $this->apiClient->getGlobalUrl() : '';
        $url             = $baseUrl . str_replace('{region}', $this->apiClient->getRegion(), $url);
        $queryParameters = array_merge(['api_key' => $this->apiClient->getApiKey()], $queryParameters);
        $fullUrl         = $this->buildUri($url, $queryParameters);

        if ($this->apiClient->getCacheProvider()->contains($fullUrl)) {
            return $this->buildApiResult($fullUrl, json_decode($this->apiClient->getCacheProvider()->fetch($fullUrl), true), true);
        }

        try {
            $response = $this->apiClient->getHttpClient()->get($url, ['query' => $queryParameters]);

            $r_	= $this->buildApiResult($fullUrl, json_decode((string) $response->getBody(), true), false, $response);
            $this->apiClient->cacheApiResult($r_);
            return	$r_;

        } catch (ClientException $e) {
            throw (new ClientExceptionHandler())->handleClientException($e);
        }
    }

    /**
     * @param string $url
     * @param array  $queryParameters
     * @param bool   $global
     *
     * @return string
     */
    protected function buildUri($url, array $queryParameters, $global = false)
    {
        $baseUrl = $global ? $this->apiClient->getGlobalUrl() : $this->apiClient->getBaseUrlWithRegion();

        return $baseUrl . $url . '?' . http_build_query($queryParameters);
    }

    /**
     * @param string                 $fullUrl
     * @param array                  $result
     * @param bool                   $fetchedFromCache
     * @param ResponseInterface|null $response
     *
     * @return $this
     */
    protected function buildApiResult($fullUrl, array $result, $fetchedFromCache, ResponseInterface $response = null)
    {
        return (new ApiResult())
            ->setResult($result)
            ->setUrl($fullUrl)
            ->setHttpResponse($response)
            ->setFetchedFromCache($fetchedFromCache);
    }
}
