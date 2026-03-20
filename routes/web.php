<?php
require_once '../app/controllers/AddOfferController.php';
require_once '../app/models/Offer.php';

$action = $_GET['action'] ?? 'home';
$controller = new AddOfferController();

match($action) {
    'addOffer_step1' => $controller->step1(),
    'addOffer_step2' => $controller->step2(),
    'addOffer_store' => $controller->store(),
    default          => $controller->step1(),
};