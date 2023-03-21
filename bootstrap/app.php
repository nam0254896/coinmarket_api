<?php
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/
$firebaseConfig = [
    'apiKey' => env('FIREBASE_API_KEY'),
    'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
    'databaseURL' => env('FIREBASE_DATABASE_URL'),
    'projectId' => env('FIREBASE_PROJECT_ID'),
    'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
    'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
    'appId' => env('FIREBASE_APP_ID')
];

// $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firebase_credentials.json');

// $factory = (new Factory)
//     ->withServiceAccount(__DIR__.'/firebase_credentials.json')
//     ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

// $app = $factory->createDatabase();
return $app;
