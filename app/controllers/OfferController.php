<?php

include '../models/Offer.php';

$offres = Offer::getAll();

$parPage = 5;
$total   = count($offres);
$pages   = ceil($total / $parPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $pages));

$debut      = ($page - 1) * $parPage;
$offresPage = array_slice($offres, $debut, $parPage);

require "../views/offers/list.php";