<?php
class Offer
{
    protected $database;
    protected $functions;

    public function __construct()
    {
        $this->database = new Database;
        $this->functions = new Functions;
    }

    # set click
    public function click($slug = null)
    {
        # set default click
        $click = 0;

        # set subject data
        $subject = $this->subject($slug);

        # invalid subject
        if (!$subject){
            $this->encode(['message' => 'An error occurred while preparing the query.']);
        }

        # set partner data
        $partner = $this->partner($subject['offer']);

        # invalid partner
        if (!$partner){
            $this->encode(['message' => 'An error occurred while preparing the query.']);
        }

        # set campaign
        $campaign = ($_SESSION['campaign'] ?? null);

        # set token
        $token = ($_SESSION['token'] ?? null);

        # set entry
        $entry = parse_url(($_SERVER['HTTP_REFERER'] ?? '/'), PHP_URL_PATH);

        # set country
        $country = ($_SESSION['geo']['country_code2'] ?? '');

        # set device
        $device = $this->functions->device();

        # set platform
        $platform = $this->functions->platform();

        # set browser
        $browser = $this->functions->browser();

        # set ip address
        $ip = $this->functions->ip();

        # set link
        if (!$url = $this->url($subject['offer'], $country)){
            # set default link
            if (isset($subject['country'])){
                $url = $this->url($subject['offer'], $subject['country']);
            }
        }

        # invalid url
        if (!isset($url) || empty($url)){
            $this->encode(['message' => 'An error occurred while sending the request.']);
        }

        # set click data
        if (!$this->functions->bot()){
            $this->database->query('INSERT INTO `clicks` (`subject`, `campaign`, `token`, `entry`, `country`, `device`, `platform`, `browser`, `ip_address`, `session`, `created`) VALUES (:subject, :campaign, :token, :entry, :country, :device, :platform, :browser, :ip_address, :session, :created)');
            $this->database->bind(':subject', $subject['ID']);
            $this->database->bind(':campaign', $campaign);
            $this->database->bind(':token', $token);
            $this->database->bind(':entry', $entry);
            $this->database->bind(':country', $country);
            $this->database->bind(':device', $device);
            $this->database->bind(':platform', $platform);
            $this->database->bind(':browser', $browser);
            $this->database->bind(':ip_address', $ip);
            $this->database->bind(':session', $_SESSION['session']);
            $this->database->bind(':created', time());
            $this->database->execute();

            # set click id
            $click = $this->database->lastInsertId();
        }

        # set desktop url
        if ($device == 'desktop'){
            $url = (!empty($url['desktop']) ? $url['desktop'] : $url['mobile']);
            # set mobile url
        } elseif ($device == 'mobile') {
            $url = (!empty($url['mobile']) ? $url['mobile'] : $url['desktop']);
            # set default url
        } else {
            $url = (!empty($url['desktop']) ? $url['desktop'] : $url['mobile']);
        }

        # append clicktag
        $separator = (strpos($url, '?') !== false ? '&' : '?');
        $parameter = $separator . (isset($partner) && is_array($partner) && !empty($partner['parameter']) ? $partner['parameter'] : 'click');
        $url .= $parameter . '=' . $click;

        # append referrer tag
        $separator = (strpos($url, '?') !== false ? '&' : '?');
        $parameter = $separator . (isset($partner) && is_array($partner) && !empty($partner['referer']) ? $partner['referer'] : 'ref');
        $url .= $parameter . '=' . WEBSITE;

        # return url
        header('location: ' . $url, true, 303);
    }

    # return subject existence
    public function exists($slug = null)
    {
        # return subject
        $this->database->query('SELECT COUNT(*) FROM `subjects` WHERE `website` = :website AND `slug` = :slug AND `status` = :status');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':slug', $slug);
        $this->database->bind(':status', 'active');
        $this->database->execute();
        $result = $this->database->fetchColumn();

        return $result;
    }

    # subject existence
    public function subject($slug = null)
    {
        $this->database->query('SELECT * FROM `subjects` WHERE `subjects`.`website` = :website AND `subjects`.`slug` = :slug');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':slug', $slug);
        $result = $this->database->single();

        return $result;
    }

    # partner data
    public function partner($id = null)
    {
        $this->database->query('SELECT `partners`.* FROM `partners` LEFT JOIN `partners_crossreference` ON `partners`.`ID` = `partners_crossreference`.`partner` LEFT JOIN `offers` ON `partners_crossreference`.`offer` = `offers`.`ID` WHERE `partners_crossreference`.`offer` = :id LIMIT 0,1');
        $this->database->bind(':id', $id);
        $result = $this->database->single();

        return $result;
    }

    # subject url
    public function url(
        int $id = null,
        string $country = null,
        string $default = '0'
    ) {
        $this->database->query('SELECT * FROM `links` WHERE `links`.`offer` = :id AND `links`.`country` = :country AND `links`.`default` = :default');
        $this->database->bind(':id', $id);
        $this->database->bind(':country', $country);
        $this->database->bind(':default', $default);

        return $this->database->single();
    }

    # encode data
    public function encode($data = array())
    {
        # invalid data
        if (!is_array($data)){
            exit;
        }

        # encode data
        $result = json_encode($data);

        # display data
        echo $result;

        # exit
        exit;
    }
}