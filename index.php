<?php

// include crons class
require_once 'Cros.php';

// configurations
$config = [
    'methods' => ['POST','GET','OPTIONS'], // allowed methods types
    'credentials' => false,      // allow credentials headers
    'origin' => ['localhost'],   // allowed origin or hosts
    'max_age' => (60*60)         // cache for 1 hour
];

// initialize Cros class
$cros = new Cros($config);

// allow ajax request and set response contect-type to json
$cros->ajax()
     ->header('Content-type', 'application/json');

// return response headers
echo json_encode(headers_list());
