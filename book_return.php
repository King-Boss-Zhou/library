<?php
    include 'check.php';
    login();
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>还书页面</title>
        <link rel="stylesheet" type="text/css" href="return/return.css"/>
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

        <div id="Return">
            <label id="Hint">
                请输入查询/归还信息：
            </label>

            <form method="post" id="Condition">
                <p>
                    <label class="label_input">借书证号</label><input type="text" name="card_ID" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">书籍编号</label><input type="text" name="book_ID" class="text_field"/>
                </p>

                <div id="return_control">
                    <input type="submit" id="search_button" name="search_button" value="查询"/>
                    <input type="submit" id="return_button" name="return_button" value="归还"/>
                </div>
            </form>
        </div>

        <div id="Results">
            <label id="Hint">
                查询借阅结果：
            </label>

            <label id="result">

            </label>
        </div>

        <?php
            if(isset($_POST['return_button']) && $_POST['return_button'] == "归还")
            {
                $card_ID = trim($_POST['card_ID']);
                $book_ID = trim($_POST['book_ID']);
                $store = 0;
                date_default_timezone_set("Asia/Shanghai");
                $time = date("Y-m-d H:i:s", time());

                $serverName = "localhost";
                $uid = "3160101817";
                $pwd = "123456";
                $dbname = "library";
                $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                if($conn->connect_error)    //Data base connet failed
                {
                    echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                    echo "<script>location='book_return.php';</script>";
                }
                else
                {
                    $sql = "SELECT card_ID FROM card WHERE card_ID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $card_ID);
                    $stmt->execute();
                    $stmt->store_result();
                    if($stmt->num_rows)
                    {
                        $sql = "SELECT * FROM book WHERE book_ID = ?";
                        $stmt1 = $conn->prepare($sql);
                        $stmt1->bind_param("s", $book_ID);
                        $stmt1->execute();
                        $stmt1->store_result();
                        if($stmt1->num_rows)
                        {
                            $sql = "SELECT min(borrow_time) FROM record WHERE card_ID = ? AND book_ID = ? AND (return_time is NULL)";
                            $stmt2 = $conn->prepare($sql);
                            $stmt2->bind_param("ss", $card_ID, $book_ID);
                            $stmt2->execute();
                            $stmt2->store_result();
                            $stmt2->bind_result($borrow_time);
                            $stmt2->fetch();
                            if($borrow_time != NULL)
                            {
                                $sql = "UPDATE record SET return_time = ? WHERE card_ID = ? AND book_ID = ? AND borrow_time = ? AND return_time IS NULL ";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssss", $time, $card_ID, $book_ID, $borrow_time);
                                $stmt->execute();

                                $sql = "UPDATE book SET store = store + 1 WHERE book_ID = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $book_ID);
                                $stmt->execute();

                                echo "<script language=\"JavaScript\">alert(\"Return successfully!\");</script>";
                                echo "<script>location='book_return.php';</script>";
                            }
                            else
                            {
                                echo "<script language=\"JavaScript\">alert(\"There is no borrow records!\");</script>";
                                echo "<script>location='book_return.php';</script>";
                            }
                        }
                        else
                        {
                            echo "<script language=\"JavaScript\">alert(\"Please check your book_ID!\");</script>";
                            echo "<script>location='book_return.php';</script>";
                        }
                    }
                    else
                    {
                        echo "<script language=\"JavaScript\">alert(\"Please check your card_ID!\");</script>";
                        echo "<script>location='book_return.php';</script>";
                    }
                }
            }
            else if(isset($_POST['search_button'])&&$_POST['search_button'] == "查询")
            {
                $card_ID = trim($_POST['card_ID']);

                $serverName = "localhost";
                $uid = "3160101817";
                $pwd = "123456";
                $dbname = "library";
                $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                if($conn->connect_error)    //Data base connet failed
                {
                    echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                    echo "<script>location='book_return.php';</script>";
                }
                else
                {
                    $sql = "SELECT card_ID FROM card WHERE card_ID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $card_ID);
                    $stmt->execute();
                    $stmt->store_result();
                    if($stmt->num_rows)
                    {
                        $sql = "SELECT book.* FROM book, record WHERE record.card_ID = ? AND book.book_ID = record.book_ID AND record.return_time is NULL";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $card_ID);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->bind_result($book_ID, $title, $type, $publisher, $year, $author, $price, $store, $number);
                        if($stmt->num_rows)
                        {
                            $Book = "<table border=1><tr><td>书籍编号</td><td>书名</td><td>类别</td><td>出版社</td><td>出版年份</td><td>作者</td><td>价格</td><td>库存</td><td>总量</td> </tr>";
                            while($stmt->fetch()) {
                            $Book = $Book."<tr><td>".$book_ID."</td><td>".$title."</td><td>".$type."</td><td>".$publisher."</td><td>".$year."</td><td>".$author."</td><td>".$price."</td><td>"
                                                .$store."</td><td>".$number."</td></tr>";
                        }
                            $Book = $Book."</table><Br>"."<Br>";
                            echo "<script language=\"JavaScript\">
                                        var results = document.getElementById(\"result\");
                                        var Record = \"".$Book."\";
                                        results.innerHTML = Record;
                                    </script>";
                        }
                        else
                        {
                            echo "<script language=\"JavaScript\">alert(\"There is no borrow records for this card_ID!\");</script>";
                            echo "<script>location='book_return.php';</script>";
                        }
                    }
                    else
                    {
                        echo "<script language=\"JavaScript\">alert(\"Please check your card_ID!\");</script>";
                        echo "<script>location='book_return.php';</script>";
                    }
                }
            }
        ?>
    </body>
</html>