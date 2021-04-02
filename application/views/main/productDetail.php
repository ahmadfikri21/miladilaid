    <?php
        $action = base_url("Home/addToCart/$products[product_id]");
        if($this->session->userdata("checkout")){
            $action = base_url("Home/isCheckout");
        }
    ?>
    <div class="container">
        <div class="productBox">
            <img src="<?= base_url("assets/img/product_image/$products[image]") ?>">
            <div class="productBoxInfo">
                <div class="upperBoxInfo">
                    <h1><?= $products['product_name'] ?></h1>
                    <p><?= $products['description'] ?></p>
                    <h2><?= $products['weight'] ?> gram</h2>
                    <h2><?= "Rp. ".number_format($products['price']) ?></h2>
                </div>
                <div class="bottomBoxInfo">
                    <form action="<?= $action ?>" method="GET">
                        <a href="<?= base_url("Home/Index") ?>"><</a>
                        <input type="hidden" name="prod_id" value="<?= $products['product_id'] ?>">
                        <input type="hidden" name="prod_name" value="<?= $products['product_name'] ?>">
                        <input type="hidden" name="price" value="<?= $products['price'] ?>">
                        <input type="hidden" name="weight" value="<?= $products['weight'] ?>">
                        <input type="number" name="qty" value="1" min="1">
                        <input type="submit" value="Add To Cart" class="btnAddToCart">
                    </form>
                </div>
            </div>
        </div>
    </div>