<?php

$config = [
    'host' => 'localhost',
    'dbname' => 'mycroblog',
    'username' => 'root',
    'password' => 'qsdfqsdf'
];

$config = new \ArrayObject($config, \ArrayObject::ARRAY_AS_PROPS);

/**
 * Table creation
 */
try {
    // Set connection
    $dsn = sprintf('mysql:dbname=%s;host=%s;charset=utf8', $config->dbname, $config->host);
    $pdo = new PDO($dsn, $config->username, $config->password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = [
        'page' => [
            'create' => "DROP TABLE IF EXISTS `page`; CREATE TABLE `page` (
                `id` INT AUTO_INCREMENT NOT NULL,
                `url` VARCHAR(255),
                `title` VARCHAR(255),
                `content` VARCHAR(255),
                PRIMARY KEY (`id`))
                CHARACTER SET utf8 COLLATE utf8_unicode_ci",
            'seed' => [
                '`url`, `title` , `content`' => [
                    ['index', 'Home', '<h1>Welcome!</h1>'],
                    ['url1', 'title1', '<h1>This is some content1</h1>'],
                    ['url2', 'title2', 'content2'],
                    ['url3', 'title3', 'content3']
                ]
            ]
        ],
        'user' => [
            'create' => "DROP TABLE IF EXISTS `user`; CREATE TABLE `user` (
                `id` INT AUTO_INCREMENT NOT NULL,
                `username` VARCHAR(20),
                `password` VARCHAR(64),
                `salt` VARCHAR(64),
                `email` VARCHAR(255),
                `joined` DATETIME,
                PRIMARY KEY (`id`))
                CHARACTER SET utf8 COLLATE utf8_unicode_ci",
            'seed' => [
                '`username`, `password` , `salt`, `email`, `joined`' => [
                    //['asdfasdf', 'asdfasdf', 'salt', 'asdf@asdf.as', '2015-06-05 00:00:00'],
                    ['asdfasdf', hash('sha256', 'asdfasdf' . 'salt'), 'salt', 'asdf@asdf.as', '2015-06-05 00:00:00'],
                    ['joe', 'asdfasdf', 'salt', 'joe@asdf.as', '2015-06-05 00:00:00']
                ]
            ]
        ],
        'user_session' => [
            'create' => "DROP TABLE IF EXISTS `user_session`; CREATE TABLE `user_session` (
                `id` INT AUTO_INCREMENT NOT NULL,
                `userId` INT,
                `hash` VARCHAR(64),
                PRIMARY KEY (`id`))
                CHARACTER SET utf8 COLLATE utf8_unicode_ci",
        ]
    ];
    
    // Create tables if they do not exist already
        
    foreach ($tables as $tableName => $actions) {
        foreach ($actions as $action => $data) {
            if ('create' === $action) {
                $stmt = $pdo->exec($data);

                //return $pdo;
                echo 'Table "' . $tableName . '" successfully created!<br>';
            } else if ('seed' === $action) {
                foreach ($data as $columnNames => $values) {
                    $sql = sprintf('INSERT INTO `%s`(%s) VALUES ', $tableName, $columnNames);

                    foreach ($values as $value) {
                        $sql .= sprintf('("%s"), ', implode('", "', $value));
                    }

                    //die('SQL statement: "' . rtrim($sql, ' ,') . '"<br />');
                    $stmt = $pdo->exec(rtrim($sql, ' ,'));
                    echo 'Content inserted in "' . $tableName . '"!<br />';
                }
            }
        }

    }

} catch (PDOException $pe) {
    throw new Exception('Error while setting database connection (error message: "' . $pe->getMessage() . '").');
}