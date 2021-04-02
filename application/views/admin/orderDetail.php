<h1>Order Detail <?= $detail[0]["receiver_name"] ?></h1><br>
<div class="paymentStatusBox">
    <h3>Payment Status : <span class="paymentStatus"><?= $detail[0]['payment_status'] ?></span></h3>
    <?php
        if($detail[0]["payment_status"] == "Waiting to be confirmed"):
            $id = strval($detail[0]["order_id"]);
    ?>
        <a href="<?= base_url("Admin/editOrder/$id/true") ?>" class="btnDetail">Confirm Payment</a>
    <?php
        endif;
    ?>
</div>
<h3>Order Date : <span class="normal"><?= date('d-m-Y',strtotime($detail[0]['order_date'])) ?></span></h3>
<a href="<?= base_url("Admin/orders") ?>" class="btnBack">< Back</a>
<table class="defaultTable tbProducts">
        <tr>
            <th>No</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php
            $i = 1;
            foreach($detail as $row):
        ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $row["product_name"] ?></td>
            <td><?= $row["quantity"] ?></td>
            <td>Rp. <?= number_format($row["price"]*$row["quantity"]) ?></td>
        </tr>
        <?php
            $i++;
            endforeach;
        ?>
    </table>
    <p class="totalBill"><strong>Total Bill + Delivery</strong>&nbsp; &nbsp; Rp. <?= number_format($detail[0]["total_bill"]) ?></p>
    <script>
        $(document).ready(function(){
            var status = $(".paymentStatus");
            if(status.text() == "Paid"){
                status.css("color","#32cd32");
                status.css("font-weight","bold");
            }else if(status.text() == "Not Paid"){
                status.css("color","red");
                status.css("font-weight","bold");
            }else if(status.text() == "Waiting to be confirmed"){
                status.css("color","orange");
                status.css("font-weight","bold");
            }

        });
    </script>