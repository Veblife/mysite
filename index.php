<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>Tutorial: Super Simple Registration System With PHP & MySQL</title>

        <!-- Главный CSS файл -->
        <link href="assets/css/style.css" rel="stylesheet" />

        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>

        <form id="login-register" method="post" action="index.php">

            <h1>Login or Register</h1>

            <input type="text" placeholder="your@email.com" name="email" 
            autofocus />
            <p>Enter your email address above and we will send <br />you a login link.</p>

            <button type="submit">Login / Register</button>

            <span></span>

        </form>

        <!-- Подключение яваскрипт -->
        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="assets/js/script.js"></script>
<?php
require_once './common/init.php';
?>
<div>
    <?php /* Владик, почитай про относительніе и абсолютніе пути адресации */ ?>
    <a href="/register.php">Регистрация пользователя</a><br>
    <a href="/auth.php">Авторизация пользователя</a>
</div>

    </body>
</html>



