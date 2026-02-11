<?php
        $host = 'localhost'; $db = 'parkingpro'; $user = 'root'; $pass = '';
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            if (session_status() === PHP_SESSION_NONE) session_start();
        } catch (\PDOException $e) { die("Connection failed"); }
    ?>