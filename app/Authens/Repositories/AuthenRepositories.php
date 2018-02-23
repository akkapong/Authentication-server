<?php
namespace Authens\Repositories;

use Core\Repositories\CollectionRepositories;
use Authens\Collections\AuthenCollection;

class AuthenRepositories extends CollectionRepositories {


    //==== Start: Define variable ====//
    public $module         = 'users';
    public $collectionName = 'AuthenCollection';
    public $allowFilter    = ['app_name', 'created_at', 'updated_at'];
    public $model;
    //==== Start: Define variable ====//


    //==== Start: Support method ====//
    public function __construct()
    {
        $this->model = new AuthenCollection();
        parent::__construct();
    }
    //==== End: Support method ====//
}