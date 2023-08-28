<?php
class Index
{
	protected $database;
	protected $functions;

	public function __construct()
	{
		$this->database = new Database;
		$this->functions = new Functions;
	}

	# return top offers
	public function top_offers()
	{
        # set variables
        $position = 1;

        # return top offers
        $this->database->query('SELECT *, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`subject` = `subjects`.`ID`) as `score` FROM `subjects` WHERE `subjects`.`website` = :website AND `status` = :status ORDER BY `rating` DESC, `name` ASC LIMIT 0,6');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

        # set data
        foreach ($result as $key => $row) {
            # set variables
            $count = $this->total($row['ID']);

            # set position
            $result[$key]['position'] = $position;

            # set total ratings
            $result[$key]['ratings'] = $count['ratings'];

            # update position
            $position++;
        }

        return $result;
	}

    # return offers
    public function offers($limit = 5)
    {
        # set variables
        $position = 1;

        # return subjects
        $this->database->query('SELECT * FROM `subjects` WHERE `website` = :website AND `status` = :status ORDER BY `rating` DESC, `name` ASC LIMIT 0, :limit');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $this->database->bind(':limit', $limit);
        $result = $this->database->resultset();

        # set result
        foreach ($result as $key => $row) {
            # return total reviews
            $this->database->query('SELECT COUNT(*) FROM `reviews` WHERE `subject` = :subject AND (`status` = :active OR (`status` = :inactive AND `session` = :session))');
            $this->database->bind(':subject', $row['ID']);
            $this->database->bind(':active', 'active');
            $this->database->bind(':inactive', 'inactive');
            $this->database->bind(':session', $_SESSION['session']);
            $this->database->execute();
            $result['reviews'] = $this->database->fetchColumn();

            # return total ratings
            $this->database->query('SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`subject` = :subject');
            $this->database->bind(':subject', $row['ID']);
            $this->database->execute();
            $result['ratings'] = $this->database->fetchColumn();

            # set position
            $result[$key]['position'] = $position;

            # set features
            $result[$key]['features'] = $this->features($row['ID']);

            # update position
            $position++;
        }

        return $result;
    }

    public function website()
    {
        $this->database->query('SELECT * FROM `websites` WHERE `ID` = :id');
        $this->database->bind(':id', WEBSITE); // Replace WEBSITE_ID with the actual ID or a variable holding the ID
        $result = $this->database->single();
        return $result;
    }

