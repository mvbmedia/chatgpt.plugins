<?php
class Articles
{
	protected $database;
	protected $functions;

	public function __construct()
	{
		$this->database = new Database;
		$this->functions = new Functions;
	}

	# return top offers
	public function top_offers($limit = 5)
	{
		# set variables
		$position = 1;

		# return subjects
		$this->database->query('SELECT *, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`subject` = `subjects`.`ID`) as `score` FROM `subjects` WHERE `website` = :website AND `status` = :status ORDER BY `rating` DESC, `name` ASC LIMIT 0, :limit');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':status', 'active');
		$this->database->bind(':limit', $limit);
		$result = $this->database->resultset();

		# set features
		foreach($result as $key => $row)
		{
			# set position
			$result[$key]['position'] = $position;

            # set score
            $result[$key]['score'] = ($row['score'] > 0 ? $row['score'] : 60);

			# update position
			$position++;
		}

		return $result;
	}

	# return articles
	public function articles($limit = 5)
	{
	    # set max
        $max = (int) ceil($this->total() / $limit);

        # set offset
        $current = (int) ((isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page']) && $max >= $_GET['page'] && $_GET['page'] > 0) ? ($_GET['page'] - 1) : 0);

        # return articles
        $this->database->query('SELECT * FROM `articles` WHERE `articles`.`website` = :website AND `articles`.`status` = :status ORDER BY `articles`.`ID` DESC LIMIT :current, :limit');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':status', 'active');
        $this->database->bind(':current', ($current * $limit));
        $this->database->bind(':limit', $limit);
		$result = $this->database->resultset();

		return $result;
	}

	# return article
	public function article($slug = null)
	{
		# return subjects
		$this->database->query('SELECT * FROM `articles` WHERE `website` = :website AND `slug` = :slug AND `status` = :status');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':slug', $slug);
		$this->database->bind(':status', 'active');
		$result = $this->database->single();

        # set index
        preg_match_all("/<(h\d*)>(\w[^<]*)/i", $result['content'],$result['index'], PREG_SET_ORDER);

        # return result
        return $result;
	}

	# return Guide existence
	public function exists($slug = null)
	{
		# return subject
		$this->database->query('SELECT COUNT(*) FROM `articles` WHERE `website` = :website AND `slug` = :slug AND `status` = :status');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':slug', $slug);
		$this->database->bind(':status', 'active');
		$this->database->execute();
		$result = $this->database->fetchColumn();

		return $result;
	}

    # return written guide
    public function total()
    {
        # return total
        $this->database->query('SELECT COUNT(*) FROM `articles` WHERE `website` = :website AND `status` = :status');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $this->database->execute();
        $result = $this->database->fetchColumn();

        return $result;
    }

    # return written guide
    public function words()
    {
        # return total
        $this->database->query('SELECT SUM(LENGTH(`content`) - LENGTH(REPLACE(`content`, " ", ""))) FROM `articles` WHERE `website` = :website AND `status` = :status');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $this->database->execute();
        $result = $this->database->fetchColumn();

        return $result;
    }

    # return pages
    public function pages($limit = 5)
    {
        # variables
        $count = $this->total();
        $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $max = ceil($count / $limit);
        $current = (int) ((isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page']) && $max >= $_GET['page'] && $_GET['page'] > 0) ? $_GET['page'] : 1);
        $result = '';

        # create pages
        if (ceil($count / $limit) > 0){
            $result = '<ul class="pagination">';

            if ($current > 1){
                $result .= '<li class="previous"><a href="' . $url . '?page=' .  ($current - 1) . '">Vorige</a></li>';
            }

            if ($current > 3){
                $result .= '<li class="start"><a href="' . $url . '?page=1">1</a></li>';
                $result .= '<li class="dots">...</li>';
            }

            if ($current - 2 > 0){
                $result .= '<li class="page"><a href="' . $url . '?page=' . ($current - 2) . '">' . ($current - 2) . '</a></li>';
            }

            if ($current - 1 > 0){
                $result .= '<li class="page"><a href="' . $url . '?page=' . ($current - 1) . '">' . ($current - 1) . '</a></li>';
            }

            $result .= '<li class="current"><a href="' . $url . '?page=' . $current . '">' . $current . '</a></li>';

            if ($current + 1 < ceil($count / $limit) + 1){
                $result .= '<li class="page"><a href="' . $url . '?page=' . ($current + 1) . '">' . ($current + 1) . '</a></li>';
            }

            if ($current + 2 < ceil($count / $limit) + 1){
                $result .= '<li class="page"><a href="' . $url . '?page=' . ($current + 2) . '">' . ($current + 2) . '</a></li>';
            }

            if ($current < ceil($count / $limit) - 2){
                $result .= '<li class="dots">...</li>';
                $result .= '<li class="end"><a href="' . $url . '?page=' . ceil($count / $limit) . '">' . ceil($count / $limit) . '</a></li>';
            }

            if ($current < ceil($count / $limit)){
                $result .= '<li class="next"><a href="' . $url . '?page=' . ($current + 1) . '">Volgende</a></li>';
            }

            $result .= '</ul>';
        }

        return $result;
    }
}
