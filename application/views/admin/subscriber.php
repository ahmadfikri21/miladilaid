<h1>Subscriber</h1>
    <div class="searchAndAddBox">
        <div class="searchBox">
            <form action="<?= base_url("Admin/subscriber") ?>" method="GET">
                <input type="text" name="searchProduct" class="searchField" placeholder="Find Subscriber...">
                <input type="submit" id="btnSearch" class="btnSearch" value="">
            </form>
        </div>
    </div>
    <table class="defaultTable tbProducts">
        <tr>
            <th>No</th>
            <th>Subscriber Email</th>
            <th>Status</th>
            <th>Submitted At</th>
            <th>Action</th>
        </tr>
        <?php
            $i = $this->uri->segment(3) + 1;
            foreach($subscriber as $row):
                if($row["status"] == "Added"){
                    $grayed = "grayedRow";
                }else{
                    $grayed = "";
                }
        ?>
        <tr class="<?= $grayed ?>">
            <td><?= $i ?></td>
            <td class="rowEmail"><?= $row["email"] ?></td>
            <td class="rowStatus"><?= $row["status"] ?></td>
            <td><?= date('d-m-Y',strtotime($row["created_at"])) ?></td>
            <td><a class="btnEdit modalTrigger" name="<?= $row['id_subscriber'] ?>"><img src="<?= base_url("assets/img/editIcon.svg") ?>"></a></td>
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
                <h3>Status Edit</h3>
                <span class="close">X</span>
                <div class="clearfix"></div>
            </div>
            <div class="modalBody">
                <form id="editSubscriberForm">
                    <input type="hidden" id="id_subscriber">
                    <div class="formElement">
                        <label>Email</label>
                        <input type="email" id="email" disabled>
                    </div>
                    <div class="formElement">
                        <label>Status</label>
                        <select name="status" id="status">
                            <option value="">Choose Status</option>
                            <option value="Added">Added</option>
                            <option value="Not Added">Not Added</option>
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
        $(".modalTrigger").click(function(){
            // untuk mengambil tag tr dari link yang di klik.(tag akan digunakan untuk mengambil id, email dan status)
            var tr = $(this).parent().parent();
            var email = tr.children(".rowEmail").text();
            var status = tr.children(".rowStatus").text();
            var id = $(this).attr("name");

            $("#modal").css("display","block");
            $("#email").val(email);
            $("#status").val(status);
            $("#id_subscriber").val(id);
        });

        $("#editSubscriberForm").on("submit",function(e){
            e.preventDefault();

            $.ajax({
                url : js_base_url("Admin/editSubscriber/"+$("#id_subscriber").val()),
                method : "POST",
                data : {
                    status:$("#status").val()
                },success:function(data){
                    if(data){
                        $("#modal").css("display","none");
                        
                        Swal.fire({
                            title: 'Success',
                            text: "Product Edited",
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
    </script>