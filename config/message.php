<?php
return new \Phalcon\Config( [

    /* Mission Fail */
    'missionFail' => [
        'code'     => 400,
        'msgError' => 'Mission Fail',
    ],

    /* Validate Fail */
    'validateFail' => [
        'code'     => 401,
        'status'   => 'Error',
        'msgError' => 'Validate Fail',
    ],

    /* Data Not Found */
    'dataNotFound' => [
        'code'     => 400,
        'msgError' => 'Data Not Found',
    ],

    /* Cannot Connect to Database */
    'connectDBError' => [
        'code'     => 400,
        'msgError' => 'Cannot Connect to Database',
    ],

    /* Insert Error */
    'insertError' => [
        'code'     => 400,
        'msgError' => 'Insert Error',
    ],

    /* Update Error */
    'updateError' => [
        'code'     => 400,
        'msgError' => 'Update Error',
    ],

    /* Delete Error */
    'deleteError' => [
        'code'     => 400,
        'msgError' => 'Delete Error',
    ],

    /* Data is duplicate */
    'dataDuplicate' => [
        'code'     => 400,
        'msgError' => 'Data is duplicate',
    ],

    /* keyword is invalid */
    'invalidKeyword' => [
        'code'     => 400,
        'msgError' => 'Keyword is invalid',
    ],

    /* Ạpplication not found */
    'invalidApp' => [
        'code'     => 400,
        'msgError' => 'Application is invalid',
    ],

    /* refresh token is invalid */
    'invalidRefreshToken' => [
        'code'     => 400,
        'msgError' => 'Refresh token is invalid',
    ],

] );