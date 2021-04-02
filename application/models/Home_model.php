<?php

    class Home_model extends CI_Model{
        
        public function getProducts($id = FALSE){
            if($id){
                $query = $this->db->get_where("products",array("product_id" => $id,"status" => 1));
                return $query->row_array();
            }else{
                $query = $this->db->get_where("products",array("status" => "1"));
                return $query->result_array();
            }
        }

        public function addSubscriber($email){
            $data = array(
                "email" => $email
            );
            $this->db->insert('subscriber',$data);
            return ($this->db->affected_rows() == 1) ? true : false;
        }

    }