<?php

class Detections
{



    public static function index($data)
    {


        $responseData = [];

        try {
            $model = new DetectionsModel();

            $result = $model->getAll();
            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : $model->getAll();

            return databaseStatusHandler($result, 'List of All Detections', 'Unable to get Detections list', 'Unable to to Detections list, were are working on it', $responseData);

        } catch (\Throwable $th) {
            feedback('error', 'We are Having issues with connecting to the database', [$th]);
        }

    }

    public static function getphoto($data)
    {
        $directory = 'public/';
        $imageDir = 'images/';
        $filesDir = 'files/';
        $photoInfo = explode('-', $data);
        // show();
        $responseData = [];


        // try {
        $model = new DetectionsModel();
        $result = $model->find(['id' => $photoInfo[0]]);
       
        if (!isset($result['data']) && !$result['data']) {
            return databaseStatusHandler($result, 'List of All Detections', 'Unable to get Detections list', 'Unable to to Detections list, were are working on it', $responseData);
        }


        $jsonfile = $directory . $result['data']['path'] . $filesDir . $photoInfo[1] . '.json';

        if (!file_exists($jsonfile))
            return feedback('fail', 'pot hole data not found', $responseData);


        $jsonData = file_get_contents($jsonfile);
        // echo $jsonData;
        if (!$jsonData)
            return feedback('fail', 'unable to get data pot hole data', $responseData);


        $jsonData = json_decode($jsonData, true);
        $result['data']['json'] = $jsonData;

        return feedback('success', 'Pot hole data retrived', $jsonData);


        // } catch (\Throwable $th) {
        //     feedback('error', 'We are Having issues with connecting to the database', [$th]);
        // }




        // show($result);x  




    }
    public static function search($data)
    {

        try {
            $model = new DetectionsModel();

            $result = $model->search($data);
            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : [];
            $list = count($responseData) ?? 0;

            $list2 = (string) $list;

            return databaseStatusHandler($result, $list > 1 ? $list2 . ' results Found' : $list2 . ' results Founds', ' Wrong Credentials', 'Unable to the data were are working on it', $responseData);

        } catch (\Throwable $th) {
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);
        }



    }


    public static function store($data)
    {
        $directory = 'public/';
        $imageDir = 'images/';
        $filesDir = 'files/';

        $responseData = [];

        $path = uniqid('files_') . '_' . time() . '/';

        $errors = false;

        try {
            // # code...
            if (isset($_FILES['files'])) {

                if (count($_FILES['files']['name']) > ini_get('max_file_uploads'))
                    return feedback('fail', 'you exceeded maximum number of uploads', []);
                $newDIr = $directory . '/' . $path;

                if (!file_exists($newDIr)) {
                    mkdir($newDIr, 0777, true);

                }

                foreach ($_FILES['files']['full_path'] as $key => $value) {
                    try {
                        $imagePath = $newDIr . $imageDir;
                        if (!file_exists($imagePath)) {
                            mkdir($imagePath, 0777, true);

                        }
                        // echo $imagePath;
                        move_uploaded_file($_FILES['files']['tmp_name'][$key], $imagePath . $key . '.jpeg');

                        $filePath = $newDIr . $filesDir;

                        if (!file_exists($filePath)) {
                            mkdir($filePath, 0777, true);

                        }
                        $decodedString = urldecode($_FILES['files']['full_path'][$key]);
                        // $jsonData = json_encode();
                        // echo $filePath;
                        file_put_contents($filePath . $key . '.json', $decodedString);
                        //code...
                    } catch (\Throwable $th) {
                        $errors = true;
                    }

                }
                if (!$errors) {
                    $model = new DetectionsModel();
                    $address = (string) count($_FILES['files']['full_path']);
                    $data = [...$data, 'path' => $path, 'address' => $address];

                    $result = $model->create($data);
                    $responseData = isset($result['data']) && $result['data'] ? $result['data'] : $model->getAll();

                    return databaseStatusHandler($result, 'Detections Stored', 'Unable save Detections list', 'Unable to save Detections list, were are working on it', $responseData);
                }
                return feedback('fail', 'Something Went Wrong While saving the files ', [$th]);

            }


        } catch (\Throwable $th) {
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);

        }


    }
    public static function update($data)
    {
        $responseData = [];
        try {
            $model = new DetectionsModel();
            $result = $model->update($data['id'], $data);
            // $responseData = $result['data'] ?: $model->getAll();
            // $responseData = $result['data'] ?? $model->getAll();
            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : $model->read($data['id']);
            return databaseStatusHandler($result, 'Updated Account Successfully', 'Unable to Update Account', 'Unable to Update Account were are working on it', $responseData);

        } catch (\Throwable $th) {
            //throw $th;
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);

        }
    }

    public static function destroy($data)
    {
        $responseData = [];
        try {
            $model = new DetectionsModel();
            $result = $model->delete($data);

            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : $model->read($data);

            return databaseStatusHandler($result, 'Deleted Account Successfully', 'Unable to Delete  Account', 'Unable to Delete were are working on it', $responseData);

        } catch (\Throwable $th) {
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);

        }
    }
}
