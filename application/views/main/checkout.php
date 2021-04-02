    <?php
        if($this->session->userdata("checkout")){
           redirect("Cart/paymentInfo/".$this->session->userdata("orderId")); 
        }
    ?>
    <div class="container">
        <h1 class="defaultHeader">Checkout</h1>
        <div class="checkoutBox">
            <div class="checkoutForm">
                <h3>Alamat Pengiriman</h3>
                <form action="<?= base_url("Cart/checkout") ?>" method="POST">
                    <?php
                        foreach($items as $data):
                            ?>
                            <input type="hidden" name="prod_id[]" value="<?= $data["id"] ?>">
                            <input type="hidden" name="qty[]" value="<?= $data["qty"] ?>">
                            <?php
                        endforeach;
                    ?>
                    <div class="formElement">
                        <label>Nama Penerima</label>
                        <input type="text" name="nama" class="<?= fieldErrorCheck("nama") ?>" value="<?= set_value("nama") ?>" placeholder="Nama penerima...">
                        <?= form_error("nama"); ?>
                    </div>
                    <div class="formElement">
                        <label>Nomor Telepon</label>
                        <input type="text" name="no_telp" class="<?= fieldErrorCheck("no_telp") ?>" value="<?= set_value("no_telp") ?>" placeholder="Nomor telepon...">
                        <?= form_error("no_telp"); ?>
                    </div>
                    <div class="formElement">
                        <label>Email</label>
                        <input type="email" name="email" class="<?= fieldErrorCheck("email") ?>" value="<?= set_value("email") ?>" placeholder="Email...">
                        <?= form_error("email"); ?>
                    </div>
                    <div class="formElement">
                        <label>Alamat Penerima</label>
                        <textarea name="alamat" cols="30" rows="10" class="<?= fieldErrorCheck("alamat") ?>" placeholder="Alamat Penerima..."><?= set_value("alamat") ?></textarea>
                        <?= form_error("alamat"); ?>
                    </div>
                    <div class="formElement">
                        <label>Provinsi</label>
                        <select id="provinsi" class="<?= fieldErrorCheck("provinsi") ?>" name="provinsi">
                            <option value="">Pilih Provinsi</option>
                            <?php
                                foreach($provinsi as $rowProvinsi){
                                    echo "<option value='$rowProvinsi[province]' name='$rowProvinsi[province_id]'>$rowProvinsi[province]</option>";
                                }
                            ?>
                        </select>
                        <?= form_error("provinsi"); ?>
                    </div>
                    <div class="formElement">
                        <label>Kota</label>
                        <select id="kota" class="<?= fieldErrorCheck("kota") ?>" name="kota" disabled>
                            <option value="">Pilih Kota</option>
                        </select>
                        <?= form_error("kota"); ?>
                    </div>
                    <div class="formElement">
                        <label>Ongkos Kirim</label>
                        <input type="text" name="ongkir" id="ongkir" readonly>
                    </div>
                    <input type="hidden" name="total_bill" id="total_bill">
                    <input type="submit" class="btnSubmit" value="Submit">
                    <div class="clearfix"></div>
                </form>
            </div>
            <div class="orderDetail">
                <h3>Detail Order</h3>
                <table class="defaultTable orderTab">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Berat</th>
                        <th>Total</th>
                    </tr>
                    <?php
                        $subtotal = 0;
                        foreach($items as $row):
                            $total = $row['price']*$row['qty'];
                            $subtotal = $subtotal + $total;
                            ?>
                            <tr>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['qty'] ?></td>
                                <td><?= $row['options']["weight"] ?></td>
                                <td><?= "Rp. ".number_format($total) ?></td>
                            </tr>
                            <?php
                        endforeach;
                    ?>
                </table>
                <p><strong>Total Belanja</strong>&nbsp; &nbsp; Rp. <span value="<?= $subtotal ?>" id="subtotal"><?= number_format($subtotal) ?></span></p>
                <p><strong>Ongkos Kirim</strong>&nbsp; &nbsp; Rp. <span id="ongkos">0</span></p>
            </div>
        </div>
    </div>
    <script>
        $("#provinsi").change(function(){
            var province_id = $("option:selected",this).attr("name");
            // menghapus value ongkos kirim, jika provinsi diganti
            $("#ongkir").attr("value", "");
            // ajax digunakan untuk mengambil kota berdasarkan provinsi yang dimasukkan, data yang diambil bertype json
            $.ajax({
                url : "<?= base_url("Cart/getKota") ?>",
                method : "GET",
                data : {
                    province_id : province_id
                },
                dataType : "json",
                success:function(data){
                    // menghapus data kota sebelumnya agar data tidak double
                    $("#kota option").remove();
                    $("#kota").append($('<option>',{value : "",text : "Pilih Kota"}))
                    $.each(data.rajaongkir.results, function(i, item){
                        $("#kota").append($('<option>', {
                            value : item.city_name,
                            name : item.city_id,
                            text : item.city_name
                        }));    
                    });
                    $("#kota").prop("disabled",false);
                }
            });
        });

        $("#kota").change(function(){
            var kotaValue = $("#kota").children("option:selected").attr("name");
            if(kotaValue != ""){
                $("#ongkir").attr("value", "Please Wait....");
                $.ajax({
                    url : "<?= base_url("Cart/getCost") ?>",
                    method : "GET",
                    data : {
                        kotaValue:kotaValue   
                    },
                    dataType : "json",
                    success:function(data){
                        var ongkir = data.rajaongkir.results[0].costs[0].cost[0].value;
                        var total_bill = parseInt(ongkir) + parseInt($("#subtotal").attr("value"));

                        $("#ongkir").attr("value", ongkir);
                        $("#total_bill").attr("value", total_bill);
                        $("#ongkos").text(ongkir.toLocaleString());
                    }
                });
            }

        });

    </script>