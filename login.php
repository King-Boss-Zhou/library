<?php  
    header("Content-Type:text/html;charset=utf-8"); 
    //record the global variables to keep the login state and username
    session_start();

    //to clear the login state
    unset($_SESSION['islogin']);
    unset($_SESSION['username']);

     //Judge if the information has been posted from Admin_Login.html
    if(isset($_POST['btn_login']) && $_POST['btn_login'] == "登录") 
    {  
        //Get the input username and password
        $username = trim($_POST['username']);
        $password = trim($_POST['password']); 
        //username or password is empty
        if(($username=='')||($password==''))
        {  
            //alarm
            echo "<script language=\"JavaScript\">alert(\"Username or password can not be empty! Please input!\");</script>";
            header('refresh:0; url=login.html');
            exit;  
        }
        else
        {  
            //Connet the database
            $serverName = "localhost";
            $uid = "3160101817";
            $pwd = "123456";
            $dbname = "library";
            $conn = new mysqli($serverName, $uid, $pwd, $dbname);

            if($conn->connect_error)    //Data base connet failed
            {
                echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                header('refresh:0; url=login.html');
                exit; 
            }
            else
            {
                //query for password
                $sql = "select password from manager where manager_ID = '{$username}'";
                $result = $conn->query($sql);
                $row = mysqli_fetch_assoc($result);
                //password error   if there is more than 1 record means sql attack
                if($row["password"] != $password )
                {
                    echo "<script language=\"JavaScript\">alert(\"Username or password is wrong!\");</script>";
                    header('refresh:0; url=login.html');
                    exit; 
                }
                else  //successful
                {
                    //Record the login state
                  $_SESSION['username']=$username;  
                  $_SESSION['islogin']=1;
                  echo "<script language=\"JavaScript\">alert(\"Login Successfully!\");</script>";
                  echo "<script>location='main.php';</script>";
                  exit;
                }
            }
        }  
    }
    else
    {
      echo "<script language=\"JavaScript\">alert(\"Please Login!\");</script>";
      header('refresh:0; url=login.html');
      exit; 
    }
?>  