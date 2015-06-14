<?php
require_once './common/init.php';
require_once './templates/header.php';
?>

<?php 
    /**
     * отображаем ссылки авторизации и регистрации для незарегистрированных пользователей
     * и ссылки перехода на страницу пользователя и т.д. для авторизованных пользователей
     */
    include '/templates/linksForUsers.php'; 
?>

<?php
require_once './templates/footer.php';


