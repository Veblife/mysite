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

$username = '';
$email = '';
$birth_date = '';
$avatar = '';

if (count($_POST)) {
    $errors = array();
    $validData = array();

    if (isset($_POST['edit-user'])) {
        // check username
        if (isset($_POST['username']) && strip_tags($_POST['username']) !== '') {
            // если username правильный то мы его сохраняем в массив для сохранения в базе
            // и в переменную $username чтобы отобразить пользователю, чтобы снова не вводить
            $username = $validData['username'] = strip_tags($_POST['username']);
        } else {
            $errors[] = 'Ошибка username';
        }

        // check email
        if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $email = $validData['email'] = $_POST['email'];
        } else {
            $errors[] = 'Ошибка email';
        }

        // check birth date
        $birthDatelErrorText = 'Ошибка даты рождения';
        if (isset($_POST['birth_date'])) {
            $inputDate = trim($_POST['birth_date']);
            $inputDateArr = explode('-', $inputDate);
            if (count($inputDateArr) != 3) {
                $errors[] = $birthDatelErrorText;
            } else {
                if (checkdate($inputDateArr[1], $inputDateArr[2], $inputDateArr[0]) &&
                        (strtotime(date('Y-m-d')) >= strtotime($inputDate))
                ) {
                    $birth_date = $validData['birth_date'] = $inputDate;
                } else {
                    $errors[] = $birthDatelErrorText;
                }
            }
        } else {
            $errors[] = $birthDatelErrorText;
        }

    }elseif(isset($_POST['edit-password'])){
        if ($userId) {
            $user = getUser($userId);
        }
        if (isset($_POST['password']) && strlen($_POST['password']) >= 6) {
            $validData['password'] = $_POST['password'];
        } else {
            $errors[] = 'Ошибка password';
        }        
    }


    if (count($errors)) {
        $message = implode('<br>', $errors);
    } else {
        
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
<?php // не забудьте почитать про enctype="multipart/form-data" - это чтобы загружались файлы на сервер  ?>
<fieldset>
    <legend>Edit user profile:</legend>
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" placeholder="Username"  value="<?= $user['username']; ?>" required /></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" placeholder="Email"  value="<?= $user['email']; ?>" required/></td>
            </tr>  
            <tr>
                <td>Birth date:</td>
                <td><input type="text" class="datepicker" name="birth_date" placeholder="Birth date"  value="<?= $user['birth_date']; ?>" /></td>
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
                <td>Password</td>
                <td><input type="password" name="password" placeholder="Password"  /></td>
            </tr> 
            <tr>
                <td>Repeat password:</td>
                <td><input type="password" name="repeat-password" placeholder="Repeat password"  /></td>
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


