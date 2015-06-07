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
    
    $regUserSql = 'INSERT INTO users (username, email, password) '
            . 'VALUES ('
            . '"'.mysql_escape_string($data['username']).'", '
            . '"'.mysql_escape_string($data['email']).'", '
            . '"'.mysql_escape_string($data['password']).'"'
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
