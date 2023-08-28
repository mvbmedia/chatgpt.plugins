<?php
class Category_Controller extends Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index($slug = null)
	{
		# secure arguments
		$this->secure(func_num_args(), 1);
		
		# initiate model
		$this->model('category');
		
		# return view
		$this->view('category/index', [
			'top-offers' => $this->model->top_offers($slug),
			'offers' => $this->model->offers($slug),
			'category' => $this->model->category($slug),
			'tooltip' => $this->functions->tooltip()
		]);
		
		# display content
		$this->view->render();
	}
}