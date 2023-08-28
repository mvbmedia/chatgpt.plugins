<?php
class Controller
{
    protected $database;
    protected $functions;

    protected $model;
    protected $view;
    protected $data = array();

    public function __construct()
    {
        # initialize classes
        $this->database = new Database;
        $this->functions = new Functions;

        # track actions
        $this->tracking();

        # set country
        $_SESSION['geo'] = $this->geo();
    }

    # return view
    protected function view($view, $data = array())
    {
        # set view
        $this->view = new View($view, $data);

        # return view
        return $this->view;
    }

    # return model
    protected function model($model, $data = array())
    {
        # return model
        if (file_exists(MODEL . $model . '.model.php')) {
            # return file
            require(MODEL . $model . '.model.php');

            # set class
            $this->model = new $model;
        }
    }

    # geo location
    public function geo()
    {
        $key = '7009afb76870d58ec99f1205e636fe03cc5b6dfb5ed80d39c223f6ab5b972228';

        # initialize connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.ipinfodb.com/v3/ip-country/?key=' . $key . '&ip=' . $this->functions->ip() . '&format=json');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: ' . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '')
        ]);

        # set result
        $result = curl_exec($ch);

        # close connection
        curl_close($ch);

        # decode result
        $array = json_decode($result, true);

        $result = [
            "ip" => $array['ipAddress'] ?? null,
            "country_code2" =>  $array['countryCode'] ?? 'nl'
        ];

        # return result
        return ($result !== false ? $result : null);
    }

    # track actions
    protected function tracking()
    {
        # cancel on crawler
        if ($this->functions->bot()){
            return;
        }

        # destroy current sessions
        if (!isset($_SESSION['created']) || ($_SESSION['created'] + 7689600 < time())){
            # destroy current sessions
            session_unset();

            # renew sessions
            $_SESSION['created'] = time();
        }

        # set session
        if (!isset($_SESSION['session'])){
            $_SESSION['session'] = uniqid();
        }

        # set campaign sessions
        foreach($this->campaigns() as $row)
        {
            $request = $row['request'];

            if (isset($_GET[$request]) && !empty($_GET[$request]) && (!isset($_SESSION['token']) || $_SESSION['token'] !== $_GET[$request])){
                $_SESSION['campaign'] = $row['ID'];
                $_SESSION['token'] = $_GET[$request];
            }
        }

        # set campaign
        $campaign = ($_SESSION['campaign'] ?? null);

        # set token
        $token = ($_SESSION['token'] ?? null);

        # set entry
        $entry = parse_url(($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

        # set referer
        $referer = (isset($_SERVER['HTTP_REFERER']) ? $this->functions->host($_SERVER['HTTP_REFERER']) : null);

        # set country
        $country = ($_SESSION['geo']['country_code2'] ?? 'nl');

        # set device
        $device = $this->functions->device();

        # set platform
        $platform = $this->functions->platform();

        # set browser
        $browser = $this->functions->browser();

        # set ip address
        $ip = $this->functions->ip();

        # set controller
        list($controller) = explode('/', trim($entry, '/'));

        # set tracking data
        if ($controller !== 'api'){
            $this->database->query('INSERT INTO `tracking` (`website`, `campaign`, `token`, `entry`, `referer`, `country`, `device`, `platform`, `browser`, `ip_address`, `session`, `created`) VALUES (:website, :campaign, :token, :entry, :referer, :country, :device, :platform, :browser, :ip_address, :session, :created)');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':campaign', $campaign);
            $this->database->bind(':token', $token);
            $this->database->bind(':entry', $entry);
            $this->database->bind(':referer', $referer);
            $this->database->bind(':country', $country);
            $this->database->bind(':device', $device);
            $this->database->bind(':platform', $platform);
            $this->database->bind(':browser', $browser);
            $this->database->bind(':ip_address', $ip);
            $this->database->bind(':session', $_SESSION['session']);
            $this->database->bind(':created', time());
            $this->database->execute();
        }
    }

    # return campaigns
    protected function campaigns($result = array())
    {
        $this->database->query('SELECT `ID`, `request` FROM `campaigns` WHERE `status` = :status');
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

    # redirect to new location
    protected function redirect($location = '/')
    {
        # redirect
        die(header('location: ' . $location, true, 303));
    }

    # display error
    protected function error($code = 404)
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

    # secure argument limit
    protected function secure($args = null, $limit = null)
    {
        # limit reached
        if ($args > $limit){
            $this->error();
        }
    }
}