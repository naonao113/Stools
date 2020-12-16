<?php 
    
    class News extends CI_Controller{
        
        public function view($page="news"){
            if(!file_exists(APPPATH.'views/pages/'.$page.'.php')){
               // show_404();
               //echo "2222";
            }
            $data['title']=ucfirst($page);
            $this->load->view("template/header",$data);
            $this->load->view("pages/".$page,$data);
            $this->load->view("template/footer",$data);

        }
    }