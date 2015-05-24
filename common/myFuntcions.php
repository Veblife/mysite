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

