<?php

if (!empty($_GET)) {
    $method = $_SERVER['REQUEST_METHOD'];

    $request = explode('/', $_GET['request']);
    $paramslist = count($request);
    $class = $request[0];
    $function = 'index';
    $data = [''];
    $id = '';
    $fail = false;

    switch ($method) {
        case 'GET':
            
            if ($paramslist == 3) {
                $function = $request[1];
                $id = $request[2];

            }
            break;
        case 'POST':
            if ($paramslist == 2) {
                $function = $request[1];
                $data = $_POST;
            } else {
                feedback('fail', 'POST parameters not enough', []);
                $fail = true;
            }
            break;



        case 'PUT':
        case 'PATCH':
            if ($paramslist == 1) {
                $function = 'update';
                parse_str(file_get_contents("php://input"), $data);

            } else {
                feedback('fail', 'UPDATE parameters not enough', []);
                $fail = true;
            }
            break;
        case 'DELETE':
            if ($paramslist == 2) {
                $function = 'destroy';
                $data = $request['1'];
            } else {
                feedback('fail', 'unable to delete', []);
                $fail = true;
            }
            break;
        default:
            feedback('fail', 'method not Allowed.', []);

            break;
    }
    if (!$fail) {
        call_user_func_array([$class, $function], [$data, $id]);

        // feedback('success', 'success indeed', [
        //     'request' => $request,
        //     'params' => $paramslist,
        //     'class' => $class,
        //     'function' => $function,
        //     'data' => $data,
        //     'id' => $id
        // ]);
    }

} else {
    feedback('fail', 'parameters not enough', []);
}
