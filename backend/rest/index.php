<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/services/config.php';
require __DIR__ . '/services/DB.php';

require __DIR__ . '/dao/CrudDao.php';
require __DIR__ . '/dao/BaseDao.php';
require __DIR__ . '/dao/ProductDao.php';
require __DIR__ . '/dao/PlayerDao.php';
require __DIR__ . '/dao/MatchDao.php';
require __DIR__ . '/dao/UserDao.php';
require __DIR__ . '/dao/OrderDao.php';

require __DIR__ . '/services/BaseService.php';
require __DIR__ . '/services/ProductService.php';
require __DIR__ . '/services/PlayerService.php';
require __DIR__ . '/services/MatchService.php';
require __DIR__ . '/services/UserService.php';
require __DIR__ . '/services/OrderService.php';

Flight::set('productService', new ProductService());
Flight::set('playerService',  new PlayerService());
Flight::set('matchService',   new MatchService());
Flight::set('userService',    new UserService());
Flight::set('orderService',   new OrderService());

require __DIR__ . '/routes/products.php';
require __DIR__ . '/routes/players.php';
require __DIR__ . '/routes/matches.php';
require __DIR__ . '/routes/users.php';
require __DIR__ . '/routes/orders.php';

Flight::route('GET /', function () {
    echo 'FK Zeljeznicar API radi ✅';
});

Flight::start();
