<?php
namespace Authens\Services;

use Authens\Repositories\AuthenRepositories;

class AuthenService extends AuthenRepositories
{
    //==== Start: Define variable ====//
    
    //==== End: Define variable ====//


    //==== Start: Support method ====//

    //Method for create filter for check duplicate
    protected function createFilterForCheckDup($appName)
    {
        return [
            'app_name' => $appName,
        ];
    }

    // //manage client key file 
    // protected function manageClientKeyFile($name, $files, $old="")
    // {
    //     $directory = $this->config->path->client_key;

        
    //     $extension = $files->getExtension();
    //     $fileName  = $name.".".$extension;

    //     if (!empty($old) && file_exists($directory . $old)) {
    //         unlink($directory . $old);
    //     }

    //     $upload    = $files->moveTo($directory . $fileName);
        
    //     return $fileName;
    // }

    //Method for generate 64 bit key
    protected function generateRandomString($length=64)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    //==== End: Support method ====//


    //==== Stat: Main method ====//
    //Method for get data by filter
    public function getProcess($params)
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        try {
            //create filter
            $authens         = $this->getDataByParams($params);

            if (isset($params['limit'])) {
                //get total record
                $outputs['total'] = $authens[1];
            }

            $outputs['data'] = $authens[0];

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = 'missionFail';
        }
        

        return $outputs;
    }

    //Method for get data by id
    public function getDetail($id)
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        try {
            //create filter
            $authen  = $this->getDataById($id);

            if (empty($authen)){
                $outputs['success'] = false;
                $outputs['message'] = 'dataNotFound';
                return $outputs;
            }

            $outputs['data'] = $authen;

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = 'missionFail';
        }
        

        return $outputs;
    }


    //Method for insert data
    public function createProcess($params)
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //Check Duplicate
        $filters = $this->createFilterForCheckDup($params['app_name']);
        $isDups  = $this->checkDuplicate($filters);
     
        if ($isDups[0])
        {
            //Duplicate
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'dataDuplicate';
            return $output;
            
        }

        //manage file 
        // $params['client_key'] = $this->manageClientKeyFile(str_replace(' ', '', $params['app_name']).uniqid(), $params['client_key']);
        $params['key64bit']   = $this->generateRandomString();
        $params['created_at'] = date("Y-m-d H:i:s");
        $params['updated_at'] = date("Y-m-d H:i:s");

        //insert
        $res = $this->insertData($params);

        if (!$res)
        {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'insertError';
            return $output;
        } 

        
        
        //add config data
        $output['data'] = $res;

        return $output;
    }

    //Method for update data
    public function updateProcess($id, $params)
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //Check Duplicate
        $filters = $this->createFilterForCheckDup($params['app_name']);
        $isDups  = $this->checkDuplicateForUpdate($id, $filters);
        
        if ($isDups[0])
        {
            //Duplicate
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'dataDuplicate';
            return $output;
            
        }
        
        //manage file 
        //get old data
        $old = $this->getDataById($id);
        //romove old if have new file and create new one
        // if (isset($params['client_key'])) {
        //     $params['client_key'] = $this->manageClientKeyFile(str_replace(' ', '', $params['app_name']).uniqid(), $params['client_key'], $old->client_key);
        // }
        
        //check have key 64 bit ?
        if (!property_exists($old ,'key64bit') || empty($old->key64bit)) {
            $params['key64bit']   = $this->generateRandomString();
        }

        $params['updated_at'] = date("Y-m-d H:i:s");

        //update
        $res = $this->updateData($old, $params);

        if (!$res)
        {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'updateError';
            return $output;
        } 
        
        //add config data
        $output['data'] = $res;

        return $output;
    }


    //Method for delete data
    public function deleteProcess($id)
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //get data by id
        $authen  = $this->getDataById($id);

        if (empty($authen))
        {
            //No Data
            $output['success'] = false;
            $output['message'] = 'dataNotFound';
            return $output;
        }

        //delete
        $res = $this->deleteData($authen);

        if (!$res)
        {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'deleteError';
            return $output;
        }

        //get insert id
        $output['data'] = $res;

        return $output;
    }
    //==== End: Main method ====//
}