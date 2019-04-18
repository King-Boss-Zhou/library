<?php
    include 'check.php';
    login();
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>借书证管理页面</title>
        <link rel="stylesheet" type="text/css" href="card/card.css"/>
    </head>

    <body>
        <div id="Menu_Frame">
            <div class = "SubMenu_Frame">
                <div class = "Icon">
                    <a href = "main.php">
                        <img src = "book/home.png" class = "Image"></img>
                    </a>
                </div>
                <div class="Description">
                    <label>主页</label>
                </div>
            </div>

            <div class = "SubMenu_Frame">
                <div class = "Icon">
                    <a href = "book_search.php">
                        <img src = "book/search.png" class = "Image"></img>
                    </a>
                </div>
                <div class="Description">
                    <label>搜索</label>
                </div>
            </div>
    
            <div class = "SubMenu_Frame">
                <div class = "Icon">
                    <a href = "book_borrow.php">
                        <img src = "book/borrow.png" class = "Image"></img>
                    </a>
                </div>
                <div class="Description">
                    <label>借书</label>
                </div>
            </div>
    
            <div class = "SubMenu_Frame">
                <div class = "Icon">
                    <a href = "book_return.php">
                        <img src = "book/return.png" class = "Image"></img>
                    </a>
                </div>
                <div class="Description">
                    <label>还书</label>
                </div>
            </div>
    
            <div class = "SubMenu_Frame">
                <div class = "Icon">
                    <a href = "book_add.php">
                        <img src = "book/add.png" class = "Image"></img>
                    </a>
                </div>
                <div class="Description">
                    <label>图书入库</label>
                </div>
            </div>
    
            <div class = "SubMenu_Frame">
                <div class = "Icon">
                    <a href = "record_search.php">
                        <img src = "book/record.png" class = "Image"></img>
                    </a>
                </div>
                <div class="Description">
                    <label>借阅记录</label>
                </div>
            </div>
    
            <div class = "SubMenu_Frame">
                <div class = "Icon">
                    <a href = "card_manage.php">
                        <img src = "book/card.png" class = "Image"></img>
                    </a>
                </div>
                <div class="Description">
                    <label>借书证管理</label>
                </div>
            </div>

            <div id = "Top_Frame">
                <p id = "Admin">
                    <a href = "logout.php" id = "Logout">
                        注销
                    </a>
            
                    <label id = "User">
                        欢迎 <?php echo $_SESSION['username']?>
                    </label>
                </p>
            </div>
        </div>

        <div id="Manage">
            <label id="Hint">
                请输入借书证信息：
            </label>

            <form method="post" id="Condition">
                <p>
                    <label class="label_input">借书证号</label><input type="text" name="card_ID" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">姓名</label><input type="text" name="username" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">院系</label><input type="text" name="department" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">类型</label><input type="text" name="type" class="text_field"/>
                </p>

                <div id="manage_control">
                    <input type="submit" id="search_button" name="search_button" value="查询"/>
                    <input type="submit" id="insert_button" name="insert_button" value="添加"/>
                    <input type="submit" id="delete_button" name="delete_button" value="删除"/>
                </div>
            </form>
        </div>

        <div id="Results">
            <label id="Hint">
                查询结果：
            </label>

            <label id="result">

            </label>
        </div>
        <?php
            if(isset($_POST['search_button']) && $_POST['search_button'] == "查询")
            {
                $card_ID = $_POST['card_ID'];
                $username = $_POST['username'];
                $department = $_POST['department'];
                $type = $_POST['type'];

                $serverName = "localhost";
                $uid = "3160101817";
                $pwd = "123456";
                $dbname = "library";
                $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                if($conn->connect_error)    //Data base connet failed
                {
                    echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                    echo "<script>location='card_manage.php';</script>";
                }
                else
                {
                    $sql = "SELECT * FROM card WHERE
                            (? = '' OR card_ID = ?) AND
                            (? = '' OR username = ?) AND
                            (? = '' OR department = ?) AND
                            (? = '' OR type = ?) ORDER BY card_ID";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssssss", $card_ID, $card_ID,
                                    $username, $username,
                                    $department, $department,
                                    $type, $type);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($card_ID, $username, $department, $type);
                    if($stmt->num_rows)
                    {
                        $Card = "<table border=1><tr><td>借书证号</td><td>姓名</td><td>院系</td><td>类型</td></tr>";
                        while($stmt->fetch()) {
                            $Card = $Card."<tr><td>".$card_ID."</td><td>".$username."</td><td>".$department."</td><td>".$type."</td>"."</tr>";
                        }
                        $Card = $Card."</table><Br>"."<Br>";
                        echo "<script language=\"JavaScript\">
                                        var results = document.getElementById(\"result\");
                                        var Record = \"".$Card."\";
                                        results.innerHTML = Record;
                                    </script>";
                    }
                    else
                    {  
                        echo "<script language=\"JavaScript\">alert(\"There is no records! Check your input!\");</script>";
                        echo "<script>location='card_manage.php';</script>";
                    }
                }
            }
            else if(isset($_POST['insert_button'])&&$_POST['insert_button'] == "添加")
            {
                $card_ID = $_POST['card_ID'];
                $username = $_POST['username'];
                $department = $_POST['department'];
                $type = $_POST['type'];
                if($card_ID == "" || $username == "" || $department == "" || $type == "")
                {
                    echo "<script language=\"JavaScript\">alert(\"Please write all information!\");</script>";
                    echo "<script>location='card_manage.php';</script>";
                    
                }
                else
                {
                    $serverName = "localhost";
                    $uid = "3160101817";
                    $pwd = "123456";
                    $dbname = "library";
                    $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                    if($conn->connect_error)    //Data base connet failed
                    {
                        echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                        echo "<script>location='card_manage.php';</script>";
                    }
                    else
                    {
                        $sql = "INSERT INTO card (card_ID, username, department, type) VALUES(?,?,?,?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssss", $card_ID, $username, $department, $type);
                        $stmt->execute();
                        if($stmt->error)
                        {
                            echo "<script language=\"JavaScript\">alert(\"This card_ID is already exist!\");</script>";
                            echo "<script>location='card_manage.php';</script>";
                        }
                        echo "<script language=\"JavaScript\">alert(\"Insert successfully!\");</script>";
                        echo "<script>location='card_manage.php';</script>";
                    }
                }
            }
            else if(isset($_POST['delete_button'])&&$_POST['delete_button'] == "删除")
            {
                $card_ID = $_POST['card_ID'];
                
                if($card_ID == "")
                {
                    echo "<script language=\"JavaScript\">alert(\"card_ID can't be empty!\");</script>";
                    echo "<script>location='card_manage.php';</script>";
                }
                else
                {
                    $serverName = "localhost";
                    $uid = "3160101817";
                    $pwd = "123456";
                    $dbname = "library";
                    $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                    if($conn->connect_error)    //Data base connet failed
                    {
                        echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                        echo "<script>location='card_manage.php';</script>";
                    }
                    else
                    {
                        $sql = "SELECT * FROM card WHERE card_ID = '{$card_ID}'";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0)
                        {
                            $sql = "DELETE FROM card WHERE card_ID = '$card_ID'";
                            mysqli_query($conn, $sql);
                            echo "<script language=\"JavaScript\">alert(\"Delete successfully!\");</script>";
                            echo "<script>location='card_manage.php';</script>";
                        }
                        else
                        {
                            echo "<script language=\"JavaScript\">alert(\"The card_ID doesn't exist!\");</script>";
                            echo "<script>location='card_manage.php';</script>";
                        }
                    }
                }
            }
        ?>
    </body>
</html>