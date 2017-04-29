<?php
require 'vendor/autoload.php';
require 'src/config/db.php';

define("STATUS_OK", 200);
define("STATUS_CREATED", 201);
define("STATUS_BAD_REQUEST", 400);
define("STATUS_UNAUTHORIZED", 401);
define("STATUS_FORBIDDEN", 403);
define("STATUS_NOT_FOUND", 404);
define("STATUS_METHOD_NOT_ALLOWED", 405);
define("STATUS_TIMEOUT", 408);
define("STATUS_INTERNAL_SERVER_ERROR", 500);
define("STATUS_NOT_IMPLEMENTED", 501);

$app = new \Slim\Slim;

// Routes
$routesFolder = 'src/routes/';
$files = array_diff(scandir($routesFolder), array('.', '..'));

foreach ($files as $f)
{
	$ext = explode('.', $f);
	$ext = $ext[count($ext) - 1];

	$path = $routesFolder . $f;

	require $path;
}
//require 'src/routes/users.php';

$app->response()->header('Content-Type', 'application/json');

$app->run();