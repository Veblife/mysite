<?php
require_once './common/init.php';

include './templates/header.php';
?>

require_once 'includes/main.php';

$user = new User();

if(!$user->loggedIn()){
    redirect('index.php');
}

Регистрация:<br>
<form method="post" action="">
    <input type="text" name="username" placeholder="Insert Username" value=""/>
</form>


<?php
include './templates/footer.php';
