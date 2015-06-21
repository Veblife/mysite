<?php // если пользователь в системе не авторизован то ссылки авторизации и регистрации мы показываем ?>
<div>
    <?php if(!isset($_SESSION['user_id'])): ?>
    <?php /* Владик, почитай про относительніе и абсолютніе пути адресации */ ?>
        <a href="/register.php">Регистрация пользователя</a><br>
        <a href="/auth.php">Авторизация пользователя</a><br>
    <?php else: ?>
        <?php if($_SERVER['REQUEST_URI'] !== '/user.php'): ?>
            <a href="/user.php">Cтраница пользователя</a><br>
        <?php endif; ?>
            
        <a href="/logout.php">Выход</a><br>   
        <a href="/lesson-create.php">Создание статьи</a><br>
        <a href="/lesson-all.php">Список статей</a><br>
    <?php endif; ?>
</div>

    


