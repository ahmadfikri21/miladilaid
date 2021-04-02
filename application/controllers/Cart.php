<?php

    class Cart extends CI_Controller{
        public function index(){
            $data['title'] = "Shopping Bag";

            // untuk mengecek apakah cart kosong atau tidak
            if(!empty($this->cart->contents())){
                // array digunakan untuk menyimpan id produk
                $productIds = [];
                $items = $this->cart->contents();
                foreach($items as $content){
                    // memasukkan id product dari isi cart kedalam array productids
                    array_push($productIds,$content['id']);

                }
                
                // menyimpan image setiap product sesuai id
                $data['db'] = $this->Cart_model->getProductImageAndWeight($productIds);
                // menyimpan isi cart
                $data['items'] = $items;
            }else{
                $data['items'] = [];
            }

            // echo "<pre>";
            // print_r($items);
            // echo "</pre>";
            $this->load->view("templates/header",$data);
            $this->load->view("main/shoppingBag",$data);
            $this->load->view("templates/footer");
        }

        public function removeItem(){
            $rowid = $this->input->get("rowid");
            $data = array(
                "rowid" => $rowid,
                "qty" => 0
            );

            if($this->cart->update($data)){
                echo "Item Removed!";
            }else{
                echo "Something went wrong, please try again later";
            }
        }

        public function updateCart(){
            $rowid = $this->input->get("rowid");
            $qty = $this->input->get("quantity");
            $action = $this->input->get("action");

            if($action == "remove"){
                $data = array(
                    "rowid" => $rowid,
                    "qty" => 0
                );
    
                if($this->cart->update($data)){
                    echo "Item Removed!";
                }else{
                    echo "Something went wrong, please try again later";
                }
            }else if($action == "update"){
                $data = array(
                    "rowid" => $rowid,
                    "qty" => $qty
                );
    
                $this->cart->update($data);
            }
        }

        public function checkout(){
            $data['title'] = "Checkout";

            if(count($this->cart->contents()) > 0){
                // mengambil data provinsi dari api rajaongkir
                $provinsi = $this->getProvinsi();
                $data['provinsi'] = $provinsi['rajaongkir']['results'];
                // menyimpan isi dari cart
                $data['items'] = $this->cart->contents();

                $this->form_validation->set_error_delimiters('<p class="formErrorMsg">', '</p>');

                // melakukan form validation
                $this->form_validation->set_rules("nama","Nama","required", array("required" => "harap isi nama penerima sebelum melanjutkan"));
                $this->form_validation->set_rules("no_telp","No Telp","required|numeric", array("required" => "harap isi nomor telepon sebelum melanjutkan","numeric" => "Nomor telepon hanya diperbolehkan menggunakan angka"));
                $this->form_validation->set_rules("email","Email","required|valid_emails", array("required" => "harap isi email sebelum melanjutkan","valid_emails" => "Harap isi email yang valid"));
                $this->form_validation->set_rules("alamat","Alamat","required", array("required" => "harap isi alamat sebelum melanjutkan"));
                $this->form_validation->set_rules("provinsi","Provinsi","required", array("required" => "harap isi provinsi sebelum melanjutkan"));
                $this->form_validation->set_rules("kota","Kota","required", array("required" => "harap isi kota sebelum melanjutkan"));

                if(!$this->form_validation->run()){
                    $this->load->view("templates/header",$data);
                    $this->load->view("main/checkout");
                    $this->load->view("templates/footer");
                }else{
                    $post["prod_id"] = [];
                    $post["qty"] = [];
                    foreach($this->input->post("prod_id") as $ids){
                        array_push($post["prod_id"],$ids);
                    }
                    
                    foreach($this->input->post("qty") as $quantity){
                        array_push($post["qty"],$quantity);
                    }

                    $post["nama"] = $this->input->post("nama");
                    $post["no_telp"] = $this->input->post("no_telp");
                    $post["email"] = $this->input->post("email");
                    $post["alamat"] = $this->input->post("alamat");
                    $post["provinsi"] = $this->input->post("provinsi");
                    $post["kota"] = $this->input->post("kota");
                    $post["total_bill"] = $this->input->post("total_bill");
                    $ongkir = $this->input->post("ongkir");

                    $orderId = $this->Cart_model->addOrder($post);
                    if($orderId != FALSE){
                        $this->session->set_userdata(array("checkout" => true, "ongkir" => $ongkir,"orderId" => $orderId));
                        redirect("Cart/paymentInfo/".$orderId);
                    }else{
                        $this->session->set_flashdata("errNotice","We're Sorry, There is something wrong in our server, please try again later");
                        redirect("Home/index");
                    }
                }
            }else{
                $this->session->set_flashdata("errNotice","Cart is empty !");
                redirect("Home/index");
            }
        }

        public function paymentInfo($orderId = FALSE){
            if($this->session->userdata("checkout")){
                if($orderId){
                    
                    $data["title"] = "PaymentInfo";
                    // mengambil data order, detail order dan product sesuai detail order untuk dioper ke view
                    $data["order"] = $this->Cart_model->getOrderById($orderId);
                    $data["orderedProducts"] = $this->Cart_model->getOrderDetails($orderId);
                    $data["products"] = [];
                    foreach($data["orderedProducts"] as $prod){
                        array_push($data["products"],$this->Cart_model->getProductsById($prod["product_id"]));
                    }
    
                    $this->load->view("templates/header",$data);
                    $this->load->view("main/paymentInfo",$data);
                    $this->load->view("templates/footer");
                }else{
                    $this->session->set_flashdata("errNotice","No order(s) found");
                    redirect("Home/index");
                }
            }else{
                $this->session->set_flashdata("errNotice","Session is over, please reorder");
                redirect("Home/index");
            }

        }

        public function cancelOrder($id){
            if($this->Admin_model->deleteOrder($id)){
                $this->session->unset_userdata("checkout");
                $this->cart->destroy();
                $this->session->set_flashdata("succNotice","Order Deleted !");
                redirect("Home/index");
            }else{
                $this->session->set_flashdata("errNotice","There is an error in our database, Please wait later");
                redirect("Home/index");
            }
        }

        public function confirmPayment(){
            if($this->Cart_model->insertPayment()){
                $this->Cart_model->updatePaymentStatus();
                $this->session->set_userdata("paymentConfirm",true);
                $this->session->set_flashdata("succNotice","Payment confirmation has been send!");
                
                echo true;
            }else{
                echo false;
            }
        }

        public function getProvinsi(){
                $apiKey = "e3e8d0eb90a2acb1300a17eda2bea618";
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: $apiKey"
                ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    return json_decode($response,true);
                }
        }

        public function getKota(){
            $apiKey = "e3e8d0eb90a2acb1300a17eda2bea618";
            $province_id = $this->input->get("province_id");

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=$province_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: $apiKey"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        }

        public function getCost(){
            $apiKey = "e3e8d0eb90a2acb1300a17eda2bea618";
            $kota = $this->input->get("kotaValue");
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "origin=108&destination=$kota&weight=100&courier=jne",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: $apiKey"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        }
    }