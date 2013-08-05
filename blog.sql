DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `username` varchar(20) NOT NULL,
    `password` text NOT NULL,
	`superuser` boolean DEFAULT FALSE,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
    `post_id` int NOT NULL AUTO_INCREMENT,
	`author` varchar(20) NOT NULL,
	`title` varchar(225) NOT NULL,
	`category` varchar(50) NOT NULL,
	`content` text NOT NULL,
	`visibility` boolean DEFAULT TRUE, 
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`post_id`)
);

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
    `comment_id` int NOT NULL AUTO_INCREMENT,
    `username` varchar(20) NOT NULL,
	`email` varchar(50) NOT NULL,
	`comment` text NOT NULL,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`post_id` int NOT NULL,
    PRIMARY KEY (`comment_id`)
);

UPDATE `users` SET `superuser`=1 WHERE `username`='dky';