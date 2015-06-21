<?php
require_once './common/init.php';
destroySession();
//  после разлогирования переходим в корень сайта
header("Location: /");
