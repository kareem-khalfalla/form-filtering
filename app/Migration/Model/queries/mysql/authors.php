<?php

return "
    CREATE TABLE IF NOT EXISTS `authors` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
)    ENGINE=InnoDB DEFAULT CHARSET=utf8
";