    # return categories
    public function categories()
    {
        $this->database->query('SELECT `ID`, `name`, `icon`, `theme`, `slug` FROM `categories` WHERE `website` = :website AND `status` = :status ORDER BY `name` ASC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

	# return reviews
	public function reviews()
	{
	    # return reviews
        $this->database->query('SELECT `subjects`.`name` as `website`, `subjects`.`slug`, `subjects`.`ID`, `subjects`.`symbol`, `reviews`.`r_slug`, `reviews`.`gender`, `reviews`.`age`, `reviews`.`title`, `reviews`.`description`, `reviews`.`name`, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`review` = `reviews`.`ID`) as `score` FROM `reviews` LEFT JOIN `subjects` ON `reviews`.`subject` = `subjects`.`ID` WHERE `subjects`.`website` = :website AND `subjects`.`status` = :status AND `reviews`.`status` = :status ORDER BY `reviews`.`ID` DESC LIMIT 0, 30');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

		foreach($result as $key => $row)
		{
			# set gender
			$gender = (in_array($row['gender'], ['male', 'female', 'other']) ? $row['gender'] : 'other');

			# set age
			if ($row['age'] <= 19){
				$age = 'teenager';
			} elseif (in_array($row['age'], range(20, 29))){
				$age = 'adolescent';
			} elseif (in_array($row['age'], range(30, 39))){
				$age = 'young-adult';
			} elseif (in_array($row['age'], range(40, 49))){
				$age = 'adult';
			} elseif ($row['age'] >= 50){
				$age = 'senior';
			}

			# set thumbnail
			$result[$key]['thumbnail'] = $gender . '-' . $age . '.svg';

			# set score
            $result[$key]['score'] = ($row['score'] > 0 ? $row['score'] : 60);
		}

		return $result;
	}

	# return articles
	public function articles()
	{
		$this->database->query('SELECT * FROM `articles` WHERE `articles`.`website` = :website AND `articles`.`status` = :status ORDER BY `articles`.`ID` DESC LIMIT 0,4');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':status', 'active');
		$result = $this->database->resultset();

		return $result;
	}

	# return page
	public function page($slug = null)
	{
		$this->database->query('SELECT * FROM `pages` WHERE `pages`.`website` = :website AND `pages`.`slug` = :slug AND `pages`.`status` = :status');
		$this->database->bind(':website', WEBSITE);
		$this->database->bind(':slug', $slug);
		$this->database->bind(':status', 'active');
		$result = $this->database->single();

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
        $this->database->bind(':session', $_SESSION['session'] ?? null);
        $this->database->execute();
        $result['reviews'] = $this->database->fetchColumn();

        # return total ratings
        $this->database->query('SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`subject` = :subject');
        $this->database->bind(':subject', $id);
        $this->database->execute();
        $result['ratings'] = $this->database->fetchColumn();

        return $result;
    }

    # return features
    public function features($id = null)
    {
        $this->database->query('SELECT `icon`, `title`, (SELECT `content` FROM `characteristics` WHERE `feature` = `features`.`ID` AND `subject` = :subject) as `content` FROM `features` WHERE `features`.`website` = :website AND `card` = :card AND `status` = :status ORDER BY `position` ASC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':subject', $id);
        $this->database->bind(':card', 'yes');
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

	# return impressions
    public function impressions()
    {
        $this->database->query('SELECT `title`, `type` FROM `impressions` WHERE `website` = :website ORDER BY `title` ASC');
        $this->database->bind(':website', WEBSITE);
        $result = $this->database->resultset();

        return $result;
    }

    # return code
    public function code($code = 404)
    {
        $result = array();
        $result[400] = 'Bad Request';
        $result[401] = 'Unauthorized';
        $result[402] = 'Payment Required';
        $result[403] = 'Forbidden';
        $result[404] = 'Not Found';
        $result[500] = 'Internal Server Error';
        $result[502] = 'Bad Gateway';
        $result[503] = 'Service Unavailable';
        $result[504] = 'Gateway Timeout';

        return $result[$code];
    }

    function generateTableOfContents($htmlContent) {
        // Use DOMDocument to parse the HTML content
        $doc = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress any errors (e.g. invalid HTML)
        $doc->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);

        // Query for h2, h3, h4 elements
        $headers = $xpath->query('//h2|//h3|//h4');

        // If there are no headers, return an empty string
        if ($headers->length == 0) {
            return "";
        }

        $toc = '<div id="contentTable" class="table-of-contents"><h3>Table of contents</h3><ul>';

        foreach ($headers as $header) {
            // Create an id for the header if it doesn't exist
            if (!$header->hasAttribute('id')) {
                $headerId = preg_replace('/\s+/', '-', strtolower($header->nodeValue));
                $header->setAttribute('id', $headerId);
            } else {
                $headerId = $header->getAttribute('id');
            }

            switch ($header->nodeName) {
                case 'h2':
                    $toc .= "<li><a href='#{$headerId}'>{$header->nodeValue}</a></li>";
                    break;
                case 'h3':
                    $toc .= "<ul><li><a href='#{$headerId}'>{$header->nodeValue}</a></li></ul>";
                    break;
                case 'h4':
                    $toc .= "<ul><ul><li><a href='#{$headerId}'>{$header->nodeValue}</a></li></ul></ul>";
                    break;
            }
        }

        $toc .= '</ul></div>';

        return $toc;
    }
}
