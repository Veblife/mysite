<?php
require_once './common/init.php';
$message = '';

/**
 * Если запрос POST и есть наша кнопка auth-user 
 * то мы попадаем в эту секцию
 */
if(count($_POST) && isset($_POST['auth-user'])){
    $errors = array();
    $validData = array();
    
    // check username
    if(isset($_POST['username']) && $_POST['username'] !== ''){
        $validData['username'] = $_POST['username'];
    }else{
        $errors[] = 'Ошибка username';
    }
    
    if( isset($_POST['password']) && strlen($_POST['password']) >= 6){
        $validData['password'] = $_POST['password'];
    }else{
        $errors[] = 'Ошибка password';
    }   
    
    if(count($errors)){
        $message = implode('<br>', $errors);
    }else{
        // если ошибок нет, вызываем функцию регистрация пользователя
        $has_auth = authUser($validData);
        
        // проверка того что вернула функция регистрации и отображение результата
        if(!$has_auth){
            $message = 'Авторизация прошла неудачно';
        }else{
            header("Location: /user.php");
        }
    }
}
include './templates/header.php';
?>
<h3>Авторизация:</h3>

<?php if($message): ?>
    <div style="border: 1px dotted red;">
        <span style="color: red;"><?= $message; ?></span>
    </div>
<?php endif; ?>

<form method="post" action="">
    <input type="text" name="username" placeholder="Username"  required /><br>
    <input type="password" name="password" placeholder="Password"  required/><br>
    <input type="submit" name="auth-user" value="Click me, please..." />
</form>

<?php
include './templates/footer.php';
