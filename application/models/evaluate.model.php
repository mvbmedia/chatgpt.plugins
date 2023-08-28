<?php
class Evaluate
{
    protected $database;
    protected $functions;

    public function __construct()
    {
        $this->database = new Database;
        $this->functions = new Functions;
    }

    # return subject
    public function subject($slug = null)
    {
        # return subjects
        $this->database->query('SELECT `subjects`.*, `offers`.`host`, `offers`.`domain` FROM `subjects` LEFT JOIN `offers` ON `subjects`.`offer` = `offers`.`ID` WHERE `subjects`.`website` = :website AND `subjects`.`slug` = :slug AND `subjects`.`status` = :status');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':slug', $slug);
        $this->database->bind(':status', 'active');
        $result = $this->database->single();

        return $result;
    }

    # return subjects
    public function subjects()
    {
        $this->database->query('SELECT `ID`, `name` FROM `subjects` WHERE `website` = :website AND `status` = :status ORDER BY `rating` DESC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
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
    
    # return questions
    public function questions()
    {
        $this->database->query('SELECT * FROM `questions` WHERE `website` = :website AND `status` = :status');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }
}
