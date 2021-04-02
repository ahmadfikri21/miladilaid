<?php

    class Admin extends CI_Controller{
        function __construct() {
            parent::__construct();
            $this->load->library("pagination");

        }

        public function index(){
            if($this->session->userdata("login")){
                $search = $this->input->get('searchProduct') != null ? $this->input->get('searchProduct') : false;
                // kondisional untuk menentukan apakah sedang melakukan pencarian atau tidak
                if(!$search){
                    // konfigurasi untuk pagination
                    $config["base_url"] = base_url("Admin/index");
                    $config["per_page"] = 10;
                    $config["attributes"] = array("class" => "paginationLinks");
                    $config["cur_tag_open"] = "<a class='activePaginationLink'>";
                    $config["cur_tag_close"] = "</a>";
                    // offset diambil dari url segment ke 3(contoh segmen1/segmen2/segmen3)
                    $offset = $this->uri->segment(3);

                    $config["total_rows"] = $this->Admin_model->countTableRow("products");
                    $data["products"] = $this->Admin_model->getAllProducts($config["per_page"],$offset);

                    $this->pagination->initialize($config);
                }else{
                    $search = strtolower($search);
                    $data["products"] = $this->Admin_model->findProduct($search);
                }
    
                $this->load->view("admin/header");
                $this->load->view("admin/products",$data);
                $this->load->view("admin/footer");
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function login(){
            if(!$this->session->userdata("login")){
                $this->form_validation->set_error_delimiters('<p class="formErrorMsg">', '</p>');
                $this->form_validation->set_rules("username","Username","required", array("required" => "Username should not be empty!"));
                $this->form_validation->set_rules("password","Username","required", array("required" => "Password should not be empty!"));

                if(!$this->form_validation->run()){
                    $this->load->view("Admin/login");
                }else{
                    $username = $this->input->post("username");
                    $pass = $this->input->post("password");
                    // mengambil data username dari database
                    $userData = $this->Admin_model->loginCheck($username);

                    // mengecek apakah ada username di database
                    if($userData){
                        // memverifikasi password yang diketik dengan password yang didapat di database
                        $hash = $userData['password'];
                        if(password_verify($pass,$hash)){
                            $array = array(
                                "login" => $userData['user_id']
                            );
                            $this->session->set_userdata($array);
                            $this->session->set_flashdata("succNotice","Login Success !");
                            redirect("Admin/index");
                        }else{
                            $this->session->set_flashdata("errNotice","Wrong Password!");
                            redirect("Admin/login");
                        }
                    }else{
                        $this->session->set_flashdata("errNotice","Username is not listed in our database");
                        redirect("Admin/login");
                    }
                }
            }else{
                redirect("Admin/index");
            }
        }

        public function logout(){
            $this->session->unset_userdata("login");

            $this->session->set_flashdata("succNotice","Logout Success !");
            redirect();
        }
        
        public function addNewProduct(){
            if($this->session->userdata("login")){
                // form validation rules
                $this->form_validation->set_rules("product_name","Product Id","required");
                $this->form_validation->set_rules("description","Description","required");
                $this->form_validation->set_rules("price","Price","required");
                $this->form_validation->set_rules("stock","Stock","required");
                $this->form_validation->set_rules("weight","Weight","required");
                $this->form_validation->set_rules("status","Status","required");


                if(!$this->form_validation->run()){
                    // flashdata dibawah digunakan untuk menyimpan data yang sudah diinputkan oleh user agar user tidak perlu mengisi ulang
                    $flashdata = array(
                        "prod_name" => $this->input->post("product_name"),
                        "description" => $this->input->post("description"),
                        "price" => $this->input->post("price"),
                        "stock" => $this->input->post("stock"),
                        "weight" => $this->input->post("weight"),
                        "status" => $this->input->post("status")
                    );
                    $this->session->set_flashdata($flashdata);
                    $this->session->set_flashdata("errNotice","Please fill out all the field !");
                    redirect("Admin/index");
                }else{
                    // file upload configuration
                    $config["upload_path"] = "./assets/img/product_image/";
                    $config["allowed_types"] = 'png|jpg|jpeg';
                    $config["max_size"] = 2048;
                    $config["max_width"] = 0;
                    $config["max_height"] = 0;
                    $config["file_ext_tolower"] = true;
        
                    $this->load->library('upload',$config);
        
                    $imagename = pathinfo($_FILES["image"]["name"],PATHINFO_BASENAME);
        
                    if($this->upload->do_upload('image')){                
                        if($this->Admin_model->insertProduct($imagename)){
                            $this->session->set_flashdata("succNotice","New Product Added!");
                            redirect("Admin/index");
                        }else{
                            $this->session->set_flashdata("errNotice","There is an error in database, Try again later!");
                            redirect("Admin/index");
                        }
                    }
                }
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function deleteProduct($id,$name){
            if($this->session->userdata("login")){
                // mengambil order id
                $order_id = $this->Admin_model->getOrderId($id);
                if(count($order_id) > 0){
                    // mendelete order
                    foreach($order_id as $ids){
                        $this->Admin_model->deleteOrder($ids['order_id']);
                    }
                    
                    if($this->Admin_model->deleteProduct($id)){
                        unlink("assets/img/product_image/$name");
                        $this->session->set_flashdata("succNotice","Product Deleted!");
                        redirect("Admin/index");
                    }else{
                        $this->session->set_flashdata("errNotice","There is an error in database, Try again later!");
                        redirect("Admin/index");
                    }
                }
                else{
                    // hanya mendelete produk
                    if($this->Admin_model->deleteProduct($id)){
                        unlink("assets/img/product_image/$name");
                        $this->session->set_flashdata("succNotice","Product Deleted!");
                        redirect("Admin/index");
                    }else{
                        $this->session->set_flashdata("errNotice","There is an error in database, Try again later!");
                        redirect("Admin/index");
                    }
                }
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function editProduct($id){
            if($this->session->userdata("login")){
                $data["product"] = $this->Admin_model->getProductById($id);
                $this->form_validation->set_rules("product_id","Product Id","required");

                if(!$this->form_validation->run()){
                    $this->load->view("admin/header");
                    $this->load->view("admin/editProduct",$data);
                    $this->load->view("admin/footer");
                }else{
                    $imagename = false;
                    // untuk mengecek apakah file diisi atau tidak
                    if($_FILES['image']['size'] != 0){
                        $config["upload_path"] = "./assets/img/product_image/";
                        $config["allowed_types"] = 'png|jpg|jpeg';
                        $config["max_size"] = 2048;
                        $config["max_width"] = 0;
                        $config["max_height"] = 0;
                        $config["file_ext_tolower"] = true;
            
                        $this->load->library('upload',$config);
            
                        $imagename = pathinfo($_FILES["image"]["name"],PATHINFO_BASENAME);
                        $this->upload->do_upload('image');
                    }
                
                    if($this->Admin_model->updateProduct($id,$imagename)){
                        $this->session->set_flashdata("succNotice","Product Edited !");
                        redirect("Admin/index");
                    }else{
                        $this->session->set_flashdata("errNotice","There is an error in database, Try again later!");
                        $this->load->view("admin/header");
                        $this->load->view("admin/editProduct",$data);
                        $this->load->view("admin/footer");
                    }
                    
                }
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }
        
        public function orders(){
            if($this->session->userdata("login")){
                $search = $this->input->get('searchProduct') != null ? $this->input->get('searchProduct') : false;
                
                if(!$search){
                    $config["base_url"] = base_url("Admin/orders");
                    $config["total_rows"] = $this->Admin_model->countTableRow("orders");
                    $config["per_page"] = 10;
                    $config["attributes"] = array("class" => "paginationLinks");
                    $config["cur_tag_open"] = "<a class='activePaginationLink'>";
                    $config["cur_tag_close"] = "</a>";
                    $offset = $this->uri->segment(3);
    
                    $this->pagination->initialize($config);
    
                    $data["orders"] = $this->Admin_model->getAllOrders($config["per_page"],$offset);
                }else{
                    $search = strtolower($search);
                    $data["orders"] = $this->Admin_model->findOrder($search);
                }
                
                $this->load->view("admin/header");
                $this->load->view("admin/orders",$data);
                $this->load->view("admin/footer");
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function orderDetail($id){
            if($this->session->userdata("login")){
                if($id){
                    $data["detail"] = $this->Admin_model->getOrderDetail($id);
    
                    $this->load->view("admin/header");
                    $this->load->view("admin/orderDetail",$data);
                    $this->load->view("admin/footer");
                }
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function deleteOrder($id){
            if($this->session->userdata("login")){
                if($this->Admin_model->deleteOrder($id)){
                    $this->session->set_flashdata("succNotice","Order Deleted!");
                    redirect("Admin/orders");
                }else{
                    $this->session->set_flashdata("errNotice","There is an error in database, Try again later!");
                    redirect("Admin/orders");
                }
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function editOrder($id,$confirm = false){
            if($this->session->userdata("login")){
                if($this->Admin_model->editOrder($id,$confirm)){
                    echo true;
                }else{
                    echo false;
                }

                if($confirm){
                    $this->session->set_flashdata("succNotice","Payment is Confirmed!");
                    redirect("Admin/orderDetail/$id");
                }
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function payments(){
            if($this->session->userdata("login")){
                $search = $this->input->get('searchProduct') != null ? $this->input->get('searchProduct') : false;

                if(!$search){
                    $config["base_url"] = base_url("Admin/payments");
                    $config["per_page"] = 10;
                    $config["attributes"] = array("class" => "paginationLinks");
                    $config["cur_tag_open"] = "<a class='activePaginationLink'>";
                    $config["cur_tag_close"] = "</a>";
                    $offset = $this->uri->segment(3);
                    
                    $config["total_rows"] = $this->Admin_model->countTableRow("payments");
                    $data["payments"] = $this->Admin_model->getAllPayments($config["per_page"],$offset);

                    $this->pagination->initialize($config);
                }else{
                    $search = strtolower($search);
                    $data["payments"] = $this->Admin_model->findPayment($search);
                }
                
                $this->load->view("admin/header");
                $this->load->view("admin/payments",$data);
                $this->load->view("admin/footer");
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function subscriber(){
            if($this->session->userdata("login")){
                $search = $this->input->get('searchProduct') != null ? $this->input->get('searchProduct') : false;

                if(!$search){
                    $config["base_url"] = base_url("Admin/payments");
                    $config["per_page"] = 10;
                    $config["attributes"] = array("class" => "paginationLinks");
                    $config["cur_tag_open"] = "<a class='activePaginationLink'>";
                    $config["cur_tag_close"] = "</a>";
                    $offset = $this->uri->segment(3);
                    
                    $config["total_rows"] = $this->Admin_model->countTableRow("subscriber");
                    $data["subscriber"] = $this->Admin_model->getAllSubscriber($config["per_page"],$offset);

                    $this->pagination->initialize($config);
                }else{
                    $search = strtolower($search);
                    $data["subscriber"] = $this->Admin_model->findSubscriber($search);
                }
                
                $this->load->view("admin/header");
                $this->load->view("admin/subscriber",$data);
                $this->load->view("admin/footer");
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }

        public function editSubscriber($id){
            if($this->session->userdata("login")){
                if($this->Admin_model->editSubscriber($id)){
                    echo true;
                }else{
                    echo false;
                }
            }else{
                $this->session->set_flashdata("errNotice","You have to be logged in !");
                redirect("Admin/login");
            }
        }
    }
    