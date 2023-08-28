<?php
class Index_Controller extends Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		# initiate model
		$this->model('index');

		# return view
	    $this->view('index/index', [
	        'top-offers' => $this->model->top_offers(),
	        'reviews' => $this->model->reviews(),
	        'categories' => $this->model->categories(),
	        'articles' => $this->model->articles(),
	        'websites' => $this->model->website() // Pass the website details to the view
	    ]);

		# display content
		$this->view->render();
	}

	public function error($code = 404)
	{
        # initiate model
        $this->model('index');

        # invalid error code
        if (!in_array($code, [400, 401, 402, 403, 404, 500, 502, 503, 504])){
            $code = 404;
        }

        # return view
        $this->view('index/error', [
            'code' => $code,
            'description' => $this->model->code($code)
        ]);

        # set header
        header("HTTP/2.0 {$code} {$this->model->code($code)}");

        # display content
        die($this->view->render());
	}

	public function page($slug = null)
	{
		# secure arguments
		$this->secure(func_num_args(), 1);

		# remove parameters
		list($slug) = explode('?', $slug);

		# initiate model
		$this->model('index');

		# return view
		$this->view('index/page', [
			'page' => $this->model->page($slug)
		]);

		# display content
		$this->view->render();
	}
}
