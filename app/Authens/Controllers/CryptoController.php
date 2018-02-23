<?php
namespace Authens\Controllers;

use Core\Controllers\ControllerBase;
use Authens\Services\CryptoService;


/**
 * Display the default index page.
 */
class CryptoController extends ControllerBase
{
    //==== Start: Define variable ====//
    private $encrypt_uri = '/api/encrypt';
    private $decrypt_uri = '/api/decrypt';
    private $cryptoService;

    private $encryptRule = [
        [
            'type'   => 'required',
            'fields' => ['plain_text'],
        ]
    ];

    private $decryptRule = [
        [
            'type'   => 'required',
            'fields' => ['encrypted'],
        ]
    ];
    // private $modelName;
    // private $schemaName;

    //==== End: Define variable ====//

    //==== Start: Support method ====//
    //Method for initial some variable
    public function initialize()
    {
        $this->cryptoService = new CryptoService();
    }

    //==== End: Support method ====//

    //==== Start: Main method ====//
    //Method for procees encrypt request
    public function getEncryptAction($appId)
    {

        //get input
        $params = $this->getUrlParams();

        // Validate input
        $params = $this->myValidate->validateApi($this->encryptRule, [], $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], $this->encrypt_uri);
        }

        //get data in service
        $result = $this->cryptoService->encryptProcess($appId, $params['plain_text']);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], $this->encrypt_uri);
        }

        return $this->output($result['data']);
    }

    //Method for procees decrypt request
    public function getDecryptAction($appId)
    {
        //get input
        $params = $this->getUrlParams();

        // Validate input
        $params = $this->myValidate->validateApi($this->decryptRule, [], $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], $this->decrypt_uri);
        }

        //get data in service
        $result = $this->cryptoService->decryptProcess($appId, $params['encrypted']);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], $this->decrypt_uri);
        }

        return $this->output($result['data']);
    }
    //==== End: Main method ====//
}
