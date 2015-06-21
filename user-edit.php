<?php
require_once './common/init.php';
accessUser();
require_once './templates/header.php';
include '/templates/linksForUsers.php'; 
var_dump(date('Y-m-d'));
var_dump(strtotime(date('Y-m-d'))).'<br>';

$customDate = '2015-06-22';
var_dump($customDate);
var_dump(strtotime($customDate)).'<br>';
$userId = getIdUser();

$username = '';
$email = '';
$birth_date = '';
$avatar = '';

if (count($_POST) && isset($_POST['edit-user'])) {
    
    $errors = array();
    $validData = array();
    
    // check username
    if(isset($_POST['username']) && strip_tags($_POST['username']) !== ''){
        $validData['username'] = strip_tags($_POST['username']);
    }else{
        $errors[] = 'Ошибка username';
    }
    
    // check email
    if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $validData['email'] = $_POST['email'];
    }else{
        $errors[] = 'Ошибка email';
    }    

    // check birth date
    $birthDatelErrorText = 'Ошибка даты рождения';
    if(isset($_POST['birth_date'])){
        $inputDate = trim($_POST['birth_date']);
        $inputDateArr = implode('-', $inputDate);
        if(count($inputDateArr) != 3){
            $errors[] = $birthDatelErrorText;
        }else{
            if(checkdate($inputDateArr[1], $inputDateArr[2], $inputDateArr[0])
                    && 
               (strtotime(date('Y-m-d')) >= strtotime($inputDate))  
                    ){
                $validData['birth_date'] = $inputDate;
            }else{
               $errors[] = $birthDatelErrorText;
                
            }
        }
    }else{
        $errors[] = $birthDatelErrorText;
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
        $has_register = register_user($validData);
        
        // проверка того что вернула функция регистрации и отображение результата
        if(isset($has_register['error'])){
            $message = $has_register['error'];
        }elseif($has_register['complete']){
            $has_auth = authUser($validData);
            echo 'and auth result: '.$has_auth.'<br>';
            echo 'YEEEESSSS, I\'m new user: "'.$has_register['complete'].'"<br>';
        }
    }    
    
} else {
    if ($userId) {
        if ($user = getUser($userId)) {
            $username = $user['username'];
            $email = $user['email'];
            $birth_date = $user['birth_date'];
            $avatar = $user['avatar'];
        }
    }
}
?>
<h4>Редактирование профиля</h4>
<?php if($message): ?>
    <div style="border: 1px dotted red;">
        <span style="color: red;"><?= $message; ?></span>
    </div>
<?php endif; ?>
<?php // не забудьте почитать про enctype="multipart/form-data" - это чтобы загружались файлы на сервер ?>
<form method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="username" placeholder="Username"  value="<?= $username; ?>" required /></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="email" name="email" placeholder="Email"  value="<?= $email; ?>" required/></td>
        </tr>  
        <tr>
            <td>Birth date:</td>
            <td><input type="text" class="datepicker" name="birth_date" placeholder="Birth date"  value="<?= $birth_date; ?>" /></td>
        </tr> 
        <tr>
            <td>Avatar:</td>
            <td><input type="file" name="avatar" placeholder="Avatar"  value="Add file..." /></td>
        </tr> 
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" placeholder="Password"  /></td>
        </tr> 
        <tr>
            <td>Repeat password:</td>
            <td><input type="password" name="repeat-password" placeholder="Repeat password"  /></td>
        </tr>    
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" name="edit-user" value="Сохранить изменения" />
            </td>
        </tr>
    </table>   
        
</form>


<?php
require_once './templates/footer.php';


