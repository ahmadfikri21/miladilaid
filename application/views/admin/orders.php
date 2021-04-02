<h1>Orders</h1>
    <div class="searchAndAddBox">
        <div class="searchBox">
            <form action="<?= base_url("Admin/orders") ?>" method="GET">
                <input type="text" name="searchProduct" class="searchField" placeholder="Find Order...">
                <input type="submit" id="btnSearch" class="btnSearch" value="">
            </form>
        </div>
    </div>
    <table class="defaultTable tbProducts">
        <tr>
            <th>No</th>
            <th>Receiver Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Province</th>
            <th>City</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
            $i = $this->uri->segment(3) + 1;
            foreach($orders as $row):
                $grayed = "";
                $statusPayment = "";
                if($row["payment_status"] == "Paid"){
                    $grayed = "grayedRow";
                }else if($row["payment_status"] == "Waiting to be confirmed"){
                    $statusPayment = "orangeText";
                }else{
                    $statusPayment = "redText";
                }
        ?>
        <tr class="<?= $grayed ?>">
            <td><?= $i ?></td>
            <td class="rowName"><?= $row["receiver_name"] ?></td>
            <td><?= $row["phone"] ?></td>
            <td><?= $row["email"] ?></td>
            <td><?= $row["address"] ?></td>
            <td><?= $row["province"] ?></td>
            <td><?= $row["city"] ?></td>
            <td><?= date('d-m-Y',strtotime($row["order_date"])) ?></td>
            <td class="rowStatus <?= $statusPayment ?>"><?= $row["payment_status"] ?></td>
            <td class="actionRow">
                <a href="<?= base_url("Admin/orderDetail/$row[order_id]") ?>" class="btnDetail"><img src="<?= base_url("assets/img/detailIcon.svg") ?>"></a>
                <a name="<?= $row["order_id"] ?>" class="btnEdit modalTrigger"><img src="<?= base_url("assets/img/editIcon.svg") ?>"></a>
                <a href="<?= base_url("Admin/deleteOrder/$row[order_id]") ?>" class="btnDelete"><img src="<?= base_url("assets/img/deleteIcon.svg") ?>"></a>
            </td>
        </tr>
        <?php
            $i++;
            endforeach;
        ?>
    </table>
    <div class="paginationLinksBox">
        <?= $this->pagination->create_links() ?>
    </div>
    <div id="modal" class="defaultModal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3>Payment Status Edit</h3>
                <span class="close">X</span>
                <div class="clearfix"></div>
            </div>
            <div class="modalBody">
                <form id="editOrderForm">
                    <input type="hidden" id="order_id">
                    <div class="formElement">
                        <label>Receiver Name</label>
                        <input type="text" id="receiver_name" disabled>
                    </div>
                    <div class="formElement">
                        <label>Status</label>
                        <select name="status" id="status">
                            <option value="">Choose Status</option>
                            <option value="Paid">Paid</option>
                            <option value="Waiting to be confirmed">Waiting to be confirmed</option>
                            <option value="Not Paid">Not Paid</option>
                        </select>
                    </div>
            </div>
            <div class="modalFooter">
                    <input type="submit" id="btn" value="Submit" class="btnModal" onclick="return confirm('Are you sure ?');">
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(".btnDelete").click(function(e){
                e.preventDefault();
    
                Swal.fire({
                    title: 'Are you sure?',
                    text: "If you delete this order, you will also delete the detail of this order",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr("href");
                    }
                });
            });
        
        $(".modalTrigger").click(function(){
            // untuk mengambil tag tr dari link yang di klik.(tag akan digunakan untuk mengambil id, email dan status)
            var tr = $(this).parent().parent();
            var receiver_name = tr.children(".rowName").text();
            var status = tr.children(".rowStatus").text();
            var id = $(this).attr("name");

            $("#modal").css("display","block");
            $("#receiver_name").val(receiver_name);
            $("#status").val(status);
            $("#order_id").val(id);
        });

        $("#editOrderForm").on("submit",function(e){
            e.preventDefault();

            $.ajax({
                url : js_base_url("Admin/editOrder/"+$("#order_id").val()),
                method : "POST",
                data : {
                    status:$("#status").val()
                },success:function(data){
                    if(data){
                        $("#modal").css("display","none");
                        
                        Swal.fire({
                            title: 'Success',
                            text: "Payment Status Edited!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK!'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });

                    }else{
                        Swal.fire(
                            "Failed",
                            "There is something wrong in our database, Try again later",
                            "error"
                        );
                    }
                }
            });
        });
        
        });

    </script>