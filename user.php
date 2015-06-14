<?php
require_once './common/init.php';
accessUser();
require_once './templates/header.php';
include '/templates/linksForUsers.php'; 
?>
<h3>Страница пользователя</h3>
<h4>Редактирование профиля</h4>
<form method="post">
    <input type="text" name="username" placeholder="Username"  required /><br>
    <input type="email" name="email" placeholder="Email"  required/><br>
    <input type="password" name="password" placeholder="Password"  required/><br>
    <input type="submit" name="register-user" value="Click me, please..." />    
</form>

<?php
require_once './templates/footer.php';


