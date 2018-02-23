<?php
namespace Authens\Collections;

use Phalcon\Mvc\MongoCollection;

class AuthenCollection extends MongoCollection
{
    public $app_name;
    public $client_key;
    public $keyword;
    public $key64bit;
    public $lifetime;
    public $created_at;
    public $updated_at;

    public function getSource()
    {
        return 'authens';
    }
}