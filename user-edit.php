<?php
require_once './common/init.php';
accessUser();
require_once './templates/header.php';
include '/templates/linksForUsers.php';
//var_dump(date('Y-m-d'));
//var_dump(strtotime(date('Y-m-d'))).'<br>';
//$customDate = '2015-06-22';
//var_dump($customDate);
//var_dump(strtotime($customDate)).'<br>';
$userId = getIdUser();
$user = getUser($userId);

$username = isset($user['username']) ? $user['username'] : '';
$email = isset($user['email']) ? $user['email'] : '';
$birth_date = isset($user['birth_date']) ? $user['birth_date'] : '';
$avatar = isset($user['avatar']) ? $user['avatar'] : '';
$password = isset($user['password']) ? $user['password'] : false;

if (count($_POST)) {
    $errors = array();
    $validData = array();

    // проверяем, делаем ли мы редактирование профиля пользователя (username, email, birth_date...)
    if (isset($_POST['edit-user'])) {
        // check username
        $username = isset($_POST['username']) ? strip_tags($_POST['username']) : false;
        if ($username && $username !== '') {
            // если username правильный то мы его сохраняем в массив для сохранения в базе
            $validData['username'] = $username;
        } else {
            $errors[] = 'Ошибка username';
        }
        // check email
        $email = isset($_POST['email']) ? $_POST['email'] : false;
        if ($email 
                && 
            filter_var($email, FILTER_VALIDATE_EMAIL) 
                && 
            !hasEmailToOtherUsers($userId, $email)) {
            $validData['email'] = $email;
        } else {
            $errors[] = 'Ошибка email';
        }
        // check birth date
        $birthDatelErrorText = 'Ошибка даты рождения';
        $birth_date = isset($_POST['birth_date']) ? trim($_POST['birth_date']) : false;
        if ($birth_date) {
            $inputDateArr = explode('-', $birth_date);
            if (count($inputDateArr) != 3) {
                $errors[] = $birthDatelErrorText;
            } else {
                if (checkdate($inputDateArr[1], $inputDateArr[2], $inputDateArr[0]) &&
                        (strtotime(date('Y-m-d')) >= strtotime($birth_date))
                ) {
                    $validData['birth_date'] = $birth_date;
                } else {
                    $errors[] = $birthDatelErrorText;
                }
            }
        }elseif($birth_date === ''){
            $validData['birth_date'] = $birth_date;
        } else {
            $errors[] = $birthDatelErrorText;
        }
        
        if(!count($errors)){
            if(editUser($userId, $validData)){
                $goodMessage = 'Пользователь изменен';
            }else{
                $message = 'Пользователь не изменен';
            }
        }
    }elseif(isset($_POST['edit-password'])){
        $errors = array();
        $validData = array();
    
        $oldPassword = isset($_POST['old-password']) ? $_POST['old-password'] : false;
        if ($oldPassword && $oldPassword !== false && strlen($oldPassword) >= 6) {
            $validData['old-password'] = $oldPassword;
        } else {
            $errors[] = 'Ошибка Old password';
        }   
        $newPassword = isset($_POST['new-password']) ? $_POST['new-password'] : false;
        if ($newPassword && $newPassword !== false && strlen($newPassword) >= 6) {
            $validData['new-password'] = $newPassword;
        } else {
            $errors[] = 'Ошибка New password';
        }   
        $repeatNewPassword = isset($_POST['repeat-new-password']) ? $_POST['repeat-new-password'] : false;
        if ($repeatNewPassword && $repeatNewPassword !== false && strlen($repeatNewPassword) >= 6) {
            $validData['repeat-new-password'] = $repeatNewPassword;
        } else {
            $errors[] = 'Ошибка Repeat new password';
        }   
        
        if(!count($errors)){
            $checkOldPasswords = $password === hashString($validData['old-password']) ? true : false;
            $compareNewPasswords = $validData['new-password'] === $validData['repeat-new-password'] ? true : false;
            
            if($checkOldPasswords && $compareNewPasswords){
                $hashNewPassword = hashString($validData['new-password']);
                if(editUser($userId, array('password' => $hashNewPassword))){
                    $goodMessage = 'Пароль изменен';
                }else{
                    $message = 'Пароль не изменен';
                }                
            }elseif(!$checkOldPasswords){
                $message = 'Старые пароли не совпадают';
            }elseif(!$compareNewPasswords){
                $message = 'Новые пароли не совпадают';
            }
        }        
    }

    if (count($errors)) {
        $message = implode('<br>', $errors);
    }
    
    
} else {
    if ($userId) {
        $user = getUser($userId);
    }
}
?>
<h4>Редактирование профиля</h4>
<?php if (isset($message) && ($message)): ?>
    <div style="border: 1px dotted red;">
        <span style="color: red;"><?= $message; ?></span>
    </div>
<?php endif; ?>
<?php if (isset($goodMessage) && ($goodMessage)): ?>
    <div style="border: 1px dotted green;">
        <span style="color: green;"><?= $goodMessage; ?></span>
    </div>
<?php endif; ?>
<?php // не забудьте почитать про enctype="multipart/form-data" - это чтобы загружались файлы на сервер  ?>
<fieldset>
    <legend>Edit user profile:</legend>
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
                <td colspan="2" style="text-align: center;">
                    <input type="submit" name="edit-user" value="Редактировать пользователя" />
                </td>
            </tr>
        </table>   
    </form>    
</fieldset>

<fieldset>
    <legend>Edit password:</legend>
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Old Password</td>
                <td><input type="password" name="old-password" placeholder="Old password"  required /></td>
            </tr>
            <tr>
                <td>New Password</td>
                <td><input type="password" name="new-password" placeholder="New assword" required /></td>
            </tr> 
            <tr>
                <td>Repeat new password:</td>
                <td><input type="password" name="repeat-new-password" placeholder="Repeat new password" required /></td>
            </tr>    
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" name="edit-password" value="Изменить пароль" />
                </td>
            </tr>
        </table>   
    </form>    
</fieldset>

<?php
require_once './templates/footer.php';