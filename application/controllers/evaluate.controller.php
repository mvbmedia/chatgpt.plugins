<?php
class Evaluate_Controller extends Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index($slug = null)
	{
		# secure arguments
		$this->secure(func_num_args(), 1);
		
		# initiate model
		$this->model('evaluate');

		# invalid subject
		if (!empty($slug) && !$this->model->exists($slug)){
			$this->error(404);
		}

		# return evaluate view
		$this->view('evaluate/index', [
			'subject' => $this->model->subject($slug),
			'questions' => $this->model->questions(),
			'subjects' => $this->model->subjects()
		]);

		# display content
		$this->view->render();
	}
}
