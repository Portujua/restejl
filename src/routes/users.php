<?php

$app = new \Slim\Slim;


$app->group('/api', function() use ($app){
	// Get all users
	$app->get('/user', function() use ($app) {
		try {
			$db = new Db();
			$users = $db->run("select * from user");
			$app->response()->status(STATUS_OK);

			echo json_encode($users);
		}
		catch (PDOException $ex) {
			echo '{"error": {"text": "'.$ex->getMessage().'"}}';
		}
	});

	// Add new user
	$app->post('/user', function() use ($app) {
		$data = $app->request->post();

		try {
			$db = new Db();
			$db->run(
				"insert into user (username, password) values (:username, :password)",
				[":username" => $data['username'], ":password" => $data['password']]
			);

			$app->response()->status(STATUS_CREATED);

			echo json_encode(
				["status" => STATUS_CREATED, "text" => "'user' created successfully!"]
			);
		}
		catch (PDOException $ex) {
			echo '{"error": {"text": "'.$ex->getMessage().'"}}';
			$app->response()->status(STATUS_BAD_REQUEST);
		}
	});
});