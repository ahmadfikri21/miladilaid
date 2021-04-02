<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css') ?>">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Miladila</title>
</head>
<body>
    <!-- Flashdata -->
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
    <!-- Welcome Box -->
    <div class="welcomeBox">
        <div class="container">
            <nav>
                <div class="navHeader"><a href="<?= base_url() ?>">miladila id</a></div>
                <ul>
                    <li class="navLinks"><a href="<?= base_url() ?>">Home</a></li>
                    <li class="navLinks cart">
                        <a href="<?= base_url("Cart/index/") ?>">
                            <img src="<?= base_url("assets/img/shoppingBagWhite.svg") ?>">
                            <span class="countCart"><?= $this->cart->total_items(); ?></span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="welcomeContent">
                <div class="miladilaLogo">
                    <span>miladila.id</span>
                </div>
                <div class="welcomeText">
                    <h1>Welcome to Miladila.id</h1>
                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel atque aspernatur porro voluptatibus aut. Odit, harum et? Sed nisi ex eius, aliquam illum tempore ipsa perferendis similique saepe vero sint veniam iure blanditiis pariatur.</span>
                    <form class="formSubscribe">
                        <input type="text" class="txtSub email" placeholder="Enter your email..."> 
                        <input type="submit" value="Subscribe" class="btnSub">
                    </form>
                </div>
            </div>
        </div>
        <a href="#shopNowBox" class="scrollIcon">
            <img src="<?= base_url("assets/img/Scrolldown.svg") ?>">
            <img src="<?= base_url("assets/img/Scrolldown.svg") ?>">
        </a>
    </div>
    <!-- Shopnow Box -->
    <div id="shopNowBox">
        <div class="container">
            <h1>Shop Now</h1>
            <div class="products">
                <?php 
                    foreach($products as $data): 
                        $action = base_url("Home/addToCart/$data[product_id]");
                        if($this->session->userdata("checkout")){
                            $action = base_url("Home/isCheckout");
                        }
                ?>
                    <div class="productList">
                        <img class="imgClick" src="<?= base_url("assets/img/product_image/$data[image]") ?>" name="<?= $data['product_id'] ?>">
                        <div class="productInfo">
                            <p class="prod_name"><?= $data['product_name'] ?></p>
                            <p class="price"><?= "Rp. ".number_format($data['price']) ?></p>
                            <div class="addToCartBox">
                                <form action="<?= $action ?>" method="GET">
                                    <input type="hidden" name="prod_id" value="<?= $data['product_id'] ?>">
                                    <input type="hidden" name="prod_name" value="<?= $data['product_name'] ?>">
                                    <input type="hidden" name="price" value="<?= $data['price'] ?>">
                                    <input type="hidden" name="weight" value="<?= $data['weight'] ?>">
                                    <input type="number" name="qty" value="1" min="1" max="10">
                                    <input type="submit" value="Add To Cart" class="btnAddToCart">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
        $(".imgClick").click(function(){
            productId = $(this).attr("name");
            document.location.href = "<?= base_url("Home/productDetail/") ?>"+productId;
        });
    </script>