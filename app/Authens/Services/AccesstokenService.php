<?php
namespace Authens\Services;

use Authens\Repositories\AuthenRepositories;
use Core\Libraries\AccessToken;

class AccesstokenService extends AuthenRepositories
{
    protected $accessToken;

    private $refreshKey = "bAqzSDl0MYbMkUFUCXEdpaldJFMRu8r0xcc17Vld25CjUXjGrU6ZInsNYGuNK0AW";
    private $delimeter  = "::";

    public function __construct()
    {
        $this->AccessToken = new AccessToken();
        parent::__construct();
    }
    //==== Start: Define variable ====//
    
    //==== End: Define variable ====//


    //==== Start: Support method ====//

    //Method for get client public key
    protected function getClientKey($filename)
    {
        return $this->AccessToken->openKeyFile($filename, 'client_key');
    }

    //Method for get client public key
    protected function getServerPrivateKey($serverNo)
    {
        return $this->AccessToken->openKeyFile("private$serverNo.pem", 'server_key');
    }

    //Method for get server public key
    protected function getServerPublicKey($serverNo)
    {
        return $this->AccessToken->openKeyFile("public$serverNo.pem", 'server_key');
    }

    //Method for random server key no
    protected function randomServerKeyNo()
    {
        return rand(1, 5);
    }

    //method for validate data
    protected function validateEncrypted($encrypted, $clientKey, $appKeyword)
    {
        //create crypto classd
        $crypto    = new \Authens\Services\CryptoService();
        // $keyword = $this->AccessToken->decryptString($encrypted, $clientKey);
        $keyword = $crypto->decrypt($encrypted, $clientKey, true);

        return ($appKeyword == $keyword);
    }

    //Method for create refresh token
    protected function createRefreshToken($appId, $serverNo)
    {
        //create crypto classd
        $crypto       = new \Authens\Services\CryptoService();
        //define message
        $message      = $appId.$this->delimeter.$serverNo;
        $refreshToken = $crypto->encrypt($message, $this->refreshKey, true);
        
        return $refreshToken;
    }

    //Method for check refresh token
    protected function checkRefreshToken($refreshToken)
    {
        //create crypto classd
        $crypto    = new \Authens\Services\CryptoService();
        //decrypt message
        $plainText = $crypto->decrypt($refreshToken, $this->refreshKey, true);
        
        $datas     = explode($this->delimeter, $plainText);

        return [
            'app_id'    => $datas[0],
            'server_no' => (isset($datas[1]))?$datas[1]:null,
        ];
    }
    //==== End: Support method ====//


    //==== Stat: Main method ====//
    //Method for get access token
    public function requestAccessToken($appId, $encrypted)
    {
        
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        //get data
        $datas = $this->getDataById($appId);

        if (empty($datas)) {
            $outputs['success'] = false;
            $outputs['message'] = 'dataNotFound';
            return $outputs;
        }

        //client key path
        $clientPath = $this->config->path->client_key . $datas->client_key;
        
        //get server key no
        $serverKeyNo = $this->randomServerKeyNo();

        //get client key
        $clientKey = $datas->key64bit;
        // $clientKey = $this->getClientKey($datas->client_key);

        //validate data
        $valid = $this->validateEncrypted($encrypted, $clientKey, $datas->keyword);
        if (!$valid) {
            $outputs['success'] = false;
            $outputs['message'] = 'invalidKeyword';
            return $outputs;
        }

        //get server key
        $serverKey = $this->getServerPublicKey($serverKeyNo);
        $lifetime  = $datas->lifetime;
        $time = "+$lifetime hour";
        //generate data
        $outputs['data'] = json_encode([
            "server_no"     => $serverKeyNo,
            "access_token"  => $this->AccessToken->encryptString(date("Y-m-d H", strtotime($time)), $serverKey),
            "expire_time"   => $datas->lifetime*60*60,
            "refresh_token" => $this->createRefreshToken($appId, $serverKeyNo),
        ]);

        return $outputs;
    }

    //Method for check access token
    public function checkAccessToken($token, $serverNo)
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        //get server key
        $serverKey = $this->getServerPrivateKey($serverNo);
        //decrypt
        $time       = $this->AccessToken->decryptString($token, $serverKey);


        if (empty($time) || ($time < date("Y-m-d H"))) {
            $outputs['success'] = false;
            $outputs['message'] = 'invalidAccessToken';
            return $outputs;
        }

        return $outputs;
    }

    //Method for refresh token process
    public function refreshTokenProcess($refreshToken)
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        //check refresh token
        $refreshDatas = $this->checkRefreshToken($refreshToken);

        if (empty($refreshDatas['app_id']) || empty($refreshDatas['server_no'])) {
            $outputs['success'] = false;
            $outputs['message'] = 'invalidRefreshToken';
            return $outputs;
        }

        //get data
        $datas = $this->getDataById($refreshDatas['app_id']);

        if (empty($datas)) {
            $outputs['success'] = false;
            $outputs['message'] = 'dataNotFound';
            return $outputs;
        }

        //generate new token
        //get server key
        $serverKey = $this->getServerPublicKey($refreshDatas['server_no']);
        $lifetime  = $datas->lifetime;
        $time = "+$lifetime hour";
        
        //generate data
        $outputs['data'] = json_encode([
            "server_no"     => $refreshDatas['server_no'],
            "access_token"  => $this->AccessToken->encryptString(date("Y-m-d H", strtotime($time)), $serverKey),
            "expire_time"   => $datas->lifetime*60*60,
            "refresh_token" => $this->createRefreshToken($refreshDatas['app_id'], $refreshDatas['server_no']),
        ]);

        return $outputs;

    }
    //==== End: Main method ====//
}