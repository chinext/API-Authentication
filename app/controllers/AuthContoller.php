<?php

namespace App\Controllers;

use Exception;
use App\Core\App;
use App\Model\User;
use \Firebase\JWT\JWT;



class AuthController
{

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    public function setHeader()
    {
        header("Access-Control-Allow-Origin: http://localhost/patricia-api-authentication/");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

   
    public function createUser()
    { 
        $this->setHeader(); 
        $user = new User(App::get('db_connection'));
        $data = json_decode(file_get_contents("php://input"));

        $user->firstname = $data->firstname;
        $user->lastname  = $data->lastname;
        $user->email     = $data->email;
        $user->password  = $data->password;

        // check if email exist
        $email_exists = $user->emailExists();
        if(!$email_exists){
            if(!empty($user->firstname) && !empty($user->email) && !empty($user->password) && $user->create() ){         
                http_response_code(200);
                echo json_encode(array("message" => "User was created."));
            }else{
                http_response_code(400);
                echo json_encode(array("message" => "Unable to create user."));
            }

        }else{
            http_response_code(400);
            echo json_encode(array("message" => "Email exist"));
        }
        
    }



    public function loginUser()
    { 
        $this->setHeader(); 
        $user = new User(App::get('db_connection'));
        $data = json_decode(file_get_contents("php://input"));

        $user->email = $data->email;
        $email_exists = $user->emailExists();


        if($email_exists && password_verify($data->password, $user->password)){ 
            $token = array(
               "iss" => App::get('jwt')['iss'], 
               "aud" => App::get('jwt')['aud'],
               "iat" => App::get('jwt')['iat'],
               "nbf" => App::get('jwt')['nbf'], 
               "data" => array(
                   "id"        => $user->id,
                   "firstname" => $user->firstname,
                   "lastname"  => $user->lastname,
                   "email"     => $user->email
               )
            );
            http_response_code(200);         
            // generate jwt
            $jwt = JWT::encode($token, App::get('jwt')['key']);
            echo json_encode(
                    array(
                        "message" => "Login successful",
                        "jwt" => $jwt
                    )
                );         
        }else{ 
            http_response_code(401);             
            echo json_encode(array("message" => "Login failed."));
        }

    }

    
    public function validateToken()
    {
        $this->setHeader(); 
        $data = json_decode(file_get_contents("php://input"));         
        // get jwt
        $jwt=isset($data->jwt) ? $data->jwt : "";

        if($jwt){ 
            // if decode succeed, show user details
            try {
                // decode jwt
                $decoded = JWT::decode($jwt, App::get('jwt')['key'], array('HS256'));         
                http_response_code(200);
                echo json_encode(array(
                    "message" => "Access granted.",
                    "data" => $decoded->data
                ));
         
            }catch (Exception $e){ 
                http_response_code(401);
                echo json_encode(array(
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                ));
            }         
            
        }else{
            http_response_code(401);
            echo json_encode(array("message" => "Access denied."));
        }
    }


    public function updateUser()
    {
        $this->setHeader(); 
        $user = new User(App::get('db_connection'));
        $data = json_decode(file_get_contents("php://input"));  
        $jwt  = isset($data->jwt) ? $data->jwt : "";   
        if($jwt){
                try {
                    $decoded = JWT::decode($jwt, App::get('jwt')['key'], array('HS256'));
                    $user->firstname = $data->firstname;
                    $user->lastname  = $data->lastname;
                    $user->email     = $data->email;
                    $user->password  = $data->password;
                    $user->id        = $decoded->data->id;

                    if($user->update()){
                        //re-generate jwt because user details might be different
                        $token = array(
                           "iss" => App::get('jwt')['iss'], 
                           "aud" => App::get('jwt')['aud'],
                           "iat" => App::get('jwt')['iat'],
                           "nbf" => App::get('jwt')['nbf'], 
                           "data" => array(
                               "id"        => $user->id,
                               "firstname" => $user->firstname,
                               "lastname"  => $user->lastname,
                               "email"     => $user->email
                           )
                        );
                        $jwt = JWT::encode($token, App::get('jwt')['key'] );                         

                        http_response_code(200);
                        echo json_encode(
                                array(
                                    "message" => "User was updated.",
                                    "jwt" => $jwt
                                )
                            );
                    }else{
                        http_response_code(401);
                        echo json_encode(array("message" => "Unable to update user."));
                    }


                }catch (Exception $e){
                    http_response_code(401);
                    echo json_encode(array(
                        "message" => "Access denied.",
                        "error" => $e->getMessage()
                    ));
                }
        }else{
            http_response_code(401);
            echo json_encode(array("message" => "Access denied.")); 
        }

    }
    

    



}
