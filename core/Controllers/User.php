<?php

class User
{


    public static function index($data)
    {


        $responseData = [];

        try {
            $model = new UserModel();

            $result = $model->getAll();
            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : $model->getAll();

            return databaseStatusHandler($result, 'List of All Users', 'Unable to get Users list', 'Unable to to Users list, were are working on it', $responseData);

        } catch (\Throwable $th) {
            feedback('error', 'We are Having issues with connecting to the database', [$th]);
        }

    }


    public static function search($data)
    {

        try {
            $model = new UserModel();

            $result = $model->search($data);
            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : [];
            $list = count($responseData) ?? 0;
            
            $list2 = (string) $list;

            return databaseStatusHandler($result, $list > 1 ? $list2 . ' results Found' : $list2 . ' results Founds', ' Wrong Credentials', 'Unable to Login Into you`re  account were are working on it', $responseData);

        } catch (\Throwable $th) {
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);
        }



    }
    public static function login($data)
    {
        $model = new UserModel();

        try {
            $model = new UserModel();

            $result = $model->find($data);
            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : [];
            return databaseStatusHandler($result, 'Successfully Logged in', ' Wrong Credentials', 'Unable to Login Into you`re  account were are working on it', $responseData);

        } catch (\Throwable $th) {
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);
        }



    }

    public static function register($data)
    {
        $responseData = [];

        try {
            $model = new UserModel();

            $result = $model->create($data);
            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : $model->getAll();
            return databaseStatusHandler($result, 'Created Account Successfully', 'Unable to Create Account', 'Unable to Create Account were are working on it', $responseData);

        } catch (\Throwable $th) {
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);
        }

    }
    public static function update($data)
    {
        $responseData = [];
        try {
            $model = new UserModel();
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
            $model = new UserModel();
            $result = $model->delete($data);

            $responseData = isset($result['data']) && $result['data'] ? $result['data'] : $model->read($data);

            return databaseStatusHandler($result, 'Deleted Account Successfully', 'Unable to Delete  Account', 'Unable to Delete were are working on it', $responseData);

        } catch (\Throwable $th) {
            return feedback('error', 'We are Having issues with connecting to the database', [$th]);

        }
    }
}
