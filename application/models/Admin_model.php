<?php

    Class Admin_model extends CI_Model{
        // digunakan untuk menghitung jumlah baris yang ada di tabel
        public function countTableRow($table){
            return $this->db->get($table)->num_rows();
        }

        public function getAllProducts($limit,$offset){
            $query = $this->db->get("products",$limit,$offset);
            return $query->result_array();
        }

        public function getProductById($id){
            $query = $this->db->get_where("products",array("product_id" => $id));
            return $query->row_array();
        }

        
        public function insertProduct($imagename){
            $data = array(
                "product_name" => $this->input->post("product_name"),
                "description" => $this->input->post("description"),
                "price" => $this->input->post("price"),
                "stock" => $this->input->post("stock"),
                "weight" => $this->input->post("weight"),
                "image" => $imagename,
                "status" => $this->input->post("status")
            );
            
            if($this->db->insert("products",$data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function updateProduct($id,$imagename){
            
            $data = array(
                "product_name" => $this->input->post("product_name"),
                "description" => $this->input->post("description"),
                "price" => $this->input->post("price"),
                "stock" => $this->input->post("stock"),
                "weight" => $this->input->post("weight"),
                "status" => $this->input->post("status")
            );
            
            if($imagename != false){
                $data = array(
                    "product_name" => $this->input->post("product_name"),
                    "description" => $this->input->post("description"),
                    "price" => $this->input->post("price"),
                    "stock" => $this->input->post("stock"),
                    "weight" => $this->input->post("weight"),
                    "image" => $imagename,
                    "status" => $this->input->post("status")
                );
            }
            
            if($this->db->update("products",$data,"product_id = $id")){
                return true;
            }else{
                return false;
            }
        }

        public function findProduct($keyword){
            $data = array(
                "product_name" =>$keyword,
                "status" => $keyword
            );

            if($keyword == "displayed"){
                $data = array("status" => 1);
            }else if($keyword == "not displayed"){
                $data = array("status" => 0);
            }
            
            $this->db->or_like($data);
            $query = $this->db->get("products");
            return $query->result_array();
        }
        
        // untuk mengambil order id dari tabel order detail menggunakan product id
        public function getOrderId($product_id){
            $this->db->select("order_id");
            $this->db->from("order_detail");
            $this->db->where("product_id",$product_id);
            $query = $this->db->get();
            return $query->result_array();
        }

        public function deleteProduct($id){
            if($this->db->delete("products",array("product_id" => $id))){
                return true;
            }else{
                return false;
            }
        }

        public function getAllOrders($limit,$offset){
            // query untuk mengurutkan agar waiting to be confirmed diletakkan di paling atas
            $this->db->order_by("payment_status = 'Waiting to be confirmed'","DESC");
            $this->db->order_by("payment_status = 'Not Paid'","DESC");
            $this->db->order_by("payment_status");
            $query = $this->db->get("orders",$limit,$offset);
            return $query->result_array();
        }

        public function findOrder($keyword){
            $data = array(
                "receiver_name" => $keyword,
                "payment_status" => $keyword,
                "province" => $keyword,
                "city" => $keyword
            );
            $this->db->from("orders");
            $this->db->order_by("payment_status = 'Waiting to be confirmed'","DESC");
            $this->db->order_by("payment_status = 'Not Paid'","DESC");
            $this->db->order_by("payment_status");
            $this->db->or_like($data);

            $query = $this->db->get();
            return $query->result_array();
        }

        public function deleteOrder($id){
            if($this->db->delete("order_detail",array("order_id" => $id)) && $this->db->delete("payments",array("order_id" => $id)) && $this->db->delete("orders",array("order_id" => $id))){
                return true;
            }else{
                return false;
            }
        }

        public function editOrder($id,$confirm = false){
            
            if($confirm){
                $data = array(
                    "payment_status" => "Paid"
                );
            }else{
                $data = array(
                    "payment_status" => $this->input->post("status") 
                );
            }
            
            if($this->db->update("orders",$data,"order_id = $id")){
                return true;
            }else{
                return false;
            }
        }

        public function getOrderDetail($id){
            $this->db->select("orders.receiver_name, orders.payment_status, orders.order_date, orders.total_bill, order_detail.order_id, order_detail.quantity, order_detail.price, products.product_name");
            $this->db->from("order_detail");
            $this->db->join("orders","order_detail.order_id = orders.order_id");
            $this->db->join("products","order_detail.product_id = products.product_id");
            $this->db->where("order_detail.order_id",$id);
            $query = $this->db->get();
            return $query->result_array();
        }

        public function getAllPayments($limit,$offset){
            $this->db->select("payments.*, orders.payment_status, orders.receiver_name");
            $this->db->from("payments");
            $this->db->join("orders","payments.order_id = orders.order_id");
            $this->db->order_by("payment_status = 'Waiting to be confirmed'","DESC");
            $this->db->order_by("payment_status = 'Paid'","ASC");
            $this->db->order_by("order_date","ASC");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function findPayment($keyword){
            $data = array(
                "name" =>$keyword,
                "account_number" => $keyword
            );

            $this->db->select("payments.*, orders.payment_status, orders.receiver_name");
            $this->db->from("payments");
            $this->db->join("orders","payments.order_id = orders.order_id");
            $this->db->order_by("payment_status = 'Waiting to be confirmed'","DESC");
            $this->db->order_by("payment_status = 'Paid'","DESC");
            $this->db->order_by("order_date","ASC");
            $this->db->or_like($data);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function getAllSubscriber($limit,$offset){
            $this->db->order_by("status DESC, created_at DESC");
            $query = $this->db->get("subscriber");
            return $query->result_array();
        }

        public function findSubscriber($keyword){
            $data = array(
                "email" =>$keyword,
                "status" => $keyword
            );
            
            $this->db->or_like($data);
            $query = $this->db->get("subscriber");
            return $query->result_array();
        }

        public function editSubscriber($id){
            $data = array(
                "status" => $this->input->post("status")
            );

            if($this->db->update("subscriber",$data,"id_subscriber = $id")){
                return true;
            }else{
                return false;
            }
        }

        public function loginCheck($username){
            $this->db->from("user");
            $this->db->where("username",$username);
            $query = $this->db->get();
            return $query->row_array();
        }
    }