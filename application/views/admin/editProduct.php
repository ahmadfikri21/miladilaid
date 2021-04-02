<h1>Edit Product</h1>
<?= form_open_multipart("Admin/editProduct/".$this->uri->segment(3)) ?>
    <div class="formElement">
        <input type="hidden" name="product_id" value="<?= $this->uri->segment(3) ?>">
        <label>Product Name</label>
        <input type="text" name="product_name" placeholder="Product Name..." value="<?= $product['product_name'] ?>">
    </div>
    <div class="formElement">
        <label>Description</label>
        <textarea id="description" name="description" cols="30" rows="10" placeholder="Description ..."><?= $product['description'] ?></textarea>
    </div>
    <div class="formElement">
        <label>Price</label>
        <input type="text" id="price" name="price" placeholder="Price..." value="<?= $product['price'] ?>">
    </div>
    <div class="formElement">
        <label>Stock</label>
        <input type="text" id="stock" name="stock" placeholder="Stock..." value="<?= $product['stock'] ?>">
    </div>
    <div class="formElement">
        <label>Weight</label>
        <input type="text" id="weight" name="weight" placeholder="Weight..." value="<?= $product['weight'] ?>">
    </div>
    <div class="formElement">
        <label>Status</label>
        <select name="status" id="status">
            <option value="">Choose Status</option>
            <option value="1" <?= ($product['status'] == 1 ? "selected" : "") ; ?>>Displayed</option>
            <option value="0" <?= ($product['status'] == 0 ? "selected" : "") ; ?>>Not Displayed</option>
        </select>
    </div>
    <div class="formElement">
        <label>Image</label>
        <img src="<?= base_url("assets/img/product_image/$product[image]") ?>" id="prodImage">
    </div>
    <input type="file" id="image" name="image"><br>
    <div class="formBtnBox">
        <a href="<?= base_url("Admin/index") ?>">Back</a>
        <input type="submit" value="Submit">
    </div>
</form>
<script>
    $("#image").change(function(){
        var filename = $('#image').val().split('\\').pop();
        $('#prodImage').css("display",'none'); 
    });
</script>