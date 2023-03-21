<?php

namespace App\Services;

use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseService
{ /**
    * @var Firebase
    */
   protected $firebase;

   public function __construct()
   {
       $serviceAccount = ServiceAccount::fromArray([
           "type" => "service_account",
           "project_id" => config('services.firebase.project_id'),
           "private_key_id" => config('services.firebase.private_key_id'),
           "private_key" => config('services.firebase.private_key'),
           "client_email" => config('services.firebase.client_email'),
           "client_id" => config('services.firebase.client_id'),
           "auth_uri" => config('services.firebase.auth_uri'),
           "token_uri" => config('services.firebase.token_uri'),
           "auth_provider_x509_cert_url" => config('services.firebase.auth_provider_x509_cert_url'),
           "client_x509_cert_url" => config('services.firebase.client_x509_cert_url')
       ]);

       $this->firebase = (new Factory)
           ->withServiceAccount($serviceAccount)
           ->withDatabaseUri(config('services.firebase.database_url'))
           ->createDatabase();
//    }
//    public function verifyPassword($email, $password)
//     {
//         try {
//             $response = $this->firebase->getAuth()->verifyPassword($email, $password);
//             return $response->uid;

//         } catch(FirebaseEmailExists $e) {
//             logger()->info('Error login to firebase: Tried to create an already existent user');
//         } catch(Exception $e) {
//             logger()->error('Error login to firebase: ' . $e->getMessage());
//         }
//         return false;
//     }
}}
