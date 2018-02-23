<?php
namespace Authens\Controllers;

use Core\Controllers\ControllerBase;

use Authens\Schemas\AuthenSchema;
use Authens\Collections\AuthenCollection;
use Authens\Services\AuthenService;


/**
 * Display the default index page.
 */
class AuthenController extends ControllerBase
{
    //==== Start: Define variable ====//
    private $uri = '/api/authens';
    private $authenService;
    private $modelName;
    private $schemaName;

    private $getDetailRule = [
        [
            'type'   => 'required',
            'fields' => ['id'],
        ]
    ];

    private $createRule = [
        [
            'type'   => 'required',
            'fields' => ['app_name', 'keyword'], //, 'client_key'
        ]
        // , [
        //     'type'   => 'file_extension',
        //     'fields' => [
        //         'client_key' => ['pem']
        //     ]
        // ]

    ];

    private $updateRule = [
        [
            'type'   => 'required',
            'fields' => ['app_name'],
        ]
        // , [
        //     'type'   => 'file_extension',
        //     'fields' => [
        //         'client_key' => ['pem']
        //     ]
        // ]

    ];

    private $deleteRule = [
        [
            'type'   => 'required',
            'fields' => ['id'],
        ],
    ];
    //==== End: Define variable ====//

    //==== Start: Support method ====//
    //Method for initial some variable
    public function initialize()
    {
        $this->authenService = new AuthenService();
        $this->modelName   = AuthenCollection::class;
        $this->schemaName  = AuthenSchema::class;
    }

    //==== End: Support method ====//

    //==== Start: Main method ====//
    public function getAuthenAction()
    {
        //get input
        $params = $this->getUrlParams();

        //get data in service
        $result = $this->authenService->getProcess($params);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], $this->uri);
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        $limit  = (isset($params['limit']))?$params['limit']:null;
        $offset = (isset($params['offset']))?$params['offset']:null;
        $total  = (isset($result['total']))?$result['total']:null;

        return $this->response($encoder, $result['data'], 200, $limit, $offset, $total);

        
    }

    public function getAuthendetailAction($id)
    {
        //get data in service
        $result = $this->authenService->getDetail($id);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], $this->uri,'/'.$id);
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);

    }

    public function postAuthenAction()
    {
        //get input
        $params = $this->getPostFormInput();
        $files  = $this->getFile();    
        $params = array_merge($params, $files);

        //define default
        $default = [
            'lifetime' => 1 //1 hour
        ];

        // Validate input
        $params = $this->myValidate->validateApi($this->createRule, $default, $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], $this->uri);
        }

        //add member data by input
        $result = $this->authenService->createProcess($params);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], $this->uri);
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data'], 201);
    }

    public function updateAuthenAction($id)
    {
        //get input
        $params = $this->getPostFormInput();
        $files  = $this->getFile();    
        $params = array_merge($params, $files);

        //define default
        $default = [];

        // Validate input
        $params = $this->myValidate->validateApi($this->updateRule, $default, $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], $this->uri);
        }

        //add member data by input
        $result = $this->authenService->updateProcess($id, $params);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], $this->uri);
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }

    public function deleteAuthenAction($id)
    {
        //update member data
        $result  = $this->authenService->deleteProcess($id);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], $this->uri);
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }
    //==== End: Main method ====//
}
