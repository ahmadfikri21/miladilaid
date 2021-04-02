    <h1>Products</h1>
    <div class="searchAndAddBox">
        <div class="searchBox">
            <form action="<?= base_url("Admin/index") ?>" method="GET">
                <input type="text" name="searchProduct" class="searchField" placeholder="Find Product...">
                <input type="submit" id="btnSearch" class="btnSearch" value="">
            </form>
        </div>
        <div class="btnAddBox">
            <button id="modalTrigger" class="btnAdd">+ Add New Product</button>
        </div>
    </div>
    <table class="defaultTable tbProducts">
        <tr>
            <th>No</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Weight</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
            $i = $this->uri->segment(3) + 1;
            foreach($products as $row):
                if($row['status'] == 0){
                    $status = "Not Displayed";
                }else{
                    $status = "Displayed";
                }
        ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $row["product_name"] ?></td>
            <td class="productDescription"><?= $row["description"] ?></td>
            <td>Rp. <?= number_format($row["price"]) ?></td>
            <td><?= $row["stock"] ?></td>
            <td><?= $row["weight"] ?> gr</td>
            <td><img src="<?= base_url("assets/img/product_image/$row[image]") ?>"></td>
            <td><?= $status ?></td>
            <td><a href="<?= base_url("Admin/editProduct/$row[product_id]") ?>" class="btnEdit"><img src="<?= base_url("assets/img/editIcon.svg") ?>"></a><a href="<?= base_url("Admin/deleteProduct/$row[product_id]/$row[image]") ?>" class="btnDelete"><img src="<?= base_url("assets/img/deleteIcon.svg") ?>"></a></td>
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
                <h3>Add Product</h3>
                <span class="close">X</span>
                <div class="clearfix"></div>
            </div>
            <div class="modalBody">
                <?php echo form_open_multipart("Admin/addNewProduct",array("id" => "addProductForm")) ?>
                    <div class="formElement">
                        <label>Product Name</label>
                        <input type="text" id="product_name" name="product_name" placeholder="Product Name..." value="<?= $this->session->flashdata("prod_name") ?>">
                    </div>
                    <div class="formElement">
                        <label>Description</label>
                        <textarea id="description" name="description" cols="30" rows="10" placeholder="Description ..." ><?= $this->session->flashdata("description") ?></textarea>
                    </div>
                    <div class="formElement">
                        <label>Price</label>
                        <input type="text" id="price" name="price" placeholder="Price..." value="<?= $this->session->flashdata("price") ?>">
                    </div>
                    <div class="formElement">
                        <label>Stock</label>
                        <input type="text" id="stock" name="stock" placeholder="Stock..." value="<?= $this->session->flashdata("stock") ?>">
                    </div>
                    <div class="formElement">
                        <label>Weight</label>
                        <input type="text" id="weight" name="weight" placeholder="Weight..." value="<?= $this->session->flashdata("weight") ?>">
                    </div>
                    <div class="formElement">
                        <label>Status</label>
                        <select name="status" id="status">
                            <option value="">Choose Status</option>
                            <option value="1">Displayed</option>
                            <option value="0">Not Displayed</option>
                        </select>
                    </div>
                    <div class="formElement">
                        <label>Image</label>
                    </div>
                    <input type="file" id="image" name="image">
            </div>
            <div class="modalFooter">
                    <input type="submit" id="btn" value="Submit" class="btnModal" onclick="return confirm('Are you sure ?');">
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
    <?php
        // Untuk mengunset flash data yang menjadi value setiap field ketika terjadi form validation
        $flashdata = array("prod_name","description","price","stock","weight","status");
        $this->session->unset_userdata($flashdata);
    
    ?>
    <script>
        $(".btnDelete").click(function(e){
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "If you delete this product, you will also delete all orders and payments data regarding this product.",
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
    </script>