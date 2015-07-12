<?php
function startSession() {
    // Если сессия уже была запущена, прекращаем выполнение и возвращаем TRUE
    // (параметр session.auto_start в файле настроек php.ini должен быть выключен - значение по умолчанию)
    if ( session_id() ) return true;
    else return session_start();
    // Примечание: До версии 5.3.0 функция session_start()возвращала TRUE даже в случае ошибки.
    // Если вы используете версию ниже 5.3.0, выполняйте дополнительную проверку session_id()
    // после вызова session_start()
}

function destroySession() {
    if ( session_id() ) {
        // Если есть активная сессия, удаляем куки сессии,
        setcookie(session_name(), session_id(), time()-60*60*24);
        // и уничтожаем сессию
        session_unset();
        session_destroy();
    }
}

// функция регистрация пользователя
function register_user(array $data){
    // это у нас будут имена ключей, которые будут проверяться во входящем массива
    $goodKeys = ['username','email','password'];
    
    // делаю проверку на то, чтобы во входящем массиве $data были нужные мне ключи массива
    // если нужных ключей нет, выходим с функции
    foreach($goodKeys as $goodKey){
        if(!array_key_exists($goodKey, $data)){
            return array('error'=>'ошибка входящих данных');
        }
    }
    
    // проверяем существует ли пользователь
    if(hasUser($data))
    {
        return array('error'=>'пользователь уже существует');
    }
    
    // example: INSERT INTO users (username, email, password) VALUES ("vasya", "test@mail.ru", "fldgjeo34097gfldjgdslddfgdfg")
    $regUserSql = 'INSERT INTO users (username, email, password) '
            . 'VALUES ('
            . '"'.mysql_escape_string($data['username']).'", '
            . '"'.mysql_escape_string($data['email']).'", '
            . '"'.hashString($data['password']).'"'
            . ')';
    
    // выполняю запрос в базу данных, вставляю строчку. 
    // Если запрос удачный, то возвращает true или false. Если не удалось вставить строчку
    $result = mysql_query($regUserSql);
    
    if(!$result){
        return array('error' => 'Ошибка сохранения пользователя');
    }
    
    // получаю id нового созданного пользователя (почитайте про эту функцию mysql_insert_id)
    $userId = mysql_insert_id();
    
    return array('complete'=>$userId);
}

// функция проверки существует ли пользователь с заданным мылом и юзернеймом
function hasUser(array $data)
{
    // формируем запрос на выборку пользователя с задающим юзернейм и мылом
    // example: SELECT * FROM users WHERE username = "vasya" AND email = "test@mail.ru"
    $sqlHasUser = 
            'SELECT * FROM users '
            . 'WHERE username = "'.mysql_real_escape_string($data['username']).'" '
            . 'AND email = "'.mysql_real_escape_string($data['email']).'"';
    
    // выполняю запрос в базу данных
    $result = mysql_query($sqlHasUser);
    // проверяю количество возвращенных строк
    $num_rows = mysql_num_rows($result);
    // если $num_rows > 0  то возвращает true, 
    // если не нашло пользователя то возвращает fals 
    return $num_rows ? true : false;
}

function authUser(array $data)
{
    // запуск сессии
    startSession();
    // если мы уже делали авторизацию пользователя в предыдущих запросах, то просто выходим из функции с положительным результатом
    if(isset($_SESSION['user_id'])){
        return true;
    }
    // получаем пользователя из базы, если он есть
    $sql = 'SELECT * FROM users WHERE username = "'. mysql_escape_string($data['username']).'" AND password = "'. hashString($data['password']).'"';
    $result = mysql_query($sql);
    // если запрос неудачный (пользователя нет) то выходим из функции с false
    if(!$result){
        return false;
    }
    $row = mysql_fetch_array($result);
    
    // если в полученной строчке пустой массив то тоже выходим
    if(!count($row)){
        return false;
    }
    
    // если регистрация удачная, то заносим в сессию его id
    $_SESSION['user_id'] = $row['id'];
    return true;
}

// шифрование строки
function hashString($str)
{
    return md5(md5($str));
}

// если пользователь не авторизован то мы отправляем его на авторизацию.
function accessUser()
{
    if(!isset($_SESSION['user_id'])){
        header('Location: /auth.php');
    }
}

function getIdUser()
{
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
}

// функция, которая возвращает данные пользователя из базы по его id
function getUser($userId)
{
    $sql = 'SELECT * FROM users WHERE id = '.$userId;
    $result = mysql_query($sql);
    if(!$result){
        return false;
    }
  
    return mysql_fetch_array($result);
}

// функция изменения профиля пользователя
function editUser($userId, array $data)
{
    // если в дату ничего не передали, то фунцкия заканчивается т.к. не с чем работать
    if(!count($data)){
        return false;
    }
    
    $tmpArr = array(); // сюда заносим сконфигурированный для базы данных пары ключ=значения
    foreach($data as $key => $value){
        $tmpArr[] = $key.' = "'.$value.'"';
    }
    $sql = 'UPDATE users SET '.implode(', ',$tmpArr).' WHERE id = '.$userId;
    
    echo $sql;
    
}

// делаем проверка существования мыла в базе
function hasEmailToOtherUsers($userId, $email)
{
    $sql = 'SELECT * FROM users WHERE id != '.$userId.' AND email = "'.mysql_escape_string($email).'"';
    $result = mysql_query($sql);
    return mysql_fetch_array($result) ? true : false;
}

