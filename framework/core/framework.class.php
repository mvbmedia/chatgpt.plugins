<?php
class Framework
{
    protected $database;

    protected $controller = 'index';
    protected $method = 'index';
    protected $params = array();

    public $bot;
    public $template;

    public function __construct()
    {
        # initiate classes
        $this->database = new Database;
        $this->template = new Template;
        $this->bot = new Bot;

        # set array
        $url = $this->define_url();

        try {
            # reactions
             if ($url[0] == 'modals' && isset($url[1]) && $url[1] == 'saveReaction') {
                $this->controller = 'modal'; 
                $this->method = 'saveReaction';
                unset($url[0], $url[1]);
            }
            # Guide slug
            if ($url[0] == 'articles' && $this->template->configuration['article_slug'] !== 'articles'){
                # set default controller
                $this->controller = 'index';
                # set method
                $this->method = 'error';
                # controller
            } elseif (file_exists( CONTROLLER . $url[0] . '.controller.php')){
                # set controller
                $this->controller = $url[0];
                # remove controller parameter
                unset($url[0]);
                # category
            } elseif ($this->category($url[0])) {
                # set category controller
                $this->controller = 'category';
                # page
            } elseif ($this->page($url[0])) {
                # set page controller
                $this->controller = 'index';
                # set page method
                $this->method = 'page';
                # guide
            } elseif ($url[0] == $this->template->configuration['article_slug']){
                # set page controller
                $this->controller = 'articles';
                # remove controller parameter
                unset($url[0]);
                # homepage
            } elseif (empty($url[0])) {
                # set homepage controller
                $this->controller = 'index';
                # default
            } else {
                # set default controller
                $this->controller = 'index';
                # set method
                $this->method = 'error';
            }
        } catch (Exception $error) {
            # display error
            echo $error->getMessage();
        }

        # require controller
        require_once(CONTROLLER . $this->controller . '.controller.php');

        # set controller class
        $this->controller = $this->controller . '_Controller';
        $this->controller = new $this->controller;

        # set method
        if (isset($url[0]) && method_exists($this->controller, $url[0])){
            # set method
            $this->method = $url[0];

            # remove method parameter
            unset($url[0]);
        } elseif (isset($url[1]) && method_exists($this->controller, $url[1])){
            # set method
            $this->method = $url[1];

            # remove method parameter
            unset($url[1]);
        }

        # set parameters
        $this->params = $url ? array_values($url) : array();

        # callable method
        if (is_callable([$this->controller, $this->method])){
            # call controller, method and parameters
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
    }

    # define url
    protected function define_url($url = array())
    {
        # sanitize url
        if (!empty($_SERVER['REQUEST_URI'])){
            $uri = strtok($_SERVER["REQUEST_URI"], '?');
            $url = explode('/', filter_var(trim($uri, '/'), FILTER_SANITIZE_URL));
        }

        return $url;
    }

    # category
    protected function category($slug = null)
    {
        $this->database->query('SELECT COUNT(*) FROM `categories` WHERE `categories`.`website` = :website AND `categories`.`slug` = :slug AND `categories`.`status` = :status');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':slug', $slug);
        $this->database->bind(':status', 'active');
        $this->database->execute();
        $result = $this->database->fetchColumn();

        return $result;
    }

    # page
    protected function page($slug = null)
    {
        $this->database->query('SELECT COUNT(*) FROM `pages` WHERE `pages`.`website` = :website AND `pages`.`slug` = :slug AND `pages`.`status` = :status');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':slug', $slug);
        $this->database->bind(':status', 'active');
        $this->database->execute();
        $result = $this->database->fetchColumn();

        return $result;
    }
}