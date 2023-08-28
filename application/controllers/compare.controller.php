<?php
class Compare_Controller extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
        # secure arguments
        $this->secure(func_num_args(), 1);

        # initiate model
        $this->model('compare');

        # return view
        $this->view('compare/index', [
            'offers' => $this->model->offers()
        ]);

        # display content
        $this->view->render();
    }
}