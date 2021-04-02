<?php

    class Home extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->library('session');
            // $this->session->sess_destroy();
        }
        
        public function index(){
            $data['products'] = $this->Home_model->getProducts();

            $this->load->view("main/homepage",$data);
            $this->load->view("templates/footer");
        }

        public function addSubscriber(){
            $result = $this->Home_model->addSubscriber($this->input->post("email"));
            if($result){
                echo "Success, Email added to our list!";
            }else{
                echo "We're sorry, it appears to be an error in our database, please try again later";
            }
        }

        public function addToCart($id){
            $prod_id = $id;
            $qty = $this->input->get("qty");
            $prod_name = $this->input->get("prod_name");
            $price = $this->input->get("price");
            $weight = $this->input->get("weight");

            if($qty != 0){
                $items = array(
                    'id' => $prod_id,
                    'qty' => $qty,
                    'price' => $price,
                    'name' => $prod_name,
                    'options' => array('weight' => $weight)
                );
                
                if($this->cart->insert($items)){
                    $this->session->set_flashdata("succNotice","Item(s) Added to Cart !");
                    redirect("Home/index");
                }else{
                    $this->session->set_flashdata("errNotice","We're sorry, it appears there is some problem in our server, please try again later");
                    redirect("Home/index");
                }
                
            }else{
                $this->session->set_flashdata("errNotice","Please fill the quantity box");
                redirect("Home/index");
            }
        }

        public function productDetail($productId){
            $data["title"] = "Product Detail";
            if(isset($productId)){
                $data["products"] = $this->Home_model->getProducts($productId);

                if($data["products"]){
                    $this->load->view("templates/header",$data);
                    $this->load->view("main/productDetail",$data);
                    $this->load->view("templates/footer");
                }else{
                    $this->session->set_flashdata("errNotice","We're sorry, it appears there is some problem in our server, please try again later");
                    redirect("Home/index"); 
                }
            }
        }

        public function isCheckout(){
            $this->session->set_flashdata("errNotice","Can't add item to cart ,Please wait until your order is finished");
            redirect("Home/index");
        }
    }