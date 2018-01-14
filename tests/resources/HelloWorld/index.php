<?php
	header('Content-Type: application/json');
	
	$data = [
		'first' => 'Hello World',
		'second' => 'Isn\'t lorem ipsum'
	];
	
	echo json_encode($data);
