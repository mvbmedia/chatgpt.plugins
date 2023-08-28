<?php
class Api
{
    protected $database;
    protected $functions;

    public function __construct()
    {
        $this->database = new Database;
        $this->functions = new Functions;
    }

    # return offers 
    public function offers()
    {
        # set filters
        $categories = json_decode($_POST['categories'], true);
        $filters = json_decode($_POST['features'], true);
        $sort = ($_POST['sort'] ?? 0);

        # set variables
        $subjects = array();
        $result = array();
        $position = 1;

        # set all subjects
        if (empty($categories) && empty($filters)){
            # return subjects
            $this->database->query('SELECT `subjects`.`ID` FROM `subjects` WHERE `website` = :website AND `status` = :status ORDER BY `rating` DESC, `name` ASC');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':status', 'active');
            $results = $this->database->resultset();

            foreach($results as $key => $row)
            {
                $subjects[0]['subjects'][] = $row['ID'];
            }
        }

        # set subjects
        foreach($categories as $key => $row)
        {
            # return subjects
            $this->database->query('SELECT `crossreference`.`subject` FROM `crossreference` LEFT JOIN `categories` ON `crossreference`.`category` = `categories`.`ID` LEFT JOIN `subjects` ON `crossreference`.`subject` = `subjects`.`ID` WHERE `categories`.`ID` = :category AND `categories`.`website` = :website AND `subjects`.`website` = :website AND `categories`.`status` = :status AND `subjects`.`status` = :status AND `crossreference`.`status` = :status');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':category', $row['name']);
            $this->database->bind(':status', 'active');
            $subject = $this->database->resultset();

