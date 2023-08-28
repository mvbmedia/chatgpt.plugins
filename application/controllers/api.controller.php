<?php
class Api_Controller extends Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index($module = null, $data = null)
	{
		# initiate model
		$this->model('api');

		# secure request
		if (!$this->model->secure()){
			$this->error(404);
		}

		# block ip
		if ($this->functions->ip() == '78.108.251.103' && $module == 'views'){
		    return;
        }

		# set module as functions
		if (method_exists('Api', $module))
		{
			# return module
			$this->model->{$module}($data);
		}
	}
}