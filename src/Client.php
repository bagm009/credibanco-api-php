<?php

namespace Saulmoralespa\Credibanco;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class Client
{
    const SANDBOX_API_BASE_URL = 'https://ecouat.credibanco.com/payment/rest/';
    const API_BASE_URL = 'https://eco.credibanco.com/proxy/rest/';

    protected static $_sandbox = false;
    private $user;
    private $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function sandboxMode($status = false)
    {
        self::$_sandbox = $status;
    }

    protected function client()
    {
        return new GuzzleClient([
            "base_uri" => $this->getBaseUrl()
        ]);
    }

    public function getBaseUrl()
    {
        return self::$_sandbox ? self::SANDBOX_API_BASE_URL : self::API_BASE_URL;
    }

    public function register(array $params)
    {
        try{
            $response = $this->client()->post("register.do", [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => array_merge([
                    'userName' => $this->user,
                    'password' => $this->password
                ], $params)
            ]);

            return self::responseJson($response);
        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    public function getOrderStatusExtended(array $params)
    {
        try{
            $response = $this->client()->post("getOrderStatusExtended.do", [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => array_merge([
                    'userName' => $this->user,
                    'password' => $this->password
                ], $params)
            ]);

            return self::responseJson($response);
        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    public static function responseJson($response)
    {
        return \GuzzleHttp\json_decode(
            $response->getBody()->getContents(), true
        );
    }

}