            # set subjects
            foreach($subject as $data)
            {
                $subjects[0]['subjects'][] = $data['subject'];
            }
        }

        $subjects = array_unique($subjects);

        # set subjects
        foreach($filters as $key => $row)
        {
            # return subjects
            $this->database->query('SELECT `subjects`.`ID` FROM `filter_crossreference` LEFT JOIN `subjects` ON `filter_crossreference`.`subject` = `subjects`.`ID` WHERE `subjects`.`website` = :website AND `filter_crossreference`.`group` = :id AND `subjects`.`status` = :status ORDER BY `subjects`.`rating` DESC');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':id', $row['value']);
            $this->database->bind(':status', 'active');
            $subject = $this->database->resultset();

            # set subjects
            foreach($subject as $key => $data)
            {
                $subjects[$row['name']]['subjects'][] = $data['ID'];
            }
        }

        # remove duplicate subjects
        $subjects = array_column($subjects, 'subjects');
        $subjects = (!empty($subjects) && count($subjects) > 1 ? array_intersect(...$subjects) : array_merge(...$subjects));
        $subjects = array_unique($subjects);

        # return subject data
        foreach($subjects as $key => $subject)
        {
            # return subject data
            $this->database->query('SELECT *, (SELECT (AVG(`ratings`.`rating`) * 10) as `rating` FROM `ratings` WHERE `ratings`.`subject` = `subjects`.`ID`) as `score` FROM `subjects` WHERE `subjects`.`ID` = :subject ORDER BY `rating` DESC, `name` ASC');
            $this->database->bind(':subject', $subject);
            $results = $this->database->single();

            # return features
            $this->database->query('SELECT `icon`, `title`, (SELECT `content` FROM `characteristics` WHERE `feature` = `features`.`ID` AND `subject` = :subject) as `content` FROM `features` WHERE `features`.`website` = :website AND `card` = :card AND `status` = :status');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':subject', $subject);
            $this->database->bind(':card', 'yes');
            $this->database->bind(':status', 'active');
            $features = $this->database->resultset();

            # return total reviews
            $this->database->query('SELECT COUNT(*) FROM `reviews` WHERE `subject` = :subject AND (`status` = :active OR (`status` = :inactive AND `session` = :session))');
            $this->database->bind(':subject', $subject);
            $this->database->bind(':active', 'active');
            $this->database->bind(':inactive', 'inactive');
            $this->database->bind(':session', $_SESSION['session']);
            $this->database->execute();
            $reviews = $this->database->fetchColumn();

            # return total ratings
            $this->database->query('SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`subject` = :subject');
            $this->database->bind(':subject', $subject);
            $this->database->execute();
            $ratings = $this->database->fetchColumn();
                        
            # return total prompts
            $this->database->query('SELECT COUNT(*) FROM `prompts` WHERE `prompts`.`subject` = :subject');
            $this->database->bind(':subject', $subject);
            $this->database->execute();
            $prompts = $this->database->fetchColumn();

            # set subject data
            $result[$key] = $results;

            # set position
            $result[$key]['position'] = $position;

            # set features
            $result[$key]['features'] = $features;

            # set total reviews
            $result[$key]['reviews'] = $reviews;

            # set total ratings
            $result[$key]['ratings'] = $ratings;

            # set total prompts
            $result[$key]['prompts'] = $prompts;

            # update position
            $position++;
        }
        
        # sort by popularity
        if ($sort == 'popular') {
            usort($result, function($a, $b) {
                return $b['rating'] <=> $a['rating'];
            });
            # sort by recommendations
        } elseif ($sort == 'recommendations'){
            usort($result, function($a, $b) {
                return $a['position'] <=> $b['position'];
            });
            # sort by score
        } elseif ($sort == 'score') {
            usort($result, function($a, $b) {
                return $b['rating'] <=> $a['rating'];
            });
            # sort by new
        } elseif ($sort == 'new') {
            usort($result, function($a, $b) {
                return $b['ID'] <=> $a['ID'];
            });
        }

        # start template
        ob_start();

        # extract variables
        extract($result);

        # return template
        require_once(VIEW . 'modals/subjects.php');

        # set template content
        $html = ob_get_contents();

        # end template
        ob_end_clean();

        # display content
        echo $html;
    }

    # create click
    public function click()
    {
        # set default click
        $click = 0;

        # set subject id
        $id = ($_POST['id'] ?? 0);

        # set subject data
        $subject = $this->subject($id);

        # set partner data
        $partner = $this->partner($subject['offer']);

        # set campaign
        $campaign = ($_SESSION['campaign'] ?? null);

        # set token
        $token = ($_SESSION['token'] ?? null);

        # set entry
        $entry = parse_url(($_SERVER['HTTP_REFERER'] ?? '/'), PHP_URL_PATH);

        # set country
        $country = ($_SESSION['geo']['country_code2'] ?? 'NL');

        # set device
        $device = $this->functions->device();

        # set platform
        $platform = $this->functions->platform();

        # set browser
        $browser = $this->functions->browser();

        # set ip address
        $ip = $this->functions->ip();

        # invalid subject
        if (!$subject){
            $this->encode(['message' => 'An error occurred while preparing the query.']);
        }

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
            $this->database->bind(':subject', $id);
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

        # append referer tag
        if (is_array($partner) && !empty($partner['referer'])){
            $separator = (strpos($url, '?') !== false ? '&' : '?');
            $parameter = $separator . $partner['referer'];
            $url .= $parameter . '=' . WEBSITE;
        }

        # return url
        $this->encode(['url' => $url]);
    }

    # return reviews
    public function reviews()
    {
        # set reviews
        $this->database->query('SELECT `reviews`.*, `subjects`.`name` as `offer`, `subjects`.`slug` as `offer_slug`, (SELECT AVG(`ratings`.`rating`) FROM `ratings` WHERE `ratings`.`review` = `reviews`.`ID`) as `rating`, (SELECT COUNT(*) FROM `likes` WHERE `likes`.`review` = `reviews`.`ID`) as `likes`, (SELECT COUNT(*) FROM `likes` WHERE `likes`.`review` = `reviews`.`ID` AND `likes`.`session` = :session) as `liked`, (SELECT COUNT(*) FROM `comments` WHERE `comments`.`review` = `reviews`.`ID` AND `comments`.`status` = :active) as `comments` FROM `reviews` LEFT JOIN `subjects` ON `reviews`.`subject` = `subjects`.`ID` WHERE `reviews`.`subject` = :subject AND (`reviews`.`status` = :active OR (`reviews`.`status` = :inactive AND `reviews`.`session` = :session)) ORDER BY `reviews`.`ID` DESC LIMIT :current, :limit');
        $this->database->bind(':subject', $_POST['id'], PDO::PARAM_INT);
        $this->database->bind(':active', 'active');
        $this->database->bind(':inactive', 'inactive');
        $this->database->bind(':session', $_SESSION['session']);
        $this->database->bind(':current', $_POST['offset'], PDO::PARAM_INT);
        $this->database->bind(':limit', 5);
        $result = $this->database->resultset();

        foreach ($result as $key => $row) {
            # set gender
            $gender = (in_array($row['gender'], ['male', 'female', 'other']) ? $row['gender'] : 'other');

            # set age
            if ($row['age'] <= 19) {
                $age = 'teenager';
            } elseif (in_array($row['age'], range(20, 29))) {
                $age = 'adolescent';
            } elseif (in_array($row['age'], range(30, 39))) {
                $age = 'young-adult';
            } elseif (in_array($row['age'], range(40, 49))) {
                $age = 'adult';
            } else {
                $age = 'senior';
            }

            # set thumbnail
            $result[$key]['thumbnail'] = $gender . '-' . $age . '.svg';

            # set rating
            $result[$key]['rating'] = ($row['rating'] > 0 ? round($row['rating'] / 2) : 3);

            # set strengths pros
            $this->database->query('SELECT * FROM `strengths` WHERE `review` = :id AND `type` = :type');
            $this->database->bind(':id', $row['ID']);
            $this->database->bind(':type', 'pro');
            $result[$key]['strengths']['pros'] = $this->database->resultset();

            # set strengths cons
            $this->database->query('SELECT * FROM `strengths` WHERE `review` = :id AND `type` = :type');
            $this->database->bind(':id', $row['ID']);
            $this->database->bind(':type', 'con');
            $result[$key]['strengths']['cons'] = $this->database->resultset();
        }

        # start template
        ob_start();

        # extract variables
        extract($result);

        # return template
        require_once(VIEW . 'modals/reviews.php');

        # set template content
        $html = ob_get_contents();

        # end template
        ob_end_clean();

        # display content
        echo $html;
    }

    # set rating
    public function rating($data = array())
    {
        # missing fields
        if (!empty(array_diff(array_keys($_POST), ['subject', 'rating']))){
            $this->encode(['message' => 'Wij hebben niet alle gegevens correct ontvangen', 'status' => 'error']);
        }

        # invalid rating
        if (!is_array(json_decode($_POST['rating'], true))){
            $this->encode(['message' => 'Jouw sterrenbeoordeling is niet geldig', 'status' => 'error']);
        }

        # invalid subject
        if (!is_numeric($_POST['subject']) || !$this->subject($_POST['subject'])){
            $this->encode(['message' => 'De gekozen website is niet geldig of bestaat niet meer', 'status' => 'error']);
        }

        try {
            # set subject
            $data['subject'] = $_POST['subject'];

            # set rating
            $data['rating'] = (is_array(json_decode($_POST['rating'], true)) ? json_decode($_POST['rating'], true) : array());

            # begin transaction
            $this->database->beginTransaction();

            # set rating
            foreach($data['rating'] as $key => $row)
            {
                # return question existence
                $this->database->query('SELECT COUNT(*) FROM `questions` WHERE `ID` = :id AND `website` = :website');
                $this->database->bind(':id', $row['id']);
                $this->database->bind(':website', WEBSITE);
                $result = $this->database->execute();

                # invalid question
                if (!$result){
                    $this->encode(['message' => 'Jouw sterrenbeoordeling is niet correct ingevuld', 'status' => 'error']);
                }

                # invalid rating
                if (!is_numeric($row['rating']) || !in_array($row['rating'], range(1, 5))){
                    $this->encode(['message' => 'Jouw sterrenbeoordeling is niet correct ingevuld', 'status' => 'error']);
                }

                # return rating existence
                $this->database->query('SELECT COUNT(*) FROM `ratings` WHERE `subject` = :subject AND `review` = :review AND `question` = :question AND `session` = :session');
                $this->database->bind(':subject', $data['subject']);
                $this->database->bind(':review', 0);
                $this->database->bind(':question', $row['id']);
                $this->database->bind(':session', $_SESSION['session']);
                $this->database->execute();
                $result = $this->database->fetchColumn();

                # update rating
                if ($result){
                    $this->database->query('UPDATE `ratings` SET `review` = :review, `rating` = :rating WHERE `subject` = :subject AND `question` = :question AND `session` = :session');
                    # insert rating
                } else {
                    $this->database->query('INSERT INTO `ratings` (`subject`, `review`, `question`, `rating`, `session`) VALUES (:subject, :review, :question, :rating, :session)');
                }

                # set rating values
                $this->database->bind(':subject', $data['subject']);
                $this->database->bind(':review', 0);
                $this->database->bind(':question', $row['id']);
                $this->database->bind(':rating', round($row['rating'] * 2));
                $this->database->bind(':session', $_SESSION['session']);
                $this->database->execute();
            }

            # end transaction
            $this->database->endTransaction();

            # return message
            $this->encode(['message' => 'Bedankt voor het achterlaten van jouw beoordeling!', 'status' => 'success']);
         } catch (Exception $error) {
            $this->encode(['message' => $error->getMessage(), 'status' => 'error']);
        }
    }

    # post review
    public function review($data = array())
    {
        # missing fields
        if (!empty(array_diff(array_keys($_POST), ['subject', 'rating', 'recommend', 'recommendation', 'title', 'description', 'pros', 'cons', 'gender', 'name', 'email', 'age', 'comments', 'newsletter']))){
            $this->encode(['message' => 'Wij hebben niet alle gegevens correct ontvangen', 'status' => 'error']);
        }

        # invalid subject
        if (!is_numeric($_POST['subject']) || !$this->subject($_POST['subject'])){
            $this->encode(['message' => 'Het gekozen offer is niet geldig of bestaat niet meer', 'status' => 'error']);
        }

        # invalid rating
        if (!is_array(json_decode($_POST['rating'], true))){
            $this->encode(['message' => 'Jouw sterrenbeoordeling is niet geldig', 'status' => 'error']);
        }

        # invalid experience
        if (empty($_POST['title']) || empty($_POST['description'])){
            $this->encode(['message' => 'Je hebt geen beoordeling geschreven', 'status' => 'error']);
        }

        # invalid experience
        if (!preg_match('~[a-zA-Z0-9]+~', $_POST['title'])) {
            $this->encode(['message' => 'Jouw beoordeling bevat geen geldige karakters', 'status' => 'error']);
        }

        # invalid experience
        if (strlen($_POST['title']) < 5 || strlen($_POST['description']) < 5){
            $this->encode(['message' => 'Jouw beoordeling bevat te weinig tekens', 'status' => 'error']);
        }

        # invalid experience
        if (strlen($_POST['title']) > 255 || strlen($_POST['description']) > 1500){
            $this->encode(['message' => 'Jouw beoordeling bevat teveel tekens', 'status' => 'error']);
        }

        # invalid recommend
        if (empty($_POST['recommend']) || !in_array($_POST['recommend'], ['yes', 'no'])){
            $this->encode(['message' => 'De aanbeveling dat jij hebt gekozen is niet geldig of bestaat niet', 'status' => 'error']);
        }

        # invalid recommendation
        if (!empty($_POST['recommendation']) && (!is_numeric($_POST['recommendation']) || !$this->subject($_POST['recommendation']))){
            $this->encode(['message' => 'De website die jij hebt aangeraden is niet geldig of bestaat niet meer', 'status' => 'error']);
        }

        # invalid gender
        if (empty($_POST['gender']) || !in_array($_POST['gender'], ['male', 'female', 'other'])){
            $this->encode(['message' => 'Het geslacht dat jij hebt gekozen is niet geldig of bestaat niet', 'status' => 'error']);
        }

        # invalid name
        if (empty($_POST['name'])){
            $this->encode(['message' => 'Je hebt geen naam ingevuld', 'status' => 'error']);
        }

        # invalid email
        if (empty($_POST['email']) || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === FALSE){
            $this->encode(['message' => 'Het e-mailadres dat jij hebt ingevuld is niet geldig', 'status' => 'error']);
        }

        # invalid domain
        if (checkdnsrr(substr($_POST['email'], strpos($_POST['email'], '@') + 1)) === FALSE){
            $this->encode(['message' => 'Het e-mailadres dat jij hebt ingevuld is niet geldig', 'status' => 'error']);
        }

        # invalid age
        if (!is_numeric($_POST['age']) || !in_array($_POST['age'], range(18, 150))){
            $this->encode(['message' => 'Je moet minimaal 18 jaar zijn om een beoordeling achter te laten', 'status' => 'error']);
        }

        # invalid newsletter
        if (!empty($_POST['newsletter']) && !in_array($_POST['newsletter'], ['yes', 'no'])){
            $this->encode(['message' => 'De bevestiging voor de nieuwsbrief is niet geldig', 'status' => 'error']);
        }

        # invalid exceeded time limit
        if ($this->created('review', $_POST['subject'], $_POST['email']) && $this->created('review', $_POST['subject'], $_POST['email']) >= (time() - 604800)){
            $this->encode(['message' => 'Je hebt deze week al een beoordeling achter gelaten voor deze website', 'status' => 'error']);
        }

        try {
            # subject
            $data['subject'] = $_POST['subject'];

            # slug
            $data['slug'] = trim($_POST['title']);
            $data['slug'] = preg_replace('/\s+/', ' ', $data['slug']);
            $data['slug'] = preg_replace('/[^ \w]+/', '', $data['slug']);
            $data['slug'] = str_replace(' ', '-', $data['slug']);
            $data['slug'] = strtolower($data['slug']);

            # rating
            $data['rating'] = (is_array(json_decode($_POST['rating'], true)) ? json_decode($_POST['rating'], true) : array());

            # title
            $data['title'] = trim(preg_replace('/\s+/', ' ', $_POST['title']));

            # description
            $data['description'] = $_POST['description'];

            # name
            $data['name'] = trim(preg_replace('/\s+/', ' ', $_POST['name']));

            # email
            $data['email'] = strtolower(trim($_POST['email']));

            # gender
            $data['gender'] = strtolower($_POST['gender']);

            # age
            $data['age'] = $_POST['age'];

            # recommend
            $data['recommend'] = strtolower($_POST['recommend']);

            # recommendation
            $data['recommendation'] = (!empty($_POST['recommendation']) ? $_POST['recommendation'] : null);

            # pros
            $data['pros'] = (is_array(json_decode($_POST['pros'], true)) ? json_decode($_POST['pros'], true) : array());

            # cons
            $data['cons'] = (is_array(json_decode($_POST['cons'], true)) ? json_decode($_POST['cons'], true) : array());

            # newsletter
            $data['newsletter'] = $_POST['newsletter'];

            # begin transaction
            $this->database->beginTransaction();

            # set review
            $this->database->query('INSERT INTO `reviews` (`subject`, `title`, `description`, `name`, `email`, `gender`, `age`, `recommend`, `recommendation`, `session`, `created`, `updated`, `status`) VALUES (:subject, :title, :description, :name, :email, :gender, :age, :recommend, :recommendation, :session, :created, :updated, :status)');
            $this->database->bind(':subject', $data['subject']);
            $this->database->bind(':title', $data['title']);
            $this->database->bind(':description', $data['description']);
            $this->database->bind(':name', $data['name']);
            $this->database->bind(':email', $data['email']);
            $this->database->bind(':gender', $data['gender']);
            $this->database->bind(':age', $data['age']);
            $this->database->bind(':recommend', $data['recommend']);
            $this->database->bind(':recommendation', $data['recommendation']);
            $this->database->bind(':session', $_SESSION['session']);
            $this->database->bind(':created', time());
            $this->database->bind(':updated', time());
            $this->database->bind(':status', 'inactive');
            $this->database->execute();

            # set review id
            $id = $this->database->lastInsertId();

            # return slug existence
            $this->database->query('SELECT COUNT(*) FROM `reviews` WHERE `slug` = :slug');
            $this->database->bind(':slug', $data['slug']);
            $this->database->execute();
            $result = $this->database->fetchColumn();

            # set slug
            $data['slug'] = ($result ? $data['slug'] . '-' . $id : $data['slug']);

            # update slug
            $this->database->query('UPDATE `reviews` SET `slug` = :slug WHERE `reviews`.`ID` = :id');
            $this->database->bind(':id', $id);
            $this->database->bind(':slug', $data['slug']);
            $this->database->execute();

            # set rating
            foreach($data['rating'] as $key => $value)
            {
                # return question existence
                $this->database->query('SELECT COUNT(*) FROM `questions` WHERE `ID` = :id AND `website` = :website');
                $this->database->bind(':id', $value['id']);
                $this->database->bind(':website', WEBSITE);
                $result = $this->database->execute();

                # invalid question
                if (!$result){
                    $this->encode(['message' => 'Jouw sterrenbeoordeling is niet correct ingevuld', 'status' => 'error']);
                }

                # invalid rating
                if (!is_numeric($value['rating']) || !in_array($value['rating'], range(1, 5))){
                    $this->encode(['message' => 'Jouw sterrenbeoordeling is niet correct ingevuld', 'status' => 'error']);
                }

                # return rating existence
                $this->database->query('SELECT COUNT(*) FROM `ratings` WHERE `review` = :review AND `question` = :question AND `session` = :session');
                $this->database->bind(':review', 0);
                $this->database->bind(':question', $value['id']);
                $this->database->bind(':session', $_SESSION['session']);
                $this->database->execute();
                $result = $this->database->fetchColumn();

                # update rating
                if ($result){
                    $this->database->query('UPDATE `ratings` SET `review` = :review, `rating` = :rating WHERE `subject` = :subject AND `question` = :question AND `session` = :session');
                    # insert rating
                } else {
                    $this->database->query('INSERT INTO `ratings` (`subject`, `review`, `question`, `rating`, `session`) VALUES (:subject, :review, :question, :rating, :session)');
                }

                # set rating
                $this->database->bind(':subject', $data['subject']);
                $this->database->bind(':review', $id);
                $this->database->bind(':question', $value['id']);
                $this->database->bind(':rating', round($value['rating'] * 2));
                $this->database->bind(':session', $_SESSION['session']);
                $this->database->execute();
            }

            # set pros
            foreach($data['pros'] as $key => $value)
            {
                # invalid pros
                if ($key >= 3 || strlen($value) > 255){
                    $this->encode(['message' => 'Jouw voordelen zijn niet geldig', 'status' => 'error']);
                }

                # insert pros
                if (!empty($value)){
                    $this->database->query('INSERT INTO `strengths` (`review`, `title`, `type`) VALUES (:review, :title, :type)');
                    $this->database->bind(':review', $id);
                    $this->database->bind(':title', $value);
                    $this->database->bind(':type', 'pro');
                    $this->database->execute();
                }
            }

            # set cons
            foreach($data['cons'] as $key => $value)
            {
                # invalid cons
                if ($key >= 3 || strlen($value) > 255){
                    $this->encode(['message' => 'Jouw nadelen zijn niet geldig', 'status' => 'error']);
                }

                # insert cons
                if (!empty($value)){
                    $this->database->query('INSERT INTO `strengths` (`review`, `title`, `type`) VALUES (:review, :title, :type)');
                    $this->database->bind(':review', $id);
                    $this->database->bind(':title', $value);
                    $this->database->bind(':type', 'con');
                    $this->database->execute();
                }
            }

            # set newsletter
            if ($data['newsletter'] == 'yes'){
                $this->database->query('SELECT COUNT(*) FROM `mailing` WHERE `email` = :email');
                $this->database->bind(':email', $data['email']);
                $this->database->execute();
                $result = $this->database->fetchColumn();

                if (!$result){
                    $this->database->query('INSERT INTO `mailing` (`email`, `created`, `status`) VALUES (:email, :created, :status)');
                    $this->database->bind(':email', $data['email']);
                    $this->database->bind(':created', time());
                    $this->database->bind(':status', 'active');
                    $this->database->execute();
                }
            }

            # end transaction
            $this->database->endTransaction();

            # return message
            $this->encode(['message' => 'Bedankt voor het achterlaten van jouw beoordeling!', 'status' => 'success']);
        } catch (Exception $error) {
            # cancel transaction
            $this->database->cancelTransaction();

            # return error
            $this->encode(['message' => $error->getMessage(), 'status' => 'error']);
        }
    }

    # post comment
    public function comment($data = array())
    {
        # missing fields
        if (!empty(array_diff(array_keys($_POST), ['id', 'name', 'email', 'comment']))){
            $this->encode(['message' => 'Wij hebben niet alle gegevens correct ontvangen', 'status' => 'error']);
        }

        # invalid review
        if (!is_numeric($_POST['id'])){
            $this->encode(['message' => 'De gekozen beoordeling is niet geldig of bestaat niet meer', 'status' => 'error']);
        }

        # invalid comment
        if (empty($_POST['comment'])){
            $this->encode(['message' => 'Je hebt geen reactie geschreven', 'status' => 'error']);
        }

        # invalid experience
        if (strlen($_POST['comment']) > 1500){
            $this->encode(['message' => 'Jouw reactie bevat teveel tekens', 'status' => 'error']);
        }

        # invalid name
        if (empty($_POST['name'])){
            $this->encode(['message' => 'Je hebt geen naam ingevuld', 'status' => 'error']);
        }

        # invalid email
        if (empty($_POST['email']) || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === FALSE){
            $this->encode(['message' => 'Het e-mailadres dat jij hebt ingevuld is niet geldig', 'status' => 'error']);
        }

        # invalid domain
        if (checkdnsrr(substr($_POST['email'], strpos($_POST['email'], '@') + 1)) === FALSE){
            $this->encode(['message' => 'Het e-mailadres dat jij hebt ingevuld is niet geldig', 'status' => 'error']);
        }

        # invalid exceeded time limit
        if ($this->created('comment', $_POST['id'], $_POST['email']) && $this->created('comment', $_POST['id'], $_POST['email']) >= (time() - 604800)){
            $this->encode(['message' => 'Je hebt deze week al een beoordeling achter gelaten voor deze website', 'status' => 'error']);
        }

        try {
            # id
            $data['id'] = filter_var($_POST['id'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

            # name
            $data['name'] = trim(preg_replace('/\s+/', ' ', $_POST['name']));

            # email
            $data['email'] = strtolower(trim($_POST['email']));

            # description
            $data['comment'] = $_POST['comment'];

            # date
            $data['date'] = $this->functions->timestamp(time());

            # begin transaction
            $this->database->beginTransaction();

            # set comment
            $this->database->query('INSERT INTO `comments` (`review`, `comment`, `name`, `email`, `session`, `created`, `status`) VALUES (:review, :comment, :name, :email, :session, :created, :status)');
            $this->database->bind(':review', $data['id']);
            $this->database->bind(':comment', $data['comment']);
            $this->database->bind(':name', $data['name']);
            $this->database->bind(':email', $data['email']);
            $this->database->bind(':session', $_SESSION['session']);
            $this->database->bind(':created', time());
            $this->database->bind(':status', 'inactive');
            $execute = $this->database->execute();

            # end transaction
            $this->database->endTransaction();

            # return message
            if ($execute){
                $this->encode(['message' => 'Thank you for leaving your comment!', 'status' => 'success', 'result' => $data]);
            } else {
                $this->encode(['message' => 'An error occurred while adding the comment', 'status' => 'error']);
            }
        } catch (Exception $error) {
            # cancel transaction
            $this->database->cancelTransaction();

            # return error
            $this->encode(['message' => 'An error occurred while adding the comment', 'status' => 'error']);
        }
    }

    # compare
    public function compare(): void
    {
        # missing fields
        if (array_diff_key(['subject' => ''], $_POST)){
            $this->encode(['message' => 'We did not receive all the data correctly', 'status' => 'error']);
        }

        $subject = $_POST['subject'];
        
        # invalid subject
        if (!is_numeric($subject) || !$this->subject($subject)){
            $this->encode(['message' => 'The chosen offer is invalid or no longer exists', 'status' => 'error']);
        }

        # maximum exceeded
        if (isset($_SESSION['compare']) && is_array($_SESSION['compare']) && !in_array($subject, $_SESSION['compare']) && count($_SESSION['compare']) >= 4){
            $this->encode(['message' => 'A maximum of 4 offers can be in the comparator', 'status' => 'error']);
        }

        try {
            $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
            $result = [];

            $_SESSION['compare'] ??= [];

            if (!in_array($subject, $_SESSION['compare'])){
                $_SESSION['compare'][] = $subject;
            } else {
                if (($key = array_search($subject, $_SESSION['compare'])) !== false){
                    unset($_SESSION['compare'][$key]);
                }
            }

            foreach($_SESSION['compare'] as $key)
            {
                $this->database->query('SELECT `ID`, `name`, `symbol` FROM `subjects` WHERE `subjects`.`ID` = :id');
                $this->database->bind(':id', $key);
                $result[] = $this->database->single();
            }

            ob_start();

            extract($result, EXTR_SKIP);

            require_once(VIEW . 'modals/compare.php');
            $html = ob_get_clean();

            $message = in_array($subject, $_SESSION['compare']) 
                ? 'The selected plugin has been added to the comparator'
                : 'The selected plugin has been removed from the comparator';

            $this->encode(['message' => $message, 'html' => $html, 'status' => 'success']);

        } catch (\Exception $e) {
            $this->database->cancelTransaction();
            $this->encode(['message' => 'An error occurred while editing the comparator', 'status' => 'error']);
        }
    }

    # autocomplete
    /**
     * Summary of autocomplete
     * @return void
     */
    public function autocomplete(): void
    {
        if (array_diff_key(['term' => ''], $_POST)) {
            $this->encode(['message' => 'Error: missing fields', 'status' => 'error']);
        }

        $term = $_POST['term'] ?? '';

        if (empty($term)) {
            return;
        }

        $term = htmlspecialchars($term, ENT_QUOTES, 'UTF-8');

        $this->database->query(
            'SELECT `name`, `slug`, `symbol` FROM `subjects` WHERE `subjects`.`website` = :website AND `subjects`.`name` LIKE :term ORDER BY `rating` DESC, `name` ASC LIMIT 6'
        );
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':term', '%' . $term . '%');
        $result = $this->database->resultset();

        $this->encode($result);
    }

    # offer viewed 
    public function views(): void
    {
        $id = $_POST['id'] ?? null;
    
        if (array_diff_key(['id' => ''], $_POST) || !is_numeric($id) || !$this->subject($id)) {
            $this->encode(['message' => 'Error: invalid input', 'status' => 'error']);
        }
    
        $id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
        $ip = $this->functions->ip();
    
        try {
            $this->database->query(
                'INSERT INTO `views` (`subject`, `ip_address`, `session`, `created`) VALUES (:subject, :ip_address, :session, :created)'
            );
            $this->database->bind(':subject', $id);
            $this->database->bind(':ip_address', $ip);
            $this->database->bind(':session', $_SESSION['session']);
            $this->database->bind(':created', time());
            $this->database->execute();
        } catch (\Exception $e) {
            $this->encode(['message' => 'Error: database error', 'status' => 'error']);
        }
    }
    
    # like review
    public function like(?int $id = null): void
    {
        $id = $id ?? ($_POST['id'] ?? null);
    
        if (!is_numeric($id)) {
            $this->encode(['message' => 'Error: invalid input', 'status' => 'error']);
        }
    
        $id = htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8');
    
        try {
            $this->database->query('SELECT COUNT(*) FROM `likes` WHERE `review` = :review AND `session` = :session');
            $this->database->bind(':review', $id);
            $this->database->bind(':session', $_SESSION['session']);
            $count = $this->database->fetchColumn();
    
            if (!$count) {
                $this->database->query('INSERT INTO `likes` (`review`, `session`, `created`) VALUES (:review, :session, :created)');
                $this->database->bind(':review', $id);
                $this->database->bind(':session', $_SESSION['session']);
                $this->database->bind(':created', time());
                $this->database->execute();
            } else {
                $this->database->query('DELETE FROM `likes` WHERE `review` = :review AND `session` = :session');
                $this->database->bind(':review', $id);
                $this->database->bind(':session', $_SESSION['session']);
                $this->database->execute();
            }
        } catch (\Exception $e) {
            $this->encode(['message' => 'Error: database error', 'status' => 'error']);
        }
    }

    # subject
    public function subject(?int $id = null): ?array
    {
        $this->database->query('SELECT * FROM `subjects` WHERE `subjects`.`ID` = :id AND `subjects`.`website` = :website');
        $this->database->bind(':id', $id);
        $this->database->bind(':website', WEBSITE);
        return $this->database->single();
    }

    # partner
    public function partner(?int $id = null): ?array
    {
        $this->database->query(
            'SELECT `partners`.* FROM `partners`
             LEFT JOIN `partners_crossreference` ON `partners`.`ID` = `partners_crossreference`.`partner`
             LEFT JOIN `offers` ON `partners_crossreference`.`offer` = `offers`.`ID`
             WHERE `partners_crossreference`.`offer` = :id LIMIT 1'
        );
        $this->database->bind(':id', $id);
        return $this->database->single();
    }

# subject url
public function url(?int $id = null, ?string $country = null): ?array
{
    $this->database->query('SELECT * FROM `links` WHERE `links`.`offer` = :id AND `links`.`country` = :country');
    $this->database->bind(':id', $id);
    $this->database->bind(':country', $country);

    return $this->database->single();
}

# created review
public function created(?string $method = null, ?int $id = null, ?string $email = null): ?int
{
    if ($method === 'review') {
        $this->database->query(
            'SELECT `created` FROM `reviews` WHERE `subject` = :subject AND (`email` = :email OR `session` = :session)'
        );
        $this->database->bind(':subject', $id);
        $this->database->bind(':email', $email);
        $this->database->bind(':session', $_SESSION['session']);
    } elseif ($method === 'comment') {
        $this->database->query(
            'SELECT `created` FROM `comments` WHERE `review` = :review AND (`email` = :email OR `session` = :session)'
        );
        $this->database->bind(':review', $id);
        $this->database->bind(':email', $email);
        $this->database->bind(':session', $_SESSION['session']);
    } else {
        return null;
    }
    $this->database->execute();
    return $this->database->fetchColumn();
}
    # secure request
    public function secure(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            && !empty($_SERVER['HTTP_REFERER'])
            && str_contains((string) parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST), (string) DOMAIN)
            && $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    # encode data
    public function encode(array $data = []): void
    {
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }

    public function saveReaction($pageId, $pageType, $reaction)
    {
        $query = "INSERT INTO reactions (page_id, page_type, reaction) VALUES (?, ?, ?)";
        $stmt = $this->database->prepare($query);
        $stmt->execute([$pageId, $pageType, $reaction]);
    }
}