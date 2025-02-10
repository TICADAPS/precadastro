<?php

define('APP_NAME',  'Agência Brasileira de Apoio à Gestão do SUS');

// logs
define('LOGS_PATH',         __DIR__ . '/../logs/app.log');

// openssl
define('OPENSSL_KEY',       'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa');
define('OPENSSL_IV',        'BzKAbjuREsHgnw56');

// database
define('DB_SERVER', 'localhost');
define('DB_NAME', 'u226895969_preincricao');
define('DB_CHARSET', 'utf8');
define('DB_USERNAME', 'u226895969_user_root');
define('DB_PASSWORD', 'Senha10adaps');

define('MYSQL_AES_KEY', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD');

/**
 * PHPMAIL
 */
define("CONF_MAIL_HOST", "smtp.gmail.com");
define("CONF_MAIL_PORT", "587");
define("CONF_MAIL_USER", "noreply@agenciasus.org.br");
//define("CONF_MAIL_PASS", "@AgsusRH@2023");
define("CONF_MAIL_PASS", "jwtx rysi zttg wdeo");
define("CONF_MAIL_SENDER", ["name" => "AgSUS (no-reply)", "address" => "noreply@agenciasus.org.br"]);
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");
