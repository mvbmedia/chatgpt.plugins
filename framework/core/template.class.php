<?php
class Template
{
	protected $database;
	protected $functions;

	public $website;
    public $categories;
    public $configuration;

	public function __construct()
	{
		$this->database = new Database;
		$this->functions = new Functions;

		# set variables
		$this->website = $this->website();
        $this->categories = $this->categories();
        $this->configuration = $this->configuration();
	}

	# display template
	public function display($template = null, $data = array())
	{
		# display template item
		if (file_exists(APP . 'templates/' . $template . '.inc.php')){
			include(APP . 'templates/' . $template . '.inc.php');
		}
	}

	# display notification
	public function message($message = null, $status = null)
	{
		$html = '<div class="modal" id="alert" role="alert">';
		$html .= '<div class="modal-container">';
		$html .= '<span class="modal-content">' . $message . '</span>';
		$html .= '<div class="modal-footer"><button class="btn btn-primary" data-exit="true">Klaar</button></div>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	# return slug
	public function slug()
	{
		# set variables
		$result = '';

		# set slug
		if (!empty($_SERVER['REQUEST_URI'])){
			list($result) = explode('/', filter_var(trim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL));
		}

		# return slug
		return $result;
	}

	# return categories
	public function categories()
	{
		$this->database->query('SELECT `slug`, `name`, `title`, `icon` FROM `categories` WHERE `categories`.`website` = :website AND `categories`.`status` = :status ORDER BY `categories`.`name` ASC');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

		return $result;
	}

	# return website data
	public function website()
	{
		# meta category
		$this->database->query('SELECT `websites`.`name`, `websites`.`slogan`, `websites`.`description`, `websites`.`thumbnail`, `websites`.`google_analytics`, `websites`.`type`, `websites`.`host`, `websites`.`domain`, `websites`.`country`, `websites`.`meta_title`, `websites`.`meta_description`, `websites`.`meta_keywords`, `configuration`.`footer_description` FROM `websites` LEFT JOIN `configuration` ON `websites`.`ID` = `configuration`.`website` WHERE `websites`.`ID` = :website');
		$this->database->bind(':website', WEBSITE);
		$result = $this->database->single();

		return $result;
	}

	# return page configuration
	public function configuration()
	{
		$this->database->query('SELECT * FROM `configuration` WHERE `configuration`.`website` = :website');
		$this->database->bind(':website', WEBSITE);
		$result = $this->database->single();

		return $result;
	}

	# return subdomain
	public function subdomain($subdomain = null)
	{
		# return subdomain
		$subdomain = (isset($_SERVER['HTTP_HOST']) ? explode('.', $_SERVER['HTTP_HOST']) : null);

		# set subdomain
		$subdomain = array_shift($subdomain);

		return $subdomain;
	}
	
	# return pages
	public function pages()
	{
		$this->database->query('SELECT `name`, `slug` FROM `pages` WHERE `website` = :website AND `sitemap` = :sitemap AND `status` = :status ORDER BY `name` DESC');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':sitemap', 'yes');
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

		return $result;
	}
	
	# return reviews
	public function reviews()
	{
		$this->database->query('SELECT `name`, `slogan`, `symbol`, `slug` FROM `subjects` WHERE `website` = :website AND `status` = :status ORDER BY `rating` DESC, `name` ASC LIMIT 0, 10');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

		return $result;
	}
}