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
    }
    $this->session->unset_userdata("succNotice");
?>

    <div class="container">
        <h1 class="defaultHeader">Pembayaran</h1>
        <div class="orderDescription">
            <div>
                <p>No Faktur</p>
                <p>Nama Penerima</p>
                <p>No Telepon</p>
                <p>Alamat</p>
                <p>Tanggal Pemesanan</p>
                <p>Email</p>
            </div>
            <div class="orderValue">
                <p>: <?= $order["order_id"] ?></p>
                <p>: <?= $order["receiver_name"] ?></p>
                <p>: <?= $order["phone"] ?></p>
                <p>: <?= $order["address"] ?></p>
                <p>: <?= date('d-m-Y', strtotime("$order[order_date]")); ?></p>
                <p>: <?= $order["email"] ?></p>
            </div>
        </div>
        <table class="defaultTable">
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Berat</th>
                <th>Total</th>
            </tr>
            <?php
                $i = 0;
                $subtotal = 0;
                foreach($products as $prod):
                    ?>
                    <tr>
                        <td><?= $prod["product_name"] ?></td>
                        <td><?= $orderedProducts[$i]["quantity"] ?></td>
                        <td>Rp. <?= number_format($prod["price"]) ?></td>
                        <td><?= $prod["weight"] ?> gr</td>
                        <td>Rp. <?= number_format($prod["price"]*$orderedProducts[$i]["quantity"]) ?></td>
                    </tr>
                    <?php
                    $subtotal = $subtotal + $prod["price"]*$orderedProducts[$i]["quantity"];
                    $i++;
                endforeach;
            ?>
        </table>
        <div class="paymentSubtotal">
            <p><strong>Ongkos Kirim</strong>&nbsp; &nbsp; Rp. <?= number_format($this->session->userdata("ongkir")) ?></p>
            <p><strong>Total Yang Perlu Dibayar</strong>&nbsp; &nbsp; Rp. <?= number_format($subtotal+$this->session->userdata("ongkir")) ?></p>
            <?php if(!$this->session->userdata("paymentConfirm")): ?>
                <button id="cancelOrder" class="btnCancelOrder">Batalkan Pesanan</button>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        
        <?php if(!$this->session->userdata("paymentConfirm")): ?>
        <div class="accountNumberDisplay">
            <h5>Lakukan Pembayaran ke Rekening Berikut :</h5>
            <h1>1265 7775 9990 1256</h1>
            <h3>Mandiri A/n Sarah Maulida</h3>
        </div>
        <div class="confirmPaymentBox">
            <p>Jika sudah melakukan pembayaran, </p>
            <p>silahkan konfirmasi di link berikut ini <button id="btnPaymentModal">Konfirmasi</button></p>
        </div>
        <?php else: ?>
            <?php if($order["payment_status"] == "Paid"): ?>
                <div class="validationMessage">
                    <h1>Pembayaran Telah Dikonfirmasi ! <br>Pesanan Anda Sedang Diproses</h1>
                </div>
            <?php else: ?>
                <div class="validationMessage">
                    <h1>Pembayaran Sedang di Validasi <br> Mohon Menunggu</h1>
                </div>
            <?php endif; ?>
            <p class="paragrafBrown">*Kami akan segera menghubungi ada melalui handphone atau email</p>
            <p class="paragrafBrown">**Mohon <strong>jangan tutup browser</strong>  sampai pesanan anda telah di konfirmasi</p>
        <?php endif; ?>
    </div>
    <div id="modal" class="defaultModal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3>Konfirmasi Pembayaran</h3>
                <span class="close">X</span>
                <div class="clearfix"></div>
            </div>
            <div class="modalBody">
                <form id="formPaymentConfirm">
                    <input type="hidden" id="order_id" name="order_id" value="<?= $order["order_id"] ?>">
                    <div class="formElement">
                        <label>Total Tagihan</label>
                        <input type="text" id="total_bill" name="total_bill" value="<?= number_format($subtotal+$this->session->userdata("ongkir")) ?>" readonly>
                    </div>
                    <div class="formElement">
                        <label>Nomor Rekening (Nama Bank - Nomor Rekening)</label>
                        <input type="text" id="no_rekening" name="no_rekening" placeholder="Contoh : BCA - 12345678">
                    </div>
                    <div class="formElement">
                        <label>Atas Nama</label>
                        <input type="text" id="atas_nama" name="atas_nama" placeholder="Atas Nama...">
                    </div>
                    <div class="formElement">
                        <label>Tanggal Transfer</label>
                        <input type="date" id="tgl_transfer" name="tgl_transfer" placeholder="Tanggal Transfer...">
                    </div>
                
            </div>
            <div class="modalFooter">
                    <input type="submit" id="btn" value="Submit" class="btnSubmit">
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var modal = $("#modal");
            var trigger = $("#btnPaymentModal");
            var close = $(".close");

            trigger.click(function(){
                modal.css("display","block");
            });

            close.click(function(){
                modal.css("display","none");
            });

            // modal akan menutup jika cursor klik diluar modal
            $(window).click(function(e){
                if(e.target.id == modal.attr("id")){
                    modal.css("display","none");
                }
            });

            $("#formPaymentConfirm").submit(function(e){
                e.preventDefault();

                var noRek = $("#no_rekening").val();
                var nama = $("#atas_nama").val();
                var tglTransfer = $("#tgl_transfer").val();
                var orderId = $("#order_id").val();
                var totalBill = $("#total_bill").val();
                
                if(noRek != "" || nama != "" || tglTransfer != ""){
                    Swal.fire({
                    title: 'Are you sure the data is correct?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        url : js_base_url("Cart/confirmPayment"),
                        method : "POST",
                        data:{
                            orderId : orderId,
                            noRek : noRek,
                            nama : nama,
                            tglTransfer : tglTransfer,
                            totalBill : totalBill
                        },success:function(data){
                            modal.css("display","none");
                            if(data){
                                location.reload();
                            }else{
                                Swal.fire(
                                    "Error",
                                    "There is an error in our database, please contact us to confirm if you already make a payment",
                                    "error"
                                );
                            }
                        }
                    });
                    }
                });
                 
                }else{
                    Swal.fire(
                        "Failed",
                        "Please, fill out all of the textfield",
                        "error"
                    );
                }

            });

            $("#cancelOrder").click(function(){
                var orderId = $("#order_id").val();
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = js_base_url("Cart/cancelOrder/"+orderId);
                    }
                });

            });
        });
    </script>