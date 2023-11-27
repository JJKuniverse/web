<?php
    session_start();

    //한글 꺠짐 방지를 위한 utf-8 인코딩
    header('Content-Type: text/html; charset=utf-8');

    $db = new mysqli("192.168.84.128","root","123123","post");
    $db->set_charset("utf-8");

    function query($query)
    {
            global $db;
            return $db->query($query);
    }
?>
