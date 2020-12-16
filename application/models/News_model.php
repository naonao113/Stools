<?php

    class News_model extends CI_Model{
        function __construct()
        {
            $this->load->database();
        }

        //查询
        public function get_news($slug=false){
            if($slug===false){
                $query=$this->db-get('news');
                var_dump($query);
                return $query->result_array();
            }
            $query=$this->db->get_where('news',array('slug'=>$slug));
            return $query->row_array();
        }
    }