<?php
require_once './common/init.php';
accessUser();
require_once './templates/header.php';
include '/templates/linksForUsers.php'; 

$userId = getIdUser();
$username = '';
$email = '';
$register_date = '';
$birth_date = '';
$avatar = '';
        

if($userId){
    if($user = getUser($userId)){
        $username = $user['username'];
        $email = $user['email'];
        $birth_date = $user['birth_date'];
        $avatar = $user['avatar'];
    }
    
}


?>
<h3>Страница пользователя</h3>
<h4>Редактирование профиля</h4>
<form method="post">
    <input type="text" name="username" placeholder="Username"  value="<?= $username; ?>" required /><br>
    <input type="email" name="email" placeholder="Email"  value="<?= $email; ?>" required/><br>
    <input type="text" name="birth_date" placeholder="Birth date"  value="<?= $birth_date; ?>" /><br>
    <input type="text" name="avatar" placeholder="Avatar"  value="<?= $avatar; ?>" /><br>
    <input type="password" name="password" placeholder="Password"  /><br>
    <input type="submit" name="register-user" value="Click me, please..." />    
</form>

<?php
require_once './templates/footer.php';


