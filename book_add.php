<?php
    include 'check.php';
    include 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
    login();
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>图书入库页面</title>
        <link rel="stylesheet" type="text/css" href="add/add.css"/>
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

        <div id="Add">
            <label id="Hint">
                请输入图书信息：
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

                <p>
                    <label class="label_input">价格</label><input type="text" name="price" class="text_field"/>
                </p>

                <p>
                    <label class="label_input">数量</label><input type="text" name="number" class="text_field"/>
                </p>
                <div id="add_control">
                    <input type="submit" id="add_button" name="add_button" value="添加"/>
                </div>
            </form>
        </div>
        <div id = "File">
            <label id = "Hint">
                请选择Excel文件：
            </label>
            <form method="post" id = "SelectFile" enctype = "multipart/form-data">
                <p>
                    <label class="label_input">文件</label><input type="file" name="file" class="text_field"/>
                </p>
                <div id="add_control2">
                    <input type="submit" id="add_button2" name="add_button2" value="导入"/>
                </div>
            </form>
        </div>

        <?php
            function add($book_ID, $title, $type, $publisher, $year, $author, $price, $number)
            {
                if($book_ID != "")
                {
                    $serverName = "localhost";
                    $uid = "3160101817";
                    $pwd = "123456";
                    $dbname = "library";
                    $conn = new mysqli($serverName, $uid, $pwd, $dbname);

                    if($conn->connect_error)    //Data base connet failed
                    {
                        echo "<script language=\"JavaScript\">alert(\"Database connection failed! Try later!\");</script>";
                        echo "<script>location='book_add.php';</script>";
                    }
                    else
                    {
                        $sql = "SELECT * FROM book WHERE book_ID = '{$book_ID}'";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0)
                        {
                            $sql = "SELECT * FROM book WHERE
                                    (? = '' OR book_ID = ?) AND
                                    (? = '' OR title = ?) AND
                                    (? = '' OR type = ?) AND
                                    (? = '' OR publisher = ?) AND
                                    (? = '' OR year = ?) AND
                                    (? = '' OR author = ?)AND
                                    (? = '' OR price = ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssssssssiissdd", $book_ID, $book_ID, $title, $title, $type, $type, $publisher,
                                              $publisher, $year, $year, $author, $author, $price, $price);
                            $stmt->execute();
                            $stmt->store_result();
                            if($stmt->num_rows)
                            {
                                if($number > 0)
                                {
                                    $sql = "UPDATE book SET number = number + ?, store = store + ? WHERE book_ID = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("iis", $number, $number, $book_ID);
                                    $stmt->execute();

                                    echo "<script language=\"JavaScript\">alert(\"Add successfully!\");</script>";
                                    echo "<script>location='book_add.php';</script>";
                                }
                                else
                                {
                                    echo "<script language=\"JavaScript\">alert(\"The number can't be zero or negative!\");</script>";
                                    echo "<script>location='book_add.php';</script>";
                                }
                            }
                            else
                            {
                                echo "<script language=\"JavaScript\">alert(\"This book is already exist, but the information is wrong!\");</script>";
                                echo "<script>location='book_add.php';</script>";
                            }
                        }
                        else
                        {
                            if($title == "" || $type == "" || $publisher == "" || $year == "" || $author == "" || $price == "" || $number =="")
                            {
                                echo "<script language=\"JavaScript\">alert(\"Lack information!\");</script>";
                                echo "<script>location='book_add.php';</script>";
                            }
                            else
                            {
                                if($number > 0)
                                {
                                    $sql = "INSERT INTO book (book_ID, title, type, publisher, year, author, price, store, number)
                                            VALUES (?,?,?,?,?,?,?,?,?)";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("ssssisdii", $book_ID, $title, $type, $publisher, $year, $author, $price, $number, $number);
                                    $stmt->execute();

                                    echo "<script language=\"JavaScript\">alert(\"Add successfully!\");</script>";
                                    echo "<script>location='book_add.php';</script>";
                                }
                                else
                                {
                                    echo "<script language=\"JavaScript\">alert(\"The number can't be zero or negative!\");</script>";
                                    echo "<script>location='book_add.php';</script>";
                                }
                            }
                        }
                    }
                }
                else
                {
                    echo "<script language=\"JavaScript\">alert(\"The book_ID can't be empty!\");</script>";
                    echo "<script>location='book_add.php';</script>";
                }
            }
            if(isset($_POST['add_button'])&&$_POST['add_button'] == "添加")
            {
                $book_ID = $_POST['book_ID'];
                $title = $_POST['title'];
                $type = $_POST['type'];
                $publisher = $_POST['publisher'];
                $year = $_POST['year'];
                $author = $_POST['author'];
                $price = $_POST['price'];
                $number = $_POST['number'];
                add($book_ID, $title, $type, $publisher, $year, $author, $price, $number);
            }
            else if(isset($_POST['add_button2'])&&$_POST['add_button2'] == "导入")
            {
                if(!empty($_FILES['file']['name']))
                {
                    $temp = explode(".", $_FILES["file"]["name"]);
                    $extension = end($temp);
                    if(strtolower($extension) == "xls" || strtolower($extension) == "xlsx")
                    {
                        $tmp_file = $_FILES['file']['tmp_name'];
                        $path = 'book/';
                        $time = date('Ymdhis');
                        $file_name = $path.$time.".".$extension;
                        if(fopen("book/".$_FILES["file"]["name"], "w"))
                        {
                            unlink("book/".$_FILES["file"]["name"]);
                        }
                        copy($tmp_file, $file_name);

                        $reader = PHPExcel_IOFactory::createReader('Excel2007'); 
                        $PHPExcel = $reader->load($file_name);
                        $sheet = $PHPExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();
                        for ($row = 1; $row <= $highestRow; $row++)
                        {
                            $book_ID = $sheet->getCellByColumnAndRow(0, $row)->getValue();
                            $title = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                            $type = $sheet->getCellByColumnAndRow(2, $row)->getValue();
                            $publisher = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                            $year = $sheet->getCellByColumnAndRow(4, $row)->getValue();
                            $author = $sheet->getCellByColumnAndRow(5, $row)->getValue();
                            $price = $sheet->getCellByColumnAndRow(6, $row)->getValue();
                            $number = $sheet->getCellByColumnAndRow(7, $row)->getValue();

                            add($book_ID, $title, $type, $publisher, $year, $author, $price, $number);
                        }
                        echo "<script language=\"JavaScript\">alert(\"Add successfully!\");</script>";
                        echo "<script>location='book_add.php';</script>";
                    }
                    else
                    {
                        echo "<script language=\"JavaScript\">alert(\"Please choose a Excel file!\");</script>";
                        echo "<script>location='book_add.php';</script>";
                    }
                }
                else
                {
                    echo "<script language=\"JavaScript\">alert(\"Please choose a file!\");</script>";
                    echo "<script>location='book_add.php';</script>";
                }
            }
        ?>
    </body>
</html>