<?php
class Articles_Controller extends Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index($slug = null)
	{
		# secure arguments
		$this->secure(func_num_args(), 1);
		
		# initiate model
		$this->model('articles');

		# invalid Guide
		if (!empty($slug) && !$this->model->exists($slug)){
			$this->error(404);
		}

		# return subject view
		if ($this->model->exists($slug)){
			$this->view('articles/article', [
				'articles' => $this->model->articles(),
				'article' => $this->model->article($slug),
				'top-offers' => $this->model->top_offers()
			]);
		# return index view
		} else {
			$this->view('articles/index', [
				'articles' => $this->model->articles(),
				'top-offers' => $this->model->top_offers(),
				'experts' => $this->model->total(),
                'words' => $this->model->words()
			]);
		}

		# display content
		$this->view->render();
	}
}
