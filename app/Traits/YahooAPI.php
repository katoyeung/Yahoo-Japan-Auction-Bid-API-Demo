<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Config;

trait YahooAPI
{
    /**
     * @return string
     */
    public function getToken()
    {
        $key = Config::get('constants.bid.token_key');
        $accessToken = '';
        if (Cache::has($key)) {
            $accessToken = Cache::get($key);
        } else {
            $client = new Client();
            $loginUrl = Config::get('app.yahoo_api_url') . '/' . Config::get('constants.bid.login_url');
            $params = [
                'username' => config('app.yahoo_api_login'),
                'password' => config('app.yahoo_api_password')
            ];

            $result = $client->post($loginUrl, [
                'form_params' => $params
            ]);

            if ($result->getStatusCode() == 200) {
                $content = $result->getBody()->getContents();
                $obj = \GuzzleHttp\json_decode($content);
                if (isset($obj->access_token)) {
                    cache([$key => $obj->access_token], now()->addSeconds(1800));
                    $accessToken = $obj->access_token;
                }
            }else{
                //todo exception handling
            }
        }

        return $accessToken;
    }

    /**
     * @param $url
     * @return mixed|null
     */
    public function request($url)
    {
        $client = new Client();
        $result = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);

        $obj = null;
        if ($result->getStatusCode() == 200) {
            $content = $result->getBody()->getContents();
            $obj = \GuzzleHttp\json_decode($content);
        }

        return $obj;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function getItem($id)
    {
        $url = Config::get('app.yahoo_api_url') . '/' . Config::get('constants.bid.item_url') . '?auctionID=' . $id;
        return $this->request($url);
    }

    /**
     * @param $category
     * @return mixed|null
     */
    public function getCategory($category)
    {
        $url = Config::get('app.yahoo_api_url') . '/' . Config::get('constants.bid.category_url') . '?category=' . $category;
        return $this->request($url);
    }

    /**
     * @param $query
     * @return mixed|null
     */
    public function search($query)
    {
        $url = Config::get('app.yahoo_api_url') . '/' . Config::get('constants.bid.search_url') . '?' . http_build_query($query);
        return $this->request($url);
    }

    /**
     * @param $query
     * @return mixed|null
     */
    public function bid($query)
    {
        $url = Config::get('app.yahoo_api_url') . '/' . Config::get('constants.bid.bid_url');
        $client = new Client();
        $result = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'form_params' => $query
        ]);
        $obj = null;
        if ($result->getStatusCode() == 200) {
            $content = $result->getBody()->getContents();
            $obj = \GuzzleHttp\json_decode($content);
        }
        return $obj;
    }
}