<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css') ?>">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Miladila<?= " : ".$title ?></title>
</head>
<body>
    <div class="container">
        <nav class="navGlobal">
            <div class="navHeader"><a href="<?= base_url() ?>">miladila id</a></div>
            <ul>
                <li class="navLinks"><a href="<?= base_url() ?>">Home</a></li>
                <li class="navLinks cart">
                    <a href="<?= base_url("Cart/index") ?>">
                        <img src="<?= base_url("assets/img/shoppingBagBrown.svg") ?>">
                        <span class="countCart"><?= $this->cart->total_items(); ?></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>