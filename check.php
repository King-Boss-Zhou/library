<?php
    function login()
    {
        header("Content-Type:text/html;charset=utf-8");
        if (!session_id()) session_start();  
        if(empty($_SESSION['username'])||empty($_SESSION['islogin']))
        {
            echo "<script language=\"JavaScript\">alert(\"Please Login!\");</script>";
            header('refresh:0; url=login.html');
            exit;
        }
    }
    login();
?>