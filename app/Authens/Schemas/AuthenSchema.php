<?php
namespace Authens\Schemas;
use Phalcon\DI;

/**
 * Copyright 2015 info@neomerx.com (www.neomerx.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\Samples\JsonApi
 */
class AuthenSchema extends SchemaProvider
{
    protected $resourceType = 'authens';

    //Method for make static url link
    protected function generatePubliucLink($uri)
    {
        //get config
        $configs = DI::getDefault()->get('config');
        return $configs->application->publicUrl.$uri;
    }

    public function getId($authen)
    {
        /** @var User $user */
        return (string)$authen->_id;
    }

    public function getAttributes($authen)
    {
        /** @var User $user */
        return [
            'app_name'   => $authen->app_name,
            'client_key' => $this->generatePubliucLink($authen->client_key),
            'keyword'    => $authen->keyword,
            'lifetime'   => $authen->lifetime,
            'created_at' => $authen->created_at,
            'updated_at' => $authen->updated_at,
        ];
    }
}