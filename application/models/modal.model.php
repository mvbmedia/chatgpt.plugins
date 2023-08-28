<?php
class Modal
{
	protected $database;
	protected $functions;

	public function __construct()
	{
		$this->database = new Database;
		$this->functions = new Functions;
	}

    # return modal existence
    public function exists($modal = null, $value = null)
    {
        if (!file_exists(VIEW . 'modals/' . $modal . '/' . $value)){
            return false;
        }

        return true;
    }
    
	# secure request
	public function secure()
	{
		# invalid request
		if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			return false;
		}
		
		# invalid request
		if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
			return false;
		}
		
		# invalid referer
		if(empty($_SERVER['HTTP_REFERER']) || parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != DOMAIN){
			return false;
		} 

		return true;
	}
	public function saveReaction($reaction, $pageId, $pageType) {
	    $this->database->query('INSERT INTO `reactions` (`reaction`, `page_id`, `page_type`) VALUES (:reaction, :pageId, :pageType)');
	    $this->database->bind(':reaction', $reaction);
	    $this->database->bind(':pageId', $pageId);
	    $this->database->bind(':pageType', $pageType);
	    $this->database->execute();

	    return true; // Return true on successful insertion
	}
	# return json status message
	public function encode($data = array())
	{
		# encode data
		$result = json_encode($data);
		
		# display data
		echo $result;
		
		# exit
		exit;
	}
}