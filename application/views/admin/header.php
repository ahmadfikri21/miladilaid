<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Miladila : Admin</title>
</head>
<body>
    <?php
        if($this->session->flashdata("succNotice")){
            ?>
            <script>
                Swal.fire(
                    "Success",
                    "<?= $this->session->flashdata("succNotice") ?>",
                    "success"
                );
            </script>
            <?php
            $this->session->unset_userdata("succNotice");
        }elseif($this->session->flashdata("errNotice")){
            ?>
            <script>
                Swal.fire(
                    "Error",
                    "<?= $this->session->flashdata("errNotice") ?>",
                    "error"
                );
            </script>
            <?php
            $this->session->unset_userdata("errNotice");
        }
    ?>
    <aside class="sideNav">
        <a href="javascript:void(0)" class="closeSidebar">&times;</a>
        <a href="<?= base_url("Admin/index") ?>">Miladila id</a>
        <a href="<?= base_url("Admin/index") ?>">Products</a>
        <a href="<?= base_url("Admin/orders") ?>">Orders</a>
        <a href="<?= base_url("Admin/payments") ?>">Payments</a>
        <a href="<?= base_url("Admin/subscriber") ?>">Subscriber</a>
        <a href="<?= base_url("Admin/logout") ?>">Logout</a>
    </aside>
    <div class="adminContent">
        <h1 class="defaultHeader">Miladila Admin</h1>
        <span id="openSidebar" class="openSidebar"> > </span>