<?php
require_once './common/init.php';

$message = '';


if(count($_POST) && isset($_POST['register-user'])){
    $errors = array();
    $validData = array();
    
    // check username
    if(isset($_POST['username']) && $_POST['username'] !== ''){
        $validData['username'] = $_POST['username'];
    }else{
        $errors[] = 'Ошибка username';
    }
    
    // check email
    if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $validData['email'] = $_POST['email'];
    }else{
        $errors[] = 'Ошибка email';
    }    
    
    if( isset($_POST['password']) && strlen($_POST['password']) >= 6){
        $validData['password'] = $_POST['password'];
    }else{
        $errors[] = 'Ошибка password';
    }   
    
    if(count($errors)){
        $message = implode('<br>', $errors);
    }
    echo 'register zapros<br>';
    var_dump($validData);
}





include './templates/header.php';
?>
<h3>Регистрация:</h3>

<?php if($message): ?>
    <div style="border: 1px dotted red;">
        <span style="color: red;"><?= $message; ?></span>
    </div>
<?php endif; ?>

<form method="post" action="">
    <input type="text" name="username" placeholder="Username" value="Vasya" required /><br>
    <input type="email" name="email" placeholder="Email" value="test@mail.ru" required/><br>
    <input type="password" name="password" placeholder="Password" value="123456" required/><br>
    <input type="submit" name="register-user" value="Click me, please..." />
</form>


<?php
include './templates/footer.php';
