<?php
class News extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $query = NULL;
        $this->load->model('news_model');
        $this->load->model('main');
        $this->load->helper('url_helper');
        $art = array('1' , '2' , '3' , '4' , '5' , '6' , '7' , '8');
        $config = array('system_name'    => $this->main->system_name($query),
                        'notice'         => $this->main->notice($query),
                        'contact'        => $this->main->contact($query),
                        'copyright_year' => $this->main->copyright_year($query),
                        'record'         => $this->main->record($query)
        );
        $this->smarty->assign('config',$config);
        $this->smarty->assign('art_title' , $art);
    }

    public function index($query = NULL,$title = NULL)
    {
        $data = $this->news_model->get_all($query);
        foreach ($data as &$articles) {
            $articles["updated"] = date("Y-m-d H:i:s", $articles["updated"]);
        }
        $this->smarty->assign('title',$title);
        $this->smarty->assign('articles',$data);
        $this->smarty->display('news/index.html');
    }

    public function view()
    {
        $title = $this->input->get('title');
        $data = $this->news_model->get_news($title);
        foreach ($data as &$articles) {
            $articles["updated"] = date("Y-m-d H:i:s", $articles["updated"]);
        }

        if ($title === 'all') {
            $this->smarty->assign('title', $data);
            $this->smarty->display('news/index.html');
        }
        else if (empty($data))
        {
            echo "文章不存在！";
        }
        else {
            $this->smarty->assign('arttitle', $data);
            $this->smarty->assign('title', $title);
            $this->smarty->display('news/view.html');
        }

    }

    public function create($title = NULL)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->smarty->assign('title', $title);
            $this->smarty->display('news/create.html');
        }
        else
        {
            $this->news_model->set_news();
            $this->smarty->assign('title', $title);
            $this->smarty->display('news/success.html');
        }
    }



}
