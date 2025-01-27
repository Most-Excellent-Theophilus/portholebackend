<?php
// Allow requests from any origin
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS, PUT');
header('Access-Control-Allow-Headers: Content-Type');
date_default_timezone_set('Africa/Blantyre');

require __DIR__."/core/all.php";
