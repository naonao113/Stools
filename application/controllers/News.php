<?php 
    
    class News extends CI_Controller{
        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('news_model');

        }

        public function view($page="news"){
            if(!file_exists(APPPATH.'views/pages/'.$page.'.php')){
               // show_404();
               //echo "2222";
            }
            $data['title']=ucfirst($page);
            $data['conent']=$this->news_model->get_news();
            $this->load->view("template/header",$data);
            $this->load->view("pages/".$page,$data);
            $this->load->view("template/footer",$data);

        }
        public function index(){
            
        }
    }