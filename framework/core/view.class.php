<?php
class View
{
	protected $database;
	protected $functions;
	protected $template;
	
	protected $data;
	protected $view;
	
	public function __construct($view, $data = array())
	{
        $this->database = new Database;
        $this->functions = new Functions;
        $this->template = new Template;

        $this->data = $data;
        $this->view = $view;
	}
	
	# display content
	public function render()
	{
		if (file_exists(VIEW . $this->view . '.php')) {
			require_once(VIEW . $this->view . '.php');
		}
	}
	
	# return action
	public function action()
	{
		return (explode('/', $this->view))[0];
	}
}