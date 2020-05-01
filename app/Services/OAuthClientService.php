<?php

namespace App\Services;

use App\OAuthClient;
use App\Repositories\OAuthClientRepository;
use DB;
use Illuminate\Support\Facades\Http;

class OAuthClientService
{

    public function __construct()
    {
    }

    public function getByName($oauthClientName)
    {
        return OAuthClient::where('name', $oauthClientName)->first();
    }

    public function getBySourceCompany($sourceCompanyName){
        return OAuthClient::with('SourceCompany')->whereHas('SourceCompany', function($query) use ($sourceCompanyName){
            $query->where('CompanyName', '=', $sourceCompanyName);
        })->first();
    }

    public function getLoginUrlByName($oauthClientName)
    {
        $oAuthClient = $this->getBySourceCompany($oauthClientName);

        $loginUrl = $oAuthClient->authUrl;

        $values = [
            'client_id'     => $oAuthClient->clientId,
            'response_type' => $oAuthClient->responseType,
            'redirect_uri'  => $oAuthClient->redirectUri,
            'scope'         => implode(' ', json_decode($oAuthClient->scopes, true)),
            'response_mode' => $oAuthClient->responseMode,
        ];


        $query = http_build_query($values, '', '&', PHP_QUERY_RFC3986);

        return $loginUrl . "?$query";
    }

    public function getToken($code, $oAuthClient)
    {
        $response = Http::asForm()->post(
            $oAuthClient->tokenUrl,
            [
                'client_id'     => $oAuthClient->clientId,
                'redirect_uri'  => $oAuthClient->redirectUri,
                'client_secret' => $oAuthClient->clientSecret,
                'code' => $code,
                'grant_type'    => $oAuthClient->grantType
            ]
        );

        $body = $response->getBody();

        $data = json_decode($body);

        return (array) $data;
    }
}
