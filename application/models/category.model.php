<?php
class Category
{
	protected $database;
	protected $functions;

	public function __construct()
	{
		$this->database = new Database;
		$this->functions = new Functions;
	}

	# return top offers
	public function top_offers($slug = null)
	{
		$this->database->query('SELECT `subjects`.*, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`subject` = `subjects`.`ID`) as `score` FROM `subjects` LEFT JOIN `crossreference` ON `subjects`.`ID` = `crossreference`.`subject` LEFT JOIN `categories` ON `crossreference`.`category` = `categories`.`ID` WHERE `subjects`.`website` = :website AND `categories`.`slug` = :slug AND (`categories`.`status` = :status AND `subjects`.`status` = :status AND `crossreference`.`status` = :status) ORDER BY `crossreference`.`position` ASC LIMIT 0,5');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':slug', $slug);
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

        # set features
        foreach($result as $key => $row)
        {
            # set variables
            $count = $this->total($row['ID']);

            # set total reviews
            $result[$key]['reviews'] = $count['reviews'];

            # set total ratings
            $result[$key]['ratings'] = $count['ratings'];

            # set score
            $result[$key]['score'] = ($row['score'] > 0 ? $row['score'] : 60);
        }

		return $result;
	}

	# return offers
	public function offers($slug = null)
	{
		# set variables
		$position = 1;

		# return subjects
		$this->database->query('SELECT `subjects`.*, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`subject` = `subjects`.`ID`) as `score` FROM `subjects` LEFT JOIN `crossreference` ON `subjects`.`ID` = `crossreference`.`subject` LEFT JOIN `categories` ON `crossreference`.`category` = `categories`.`ID` WHERE `subjects`.`website` = :website AND `categories`.`slug` = :slug AND (`categories`.`status` = :status AND `subjects`.`status` = :status AND `crossreference`.`status` = :status) ORDER BY `crossreference`.`position`');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':slug', $slug);
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

		# set features
		foreach($result as $key => $row)
		{
            # set variables
            $count = $this->total($row['ID']);

            # set position
			$result[$key]['position'] = $position;

			# set features
			$result[$key]['features'] = $this->features($row['ID']);

            # set total reviews
            $result[$key]['reviews'] = $count['reviews'];

            # set total ratings
            $result[$key]['ratings'] = $count['ratings'];

            # set score
            $result[$key]['score'] = ($row['score'] > 0 ? $row['score'] : 60);

			# update position
			$position++;
		}

		return $result;
	}

	# return features
	public function features($id = null)
	{
		$this->database->query('SELECT `icon`, `title`, (SELECT `content` FROM `characteristics` WHERE `feature` = `features`.`ID` AND `subject` = :subject) as `content` FROM `features` WHERE `features`.`website` = :website AND `card` = :card AND `status` = :status');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':subject', $id);
		$this->database->bind(':card', 'yes');
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

		return $result;
	}

    # return reviews and ratings
    public function total($id = null)
    {
        # set variables
        $result = array();

        # return total reviews
        $this->database->query('SELECT COUNT(*) FROM `reviews` WHERE `subject` = :subject AND (`status` = :active OR (`status` = :inactive AND `session` = :session))');
        $this->database->bind(':subject', $id);
        $this->database->bind(':active', 'active');
        $this->database->bind(':inactive', 'inactive');
        $this->database->bind(':session', $_SESSION['session']);
        $this->database->execute();
        $result['reviews'] = $this->database->fetchColumn();

        # return total ratings
        $this->database->query('SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`subject` = :subject');
        $this->database->bind(':subject', $id);
        $this->database->execute();
        $result['ratings'] = $this->database->fetchColumn();

        return $result;
    }

	# return category
	public function category($slug = null)
	{
		$this->database->query('SELECT `name`, `title`, `description`, `content`, `slug`, `meta_title`, `meta_description`, `meta_keywords`, `theme`, `icon`, `thumbnail`, `created` FROM `categories` WHERE `categories`.`website` = :website AND `categories`.`slug` = :slug AND `categories`.`status` = :status');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':slug', $slug);
		$this->database->bind(':status', 'active');
		$this->database->execute();
		$result = $this->database->single();

		return $result;
	}
}
