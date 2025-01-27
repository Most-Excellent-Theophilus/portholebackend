<?php

class UserModel extends Database
{
    protected function getTableName(): string{
        return 'User';
    }
}
