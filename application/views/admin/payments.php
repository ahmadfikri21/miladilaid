<h1>Payments</h1>
    <div class="searchAndAddBox">
        <div class="searchBox">
            <form action="<?= base_url("Admin/payments") ?>" method="GET">
                <input type="text" name="searchProduct" class="searchField" placeholder="Find Product...">
                <input type="submit" id="btnSearch" class="btnSearch" value="">
            </form>
        </div>
    </div>
    <table class="defaultTable tbProducts">
        <tr>
            <th>No</th>
            <th>Order</th>
            <th>Account Number</th>
            <th>Account Name</th>
            <th>Total Paid</th>
            <th>Transfer Date</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
        <?php
            $i = $this->uri->segment(3) + 1;
            foreach($payments as $row):
                if($row["payment_status"] == "Paid"){
                    $grayed = "grayedRow";
                }else{
                    $grayed = "";
                }
        ?>
        <tr class="<?= $grayed ?>">
            <td><?= $i ?></td>
            <td><?= $row["receiver_name"] ?></td>
            <td><?= $row["account_number"] ?></td>
            <td><?= $row["name"] ?></td>
            <td>Rp. <?= number_format($row["total_bill"]) ?></td>
            <td><?= date("d-m-Y",strtotime($row["transfer_date"])) ?></td>
            <td><?= $row["payment_status"] ?></td>
            <td><a href="<?= base_url("Admin/orderDetail/$row[order_id]") ?>" class="btnDetail"><img src="<?= base_url("assets/img/detailIcon.svg") ?>"></a></td>
        </tr>
        <?php
            $i++;
            endforeach;
        ?>
    </table>
    <div class="paginationLinksBox">
        <?= $this->pagination->create_links() ?>
    </div>