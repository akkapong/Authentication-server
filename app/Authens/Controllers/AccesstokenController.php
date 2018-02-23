<?php
namespace Authens\Controllers;

use Core\Controllers\ControllerBase;

// use Authens\Schemas\AuthenSchema;
// use Authens\Collections\AuthenCollection;
use Authens\Services\AccesstokenService;


/**
 * Display the default index page.
 */
class AccesstokenController extends ControllerBase
{
    //==== Start: Define variable ====//
    private $request_key_uri = '/api/request_token';
    private $check_key_uri   = '/api/check_token';
    private $refresh_key_uri = '/api/refresh_token';
    private $accesstokenService;

    private $requestTokenRule = [
        [
            'type'   => 'required',
            'fields' => ['encrypted'],
        ]
    ];

    private $checkTokenRule = [
        [
            'type'   => 'required',
            'fields' => ['token', 'server_no'],
        ]
    ];

    private $refreshTokenRule = [
        [
            'type'   => 'required',
            'fields' => ['refresh_token'],
        ]
    ];
    // private $modelName;
    // private $schemaName;

    //==== End: Define variable ====//

    //==== Start: Support method ====//
    //Method for initial some variable
    public function initialize()
    {
        $this->accesstokenService = new AccesstokenService();
        // $this->modelName   = AuthenCollection::class;
        // $this->schemaName  = AuthenSchema::class;
    }

    //==== End: Support method ====//

    //==== Start: Main method ====//
    public function getAccesstokenAction($appId)
    {
        //get input
        $params = [];

        $params['encrypted'] = $this->getHeaderValue('encrypted');

        // Validate input
        $params = $this->myValidate->validateApi($this->requestTokenRule, [], $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], $this->request_key_uri);
        }

        //get data in service
        $result = $this->accesstokenService->requestAccessToken($appId, $params['encrypted']);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], $this->request_key_uri);
        }

        return $this->output($result['data']);

    }

    public function getCheckaccesstokenAction()
    {
        //get input
        $params          = $this->getUrlParams();
        
        $params['token'] = $this->getHeaderValue('accesstoken');
        
        // Validate input
        $params          = $this->myValidate->validateApi($this->checkTokenRule, [], $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], $this->check_key_uri);
        }

        //get data in service
        $result = $this->accesstokenService->checkAccessToken($params['token'], $params['server_no']);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], $this->check_key_uri);
        }

        return $this->output(null, 204);

    }


    public function postRefreshtokenAction()
    {
        //get input
        $params          = $this->getPostInput();
        
        // Validate input
        $params          = $this->myValidate->validateApi($this->refreshTokenRule, [], $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], $this->refresh_key_uri);
        }

        //get data in service
        $result = $this->accesstokenService->refreshTokenProcess($params['refresh_token']);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], $this->refresh_key_uri);
        }

        return $this->output($result['data']);

    }
    //==== End: Main method ====//
}
