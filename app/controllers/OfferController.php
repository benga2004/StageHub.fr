<?php
include '../models/Offer.php';

$offres = Offer::getAll();

$parPage = 5;

$total = count($offres);

$pages = ceil($total/$parPage);

$page = $_GET['page'] ?? 1;

$debut = ($page-1)*$parPage;

$offresPage = array_slice($offres,$debut,$parPage);

require "../views/offers/list.php";

?>