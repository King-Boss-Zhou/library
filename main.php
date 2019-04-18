<?php
    include 'check.php';
    login();
?>
<html lang="en">
<head>
    <meta charset = "UFT-8">
    <title>主页</title>
    <link rel = "stylesheet" type = "text/css" href = "main/main.css"/>
</head>
<body>
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

    <div id = "Menu_Frame">
        <div class = "SubMenu_Frame">
            <div class = "Icon">
                <a href = "book_search.php">
                    <img src = "book/search.png" class = "Image"></img>
                </a>
            </div>
            <p class = "Description">搜索</p>
        </div>

        <div class = "SubMenu_Frame">
            <div class = "Icon">
                <a href = "book_borrow.php">
                    <img src = "book/borrow.png" class = "Image"></img>
                </a>
            </div>
            <p class = "Description">借书</p>
        </div>

        <div class = "SubMenu_Frame">
            <div class = "Icon">
                <a href = "book_return.php">
                    <img src = "book/return.png" class = "Image"></img>
                </a>
            </div>
            <p class = "Description">还书</p>
        </div>

        <div class = "SubMenu_Frame">
            <div class = "Icon">
                <a href = "book_add.php">
                    <img src = "book/add.png" class = "Image"></img>
                </a>
            </div>
            <p class = "Description">图书入库</p>
        </div>

        <div class = "SubMenu_Frame">
            <div class = "Icon">
                <a href = "record_search.php">
                    <img src = "book/record.png" class = "Image"></img>
                </a>
            </div>
            <p class = "Description">借阅记录</p>
        </div>

        <div class = "SubMenu_Frame">
            <div class = "Icon">
                <a href = "card_manage.php">
                    <img src = "book/card.png" class = "Image"></img>
                </a>
            </div>
            <p class = "Description">借阅证管理</p>
        </div>
    </div>
</body>
</html>

