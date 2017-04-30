<?php 

$user = new User();

$app->group('/api', function() use ($app, $user){
	// Get all users
	$app->get('/user', function() use ($app, $user) {
		echo $user->listAll();
	});

	// Add new user
	$app->post('/user', function() use ($app, $user) {
		echo $user->add(User::createPayload($app->request->post()));
	});

	// Update user by username
	$app->put('/user/:id', function($id) use ($app, $user) {
		echo $user->update(User::putPayload($id, $app->request->put()));
	});

	// Delete user by params
	$app->delete('/user', function() use ($app, $user) {
		echo $user->delete($app->request->delete());
	});
});

?>