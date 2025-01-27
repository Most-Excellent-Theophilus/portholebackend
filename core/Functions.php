<?php

function feedback($status, $message, $data)
{


    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
}

function databaseStatusHandler($response, $success, $fail, $default, $data)
{
    switch ($response['status']) {
        case 'success':
            feedback($response['status'], $success, $data);
            break;

        case 'fail':
            feedback($response['status'], $fail, []);
            break;
        case 'error':
            feedback($response['status'], 'We are facing this issue ' . $response['message'], []);
            break;

        default:
            feedback('fail', $default, []);

            break;
    }
}