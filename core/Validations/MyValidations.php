<?php
namespace Core\Validations;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\File as FileValidator;
use \Phalcon\Validation\Validator\Callback;

class MyValidations extends \Phalcon\Mvc\Micro
{
    //Method for manage validate response
    protected function manageValidateResponse($messages)
    {
        $responses = [];
        if (count($messages) > 0) {
            $errors             = [];
            $responses['validate_error'] = [];

            foreach ($messages as $message){
                $errors[] = [
                    'msgError'   => $message->getMessage(),
                    'fieldError' => $message->getField(),
                ];
            }
            $responses['validate_error']['status']  = $this->message->validateFail->status; 
            $responses['validate_error']['code']    = $this->message->validateFail->code; 
            $responses['validate_error']['message'] = $this->message->validateFail->msgError; 
            $responses['validate_error']['datas']   = $errors; 
        }

        
        return $responses;
    }

    public function validateApi($rules, $default = [], $input = [])
    {
        $return   = [];

        //Manage default
        foreach (array_keys($default) as $key) {

            if (isset($input[$key]) && !empty($input[$key])) {
                $return[$key] = $input[$key];
            } else {
                $return[$key] = $default[$key];
            }

        }

        $input = array_merge($input, $return);

        //=== Start: Validate process ====//
        $validate = $this->validate($input, $rules);

        if (!empty($validate['validate_error'])) {
            return $validate;
        }
        //=== End: Validate process ====//
        
        
        return $input;
    }

    protected function convertArrayToText($array)
    {
        $output = "";
        foreach ($array as $key => $value) {
            if (!empty($output)) {
                $output .= ", ";;
            }
            $output .= $value;
        }
        return $output;
    }

    public function validate($input, $rules)
    {
        $validation = new Validation();

        foreach ($rules as $value)
        {

            switch (strtolower($value['type']))
            {

                case 'required':

                    foreach ($value['fields'] as $field)
                    {
                        $validation->add($field, new PresenceOf([
                            'message' => 'The ' . $field . ' is required',
                        ]));
                    }

                    break;

                case 'number':

                    foreach ($value['fields'] as $field)
                    {
                        $validation->add($field, new Numericality([
                            'message' => ucfirst($field) . ' must be numberic',
                        ]));
                    }

                    break;

                case 'within':
                    foreach ( $value['fields'] as $key => $list ) {
                        $validation->add( $key, new InclusionIn( [
                            'message' => 'The '.$key.' must be in '.implode(" , ", $list),
                            'domain'  => $list
                        ]));
                    }
                    break;

                case 'file_extension':

                    foreach ( $value['fields'] as $key => $allowList ) {
                        // $validation->add($key, new FileValidator($rules));

                        //replace file witk _FILES
                        // $input[$key] = $_FILES[$key];
                        $validation->add($key, new Callback(
                            [
                                'callback' => function($data) use ($key, $allowList) {
                                    if (!isset($data[$key])) {
                                        return true;
                                    }
                                    
                                    $extension = $data[$key]->getExtension();

                                    return in_array($extension, $allowList);
                                },
                                'message' => "The $key allow only ". $this->convertArrayToText($allowList)
                            ]
                        ));
                    }
                    break;

                default:
                    //default
            }

        }
// print_r($input); exit;
        $messages = $validation->validate($input);

        return $this->manageValidateResponse($messages);
    }
}