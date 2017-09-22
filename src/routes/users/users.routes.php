<?php 
/**
* User routes 
*
* Contains all user routes
*
* @package Routes
* @author Eduardo Lorenzo <ejlorenzo19@gmail.com>
* @since 30/04/2017
* @license MIT
*/

$app->group('/users', function() use ($app){
	$authToken = $app->request->headers->get('Auth-Token');
	Session::setLastToken($authToken);

	/** Get all users */
	$app->get('/', function() use ($app) {
		$pageable = new Pageable($app->request->params());

		$response = new Response(User::getInstance()->list($pageable));
		$response->setSlim($app);
		echo $response->getResponse();
	});

	/** Add new user */
	$app->post('/', function() use ($app) {
		$data = json_decode($app->request->getBody(), true);

		$response = new Response(
			User::getInstance()->add(User::createPayload($data))
		);
		$response->setSlim($app);
		echo $response->getResponse();
	});

	/** Update user by username */
	$app->put('/', function() use ($app) {
		$data = json_decode($app->request->getBody(), true);

		$response = new Response(
			User::getInstance()->update(User::putPayload($data))
		);
		$response->setSlim($app);
		echo $response->getResponse();
	});

	/** Update user by username */
	$app->patch('/:id', function($id) use ($app) {
		$data = json_decode($app->request->getBody(), true);

		$response = new Response(
			User::getInstance()->patch(User::patchPayload($id, $data))
		);
		$response->setSlim($app);
		echo $response->getResponse();
	});

	/** Delete user by params */
	$app->delete('/', function() use ($app) {
		$data = json_decode($app->request->getBody(), true);

		$response = new Response(
			User::getInstance()->delete($data)
		);
		$response->setSlim($app);
		echo $response->getResponse();
	});
});

?>