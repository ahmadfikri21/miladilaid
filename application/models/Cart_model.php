<?php

    class Cart_model extends CI_Model{
        public function getProductImageAndWeight($product_ids){
            // mengecek apakah product id ada 1 atau lebih
            if(count($product_ids) == 1){
                $sql = 'SELECT image,weight FROM products WHERE product_id='.$this->db->escape($product_ids[0]);
                $query = $this->db->query($sql);
                return $query->row_array();
            }else{
                // mengambil data image dengan beberapa id, disini menggunakan cara string yang di append.
                $sql = "SELECT image,weight FROM products WHERE product_id in (";
                for ($i=0; $i < count($product_ids); $i++){
                    $sql .= $product_ids[$i].",";
                }
                // membuang koma terakhir di string yg sudah di append
                $sql = rtrim($sql,",");
                // mengappend tanda )
                $sql .= ")";
                $query = $this->db->query($sql);
                return $query->result_array();
            }
        }

        public function addOrder($data){
            // memasukkan data ke tabel orders
            $insert = array(
                "receiver_name" => $data['nama'],
                "phone" => $data['no_telp'],
                "email" => $data['email'],
                "address" => $data['alamat'],
                "province" => $data['provinsi'],
                "city" => $data['kota'],
                "total_bill" => $data['total_bill']
            );

            // jika data berhasil masuk, maka program akan menjalankan perintah untuk memasukkan data kedalam order_detail
            if($this->db->insert("orders",$insert)){
                // mengambil id terakhir dari tabel orders untuk dijadikan foreign key di tabel order details
                $lastInsert = $this->db->insert_id();
                // looping untuk me loop produk id dan quantity karena dalam bentuk array
                for($i = 0; $i < count($this->cart->contents()); $i++){
                    $price = $this->getPrice($data["prod_id"][$i]);
                    $insert = array(
                        "order_id" => $lastInsert,
                        "product_id" => $data["prod_id"][$i],
                        "quantity" => $data["qty"][$i],
                        "price" => $price["price"]
                    );

                    $this->db->insert("order_detail",$insert);
                }

                return $lastInsert;
            }else{
                return false;
            }
        }

        public function getOrderById($orderId = FALSE){
            if($orderId != FALSE){
                $query = $this->db->get_where("orders",array("order_id" => $orderId));
                return $query->row_array();
            }else{
                $query = $this->db->get("orders");
                return $query->result_array();
            }
        }

        public function getOrderDetails($orderId){
            $query = $this->db->get_where("order_detail",array("order_id" => $orderId));
            return $query->result_array();
        }

        public function getProductsById($id = FALSE){
            if($id != FALSE){
                $query = $this->db->get_where("products",array("product_id" => $id));
                return $query->row_array();
            }else{
                $query = $this->db->get("products");
                return $query->result_array();
            }
        }

        public function getPrice($product_id){
            $this->db->select("price");
            $this->db->from("products");
            $this->db->where("product_id", $product_id);

            $query = $this->db->get();

            return $query->row_array();
        }

        public function insertPayment(){
            $array = array(
                "order_id" => $this->input->post("orderId"),
                "account_number" => $this->input->post("noRek"),
                "name" => $this->input->post("nama"),
                "transfer_date" => $this->input->post("tglTransfer"),
                "total_bill" => $this->input->post("totalBill")
            );

            if($this->db->insert("payments",$array)){
                return true;
            }else{
                return false;
            }
        }

        public function updatePaymentStatus(){
            $update = array(
                "payment_status" => "Waiting to be confirmed"
            );

            $this->db->where("order_id",$this->input->post("orderId"));

            if($this->db->update("orders",$update)){
                return true;
            }else{
                return false;
            }
        }
    }