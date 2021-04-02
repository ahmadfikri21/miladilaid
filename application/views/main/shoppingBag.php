    <?php
        if($this->session->userdata("checkout")){
            redirect("Cart/paymentInfo/".$this->session->userdata("orderId"));
        }
    ?>
    <div class="container">
        <div class="bagHeader">
            <h1>Shopping Bag</h1>
            <h1><?= count($this->cart->contents())." Product(s)" ?></h1>
        </div>
        <table class="defaultTable">
            <tr>
                <th>No.</th>
                <th>Product</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Weight</th>
                <th>Total</th>
            </tr>
            <tr>
                <?php
                    $subtotal = 0;
                    // mengecek apakah isi cart ada 1, lebih dari satu, atau kosong
                    if(count($items) == 1){
                        // melooping isi dari cart(codeigniter)
                        $k = 1;
                        foreach($items as $content):
                        ?>
                        <tr>
                            <td><?= $k ?></td>
                            <td><img src="<?= base_url("assets/img/product_image/$db[image]") ?>"></td>
                            <td><?= $content["name"] ?></td>
                            <td><input type="number" value="<?= $content["qty"] ?>" min="1" max="10" class="qtyField" name="<?= $content['rowid'] ?>"></td>
                            <td><?= "Rp.".number_format($content["price"]) ?></td>
                            <td><?= $content["qty"]*$db["weight"]." gr" ?></td>
                            <td><?= "Rp. ".number_format($content["qty"] * $content['price']) ?></td>
                            <td><span class="removeItems" value="<?= $content['rowid'] ?>">X</span></td>
                        </tr>
                        <?php
                            $subtotal = $subtotal+($content["qty"] * $content['price']);
                            $k++;
                        endforeach;
                    }else if(count($items) > 1){
                        $i = 0;
                        foreach($items as $content):
                            ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><img src="<?= base_url("assets/img/product_image/".$db[$i]["image"]) ?>"></td>
                                    <td><?= $content["name"] ?></td>
                                    <td><input type="number" value="<?= $content["qty"] ?>" min="1" class="qtyField" name="<?= $content['rowid'] ?>"></td>
                                    <td><?= "Rp. ".number_format($content["price"]) ?></td>
                                    <td><?= $content["qty"]*$db[$i]["weight"]." gr" ?></td>
                                    <td><?= "Rp. ".number_format($content["qty"] * $content['price']) ?></td>
                                    <td><span class="removeItems" value="<?= $content['rowid'] ?>">X</span></td>
                                </tr>
                            <?php
                            $subtotal = $subtotal+($content["qty"] * $content['price']);
                            $i++;
                        endforeach;
                    }else{
                        $subtotal = 0;
                        echo "<tr>
                                <td id='emptyCart' colspan=6>No Items</td>
                              </tr>";
                    }
                ?>
            </tr>
        </table>
        <div class="cartTotal">
            <p><strong>Subtotal</strong> Rp. <?= number_format($subtotal) ?></p>
        </div>
        <?php if(count($items) > 0): ?>
            <a class="btnCheckout" href="<?= base_url('Cart/checkout') ?>">Checkout</a>
            <div class="clearfix"></div>
        <?php endif; ?>
    </div>
    <script>
        $(document).ready(function(){
            $(".qtyField").on("change",function(e){
                var rowid = $(this).attr("name");
                var quantity = $(this).val();
                var action = "update";
                
                $.ajax({
                    url : "<?= base_url("Cart/updateCart") ?>",
                    method : "GET",
                    data : {
                        rowid : rowid,
                        quantity : quantity,
                        action : action
                    },success:function(){
                        location.reload();
                    }
                });
            });

            $(".removeItems").click(function(){
                var rowid = $(this).attr("value");
                var action = "remove";

                Swal.fire({
                    title: 'Remove item?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "<?= base_url("Cart/updateCart") ?>",
                            method : "GET",
                            data:{
                                rowid:rowid,
                                action:action
                            },
                            success:function(data){
                                location.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>