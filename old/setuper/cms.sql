SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `prefix_admin_contents`;
CREATE TABLE IF NOT EXISTS `prefix_admin_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `prefix_admin_contents` (`id`, `title`, `url`) VALUES
(1, 'Редактор блоков', 'blocks'),
(2, 'Менеджер скриптов', 'scripts');

DROP TABLE IF EXISTS `prefix_admin_main_menu`;
CREATE TABLE IF NOT EXISTS `prefix_admin_main_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content_ids` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `prefix_admin_main_menu` (`id`, `title`, `content_ids`) VALUES
(1, 'Основные', '1,2');

DROP TABLE IF EXISTS `prefix_blocks`;
CREATE TABLE IF NOT EXISTS `prefix_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos` text NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `css_class` text NOT NULL,
  `css_id` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

INSERT INTO `prefix_blocks` (`id`, `pos`, `title`, `content`, `css_class`, `css_id`) VALUES
(12, '', 'block r', 'This is a right block of site', '', ''),
(13, '', 'block l', 'This is a left block of the site', '', ''),
(14, '', 'logo', '<h1><a href="%adress%">The Best Minecraft Server</a></h1>', '', ''),
(15, 'l', 'Поиск', '[script_searchForm]', '', 'search'),
(16, 'l', 'Профиль', '<center>[script_userProfileBlock]</center>', '', ''),
(17, 'l', 'Архив', '[script_newsArchive]', '', 'archive'),
(18, 'r', 'Новости сервера', '[script_newsBlock]', '', 'recent-posts'),
(19, '', 'footer', '[script_copyright]\n<p class="credit">Дизайн от <a href="http://msc.16mb.com/">msc.16mb.com</a></p>', '', '');

DROP TABLE IF EXISTS `prefix_lib`;
CREATE TABLE IF NOT EXISTS `prefix_lib` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `file` text NOT NULL,
  `enabled` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `prefix_menus`;
CREATE TABLE IF NOT EXISTS `prefix_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `links` text NOT NULL,
  `urls` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `prefix_menus` (`id`, `title`, `links`, `urls`) VALUES
(2, 'main', 'Главная, Блог, Фото, О сервере', '%adress%, %adress%/index.php/blog/, %adress%/index.php/photo/, %adress%/index.php/about');

DROP TABLE IF EXISTS `prefix_news`;
CREATE TABLE IF NOT EXISTS `prefix_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `category` text NOT NULL,
  `date` date NOT NULL,
  `short` text NOT NULL,
  `full` text NOT NULL,
  `meta_desk` text NOT NULL,
  `meta_key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `prefix_news` (`id`, `title`, `category`, `date`, `short`, `full`, `meta_desk`, `meta_key`) VALUES
(3, 'mytitle', 'default', '2013-05-31', 'Мы открылись !', 'Добро пожаловать на наш сервер, сегодня мы открыты и теперь вы можете поиграть у нас.', '', ''),
(4, '', '', '0000-00-00', 'работает', 'Работаем негры =)', '', ''),
(5, 'Лолч', '', '0000-00-00', 'работает', 'Работаем негры =)', '', ''),
(6, 'Лолч', '', '2013-09-21', 'работает', 'Работаем негры =)', '', '');

DROP TABLE IF EXISTS `prefix_pages`;
CREATE TABLE IF NOT EXISTS `prefix_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `prefix_pages` (`id`, `title`, `content`, `url`) VALUES
(2, 'Главная страница', '<div class="post">Добро пожаловать в MSC Web Site Engine<br/><br/>Эта версия CMS является второй публичной(v A.1.1).<br/><br/>За информацией по работе с CMS обращайтесь к разработчику:<br/>e-mail:kolesnikov.da0@gmail.com<br/>skype: kolesnikov.da<br/>или в тех.поддержку на сайте msc.16mb.com<br/><br/>Для разработки своих расширений обратиться за помощью в раздел справка на сайте msc.16mb.com(на момент написания этого текста в разделе справка нет информации по CMS) или к администрации сайта msc.16mb.com.</div>', ''),
(3, 'RCON консоль', '<h1>RCON консоль</h1>[script_rconConsole]', 'rcon');

DROP TABLE IF EXISTS `prefix_scripts`;
CREATE TABLE IF NOT EXISTS `prefix_scripts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `file` text NOT NULL,
  `entries` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

INSERT INTO `prefix_scripts` (`id`, `title`, `alias`, `file`, `entries`) VALUES
(15, 'Администратирование', 'admin', 'admin.php', 'adminBar|getAdminBar'),
(16, 'Копирайт', 'copyright', 'copyright.php', 'copyright|getCopyright'),
(17, 'Новостная лента', 'news', 'news.php', 'news|getNewsPage,archive|getArchivePage,newsArchive|getArchiveBlock,newsBlock|getNewsBlock'),
(18, 'Пользователи', 'users', 'users.php', 'user|getUsersPage,userLoginPage|getLoginPage,userRegForm|getRegForm,userLoginForm|getLoginForm,userProfileBlock|getBlock,skins|getSkinsPage'),
(26, 'Поиск', 'search', 'search.php', 'searchForm|getSearchBlock,search|getSearchPage'),
(29, 'Сервер', 'server', 'server.php', 'rconConsole|getRconPage');

DROP TABLE IF EXISTS `prefix_scripts_entries`;
CREATE TABLE IF NOT EXISTS `prefix_scripts_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_title` text NOT NULL,
  `script_alias` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

INSERT INTO `prefix_scripts_entries` (`id`, `entry_title`, `script_alias`) VALUES
(1, 'adminBar', 'admin'),
(2, 'copyright', 'copyright'),
(3, 'news', 'news'),
(4, 'newsArchive', 'news'),
(5, 'archive', 'news'),
(6, 'newsBlock', 'news'),
(7, 'search', 'search'),
(8, 'searchForm', 'search'),
(9, 'rconConsole', 'server'),
(10, 'user', 'users'),
(11, 'userLoginPage', 'users'),
(12, 'userRegForm', 'users'),
(13, 'userLoginForm', 'users'),
(14, 'userProfileBlock', 'users'),
(15, 'skins', 'users');

DROP TABLE IF EXISTS `prefix_users`;
CREATE TABLE IF NOT EXISTS `prefix_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `group` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `prefix_users` (`id`, `login`, `group`) VALUES
(2, 'danilko', 'admin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
