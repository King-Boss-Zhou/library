<?php
    include 'check.php';
    login();
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>搜索页面</title>
        <link rel="stylesheet" type="text/css" href="search/search.css"/>
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
                    <label class="label_input">书籍编号</label><input type="text" name="book_ID" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">书名</label><input type="text" name="title" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">类别</label><input type="text" name="type" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">出版社</label><input type="text" name="publisher" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">出版年份</label><input type="text" name="year" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">作者</label><input type="text" name="author" class="text_field"/>
                </p>

                <div id="search_control">
                    <input type="submit" id="search_button" name="search_button" value="搜索"/>
                </div>
            </form>
        </div>

        <div id="Results">
            <label id="Hint">
                搜索结果：
            </label>

            <label id="result">

            </label>
        </div>
        <?php
            if(isset($_POST['search_button']) && $_POST['search_button'] == "搜索")
            {
                $book_ID = trim($_POST['book_ID']);
                $title = trim($_POST['title']);
                $type = trim($_POST['type']);
                $publisher = trim($_POST['publisher']);
                $year = trim($_POST['year']);
                $author = trim($_POST['author']);

                $serverName = "localhost";
                $uid = "3160101817";
                $pwd = "123456";
                $dbname = "library";
                $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                if($conn->connect_error)    //Data base connet failed
                {
                    echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                    echo "<script>location='book_search.php';</script>";
                }
                else
                {
                    $sql = "SELECT * FROM book WHERE
                            (? = '' OR book_ID = ?) AND
                            (? = '' OR title = ?) AND
                            (? = '' OR type = ?) AND
                            (? = '' OR publisher = ?) AND
                            (? = '' OR year = ?) AND
                            (? = '' OR author = ?) ORDER BY book_ID";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssssssiiss", $book_ID, $book_ID,
                                    $title, $title,
                                    $type, $type,
                                    $publisher, $publisher,
                                    $year, $year,
                                    $author, $author);
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
                        echo "<script language=\"JavaScript\">alert(\"There is no records! Check your input!\");</script>";
                        echo "<script>location='book_search.php';</script>";
                    }
                }
            }
        ?>
    </body>
</html>