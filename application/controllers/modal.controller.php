<?php
class Modal_Controller extends Controller
{
	public function __construct() {
		parent::__construct();
	}
	
	public function index($modal = null, $value = null)
	{
		# initiate model
		$this->model('modal');
				
		# secure request
		if (!$this->model->secure()){
			$this->error();
		}
		
		# model existence
		if (!$this->model->exists($modal . '/' . $value)){
			$this->error();
		}
		
		# return view
		$this->view('modals/' . $modal . '/' . $value);
		
		# display content
		$this->view->render();
	}
	public function saveReaction()
	{
	    // Make sure this is an Ajax request
	    if (!$this->model->secure()) {
	        $this->error();
	    }

	    // Get the posted data (reaction, pageId, pageType)
	    $data = json_decode(file_get_contents('php://input'), true);
	    $reaction = $data['reaction'];
	    $pageId = $data['pageId'];
	    $pageType = $data['pageType'];

	    // Save the reaction using the model
	    $result = $this->model->saveReaction($reaction, $pageId, $pageType);

	    // Send a response to the client
	    $this->model->encode(['status' => $result ? 'success' : 'failure']);
	}
}