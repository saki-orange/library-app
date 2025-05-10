<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
// $db['dsn'] = 'mysql:host=localhost;dbname=yii2basic_test';
$db['dsn'] = 'pgsql:host=db;port=5432;dbname=' . $_ENV['TEST_DB_NAME'];

return $db;
