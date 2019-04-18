<?php
    include 'check.php';
    login();
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>查询记录页面</title>
        <link rel="stylesheet" type="text/css" href="record/record.css"/>
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

        <div id="Search">
            <label id="Hint">
                请输入检索信息：
            </label>

            <form method="post" id="Condition">
                <p>
                    <label class="label_input">借书证号</label><input type="text" name="card_ID" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">书籍编号</label><input type="text" name="book_ID" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">管理员账号</label><input type="text" name="manager_ID" class="text_field"/>
                </p>


                <div id="search_control">
                    <input type="submit" id="search_button" name="search_button" value="查询"/>
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
                $book_ID = $_POST['book_ID'];
                $manager_ID = $_POST['manager_ID'];

                $serverName = "localhost";
                $uid = "3160101817";
                $pwd = "123456";
                $dbname = "library";
                $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                if($conn->connect_error)    //Data base connet failed
                {
                    echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                    echo "<script>location='record_search.php';</script>";
                }
                else
                {
                    $sql = "SELECT * FROM record WHERE
                            (? = '' OR card_ID = ?) AND
                            (? = '' OR book_ID = ?) AND
                            (? = '' OR manager_ID = ?) ORDER BY borrow_time";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssss", $card_ID, $card_ID, $book_ID, $book_ID, $manager_ID, $manager_ID);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($book_ID, $card_ID, $borrow_time, $return_time, $manager_ID);
                    if($stmt->num_rows)
                    {
                        $Records = "<table border=1><tr><td>书籍编号</td><td>借书证号</td><td>借阅时间</td><td>归还时间</td><td>管理员账号</td></tr>";
                        while($stmt->fetch()) {
                            $Records = $Records."<tr><td>".$book_ID."</td><td>".$card_ID."</td><td>".$borrow_time."</td><td>".$return_time."</td><td>".$manager_ID."</td>"."</tr>";
                        }
                        $Records = $Records."</table><Br>"."<Br>";
                        echo "<script language=\"JavaScript\">
                                        var results = document.getElementById(\"result\");
                                        var Record = \"".$Records."\";
                                        results.innerHTML = Record;
                                    </script>";
                    }
                    else
                    {  
                        echo "<script language=\"JavaScript\">alert(\"There is no records! Check your input!\");</script>";
                        echo "<script>location='record_search.php';</script>";
                    }
                }
            }
        ?>
    </body>
</html>