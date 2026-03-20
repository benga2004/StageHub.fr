<?php
session_start();

define('BASE_PATH', __DIR__);

// Autoload ou requires
require_once  '../app/models/Offer.php';
require_once  '../app/controllers/AddOfferController.php';

// Routage
require_once  '../routes/web.php';