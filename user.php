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
        $register_date = $user['register_date'];
        $birth_date = $user['birth_date'];
        $avatar = $user['avatar'];
    }
    
}


?>
<h3>Страница пользователя</h3>

<table>
    <tr>
        <td>Username:</td>
        <td><?= $username; ?></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><?= $email; ?></td>
    </tr>  
    <tr>
        <td>Register date:</td>
        <td><?= $register_date; ?></td>
    </tr>   
    <tr>
        <td>Birth date:</td>
        <td><?= $birth_date; ?></td>
    </tr> 
    <tr>
        <td>Avatar:</td>
        <td><?= $avatar; ?></td>
    </tr> 
    <tr>
        <td></td>
        <td></td>
    </tr>     
</table>
<a href="/user-edit.php">Редактирование профиля</a>
<?php
require_once './templates/footer.php';


