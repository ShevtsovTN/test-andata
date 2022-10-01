<?php

namespace App\interfaces;

interface DatabaseInterface
{
    public function setConnection(string $host, string $user, string $pass, string $dbName);

    public function query($sql);

    public function getInstanceDB();
}