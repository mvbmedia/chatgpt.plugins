<?php
class Compare
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
        # set variables
        $position = 1;
        $result = array();

        # set offers
        $offers = (isset($_SESSION['compare']) && !empty($_SESSION['compare']) ? $_SESSION['compare'] : array());

        # set top offers
        if (empty($offers)){
            $this->database->query('SELECT `subjects`.`ID` FROM `subjects` WHERE `subjects`.`website` = :website AND `subjects`.`status` = :status ORDER BY `rating` DESC LIMIT 0,4');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':status', 'active');
            $offers = $this->database->resultset();
        }

        foreach($offers as $key => $row)
        {
            # set id
            $id = (is_array($row) && isset($row['ID']) ? $row['ID'] : $row);

            # return subjects
            $this->database->query('SELECT * FROM `subjects` WHERE `subjects`.`ID` = :id AND `subjects`.`website` = :website AND `subjects`.`status` = :status');
            $this->database->bind(':id', $id);
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':status', 'active');
            $result[$key] = $this->database->single();

            # set ratings
            $result[$key]['ratings'] = $this->ratings($id);

            # set features
            $result[$key]['features'] = $this->features($id);

            # set summaries
            $result[$key]['summaries'] = $this->summaries($id);

            # set registrations
            $result[$key]['registrations'] = (!empty($result[$key]['registrations']) ? ceil((time() % 86400) / (86400 / $result[$key]['registrations'])) : 0);
        }

        return $result;
    }

    # return ratings
    public function ratings($id = null)
    {
        $this->database->query('SELECT `questions`.`ID`, `questions`.`title`, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`subject` = :subject AND `ratings`.`question` = `questions`.`ID` ) as `score` FROM `ratings` LEFT JOIN `questions` ON `ratings`.`question` = `questions`.`ID` WHERE `questions`.`website` = :website AND `questions`.`status` = :status GROUP BY `questions`.`ID`');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':subject', $id);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

    # return features
    public function features($id = null)
    {
        $this->database->query('SELECT `icon`, `title`, (SELECT `content` FROM `characteristics` WHERE `feature` = `features`.`ID` AND `subject` = :subject) as `content` FROM `features` WHERE `features`.`website` = :website AND `status` = :status ORDER BY `position` ASC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':subject', $id);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

    # return summaries
    public function summaries($id = null)
    {
        $this->database->query('SELECT `title`, `description` FROM `summaries` WHERE `subject` = :subject');
        $this->database->bind(':subject', $id);
        $result = $this->database->resultset();

        return $result;
    }
}
