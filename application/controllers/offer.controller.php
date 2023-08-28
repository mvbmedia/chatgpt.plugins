<?php
class Offer_Controller extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index($slug = null)
    {
        # secure arguments
        $this->secure(func_num_args(), 2);

        # initiate model
        $this->model('offer');

        # invalid subject
        if (empty($slug) || !$this->model->exists($slug)){
            $this->error(404);
        }

        # redirect
        $this->model->click($slug);
    }
}
