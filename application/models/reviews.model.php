<?php
class Reviews
{
    protected $database;
    protected $functions;

    public function __construct()
    {
        $this->database = new Database;
        $this->functions = new Functions;
    }

    # return categories
    public function categories()
    {
        $this->database->query('SELECT `ID`, `name` FROM `categories` WHERE `website` = :website AND `status` = :status ORDER BY `name` ASC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

    # return filter
    public function filter()
    {
        $this->database->query('SELECT `ID`, `name` FROM `filter` WHERE `website` = :website AND `status` = :status ORDER BY `position` ASC, `name` ASC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $results = $this->database->resultset();

        foreach ($results as $key => $result) {
            $this->database->query('SELECT `ID`, `name` FROM `filter_groups` WHERE `filter` = :id AND `status` = :status ORDER BY ABS(REPLACE(`name`, ".", "")) ASC');
            $this->database->bind(':id', $result['ID']);
            $this->database->bind(':status', 'active');
            $filter = $this->database->resultset();

            $results[$key]['filter'] = $filter;
        }

        return $results;
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

        # set data
        foreach ($result as $key => $row) {
            # set variables
            $count = $this->total($row['ID']);

            # set position
            $result[$key]['position'] = $position;

            # set total ratings
            $result[$key]['ratings'] = $count['ratings'];

            # set score
            $result[$key]['score'] = ($row['score'] > 0 ? $row['score'] : 60);

            # update position
            $position++;
        }

        return $result;
    }

    # return offers
    public function offers()
    {
        # set variables
        $position = 1;

        # return subjects
        $this->database->query('SELECT *, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`subject` = `subjects`.`ID`) as `score` FROM `subjects` WHERE `website` = :website AND `status` = :status ORDER BY `rating` DESC, `name` ASC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        # set result
        foreach ($result as $key => $row) {
            # set variables
            $count = $this->total($row['ID']);

            # set position
            $result[$key]['position'] = $position;

            # set features
            $result[$key]['features'] = $this->features($row['ID'], 'yes');

            # set total reviews
            $result[$key]['reviews'] = $count['reviews'];

            # set total ratings
            $result[$key]['ratings'] = $count['ratings'];
            
            # set prompts
            $result[$key]['prompts_count'] = $this->prompts($row['ID']);

            # set score
            $result[$key]['score'] = ($row['score'] > 0 ? $row['score'] : 60);

            # update position
            $position++;
        }

        return $result;
    }

    # return features
    public function features($id = null, $card = null)
    {
        if (!empty($card)) {
            $this->database->query('SELECT `icon`, `title`, (SELECT `content` FROM `characteristics` WHERE `feature` = `features`.`ID` AND `subject` = :subject) as `content` FROM `features` WHERE `features`.`website` = :website AND `card` = :card AND `status` = :status ORDER BY `position` ASC');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':subject', $id);
            $this->database->bind(':card', $card);
            $this->database->bind(':status', 'active');
            $result = $this->database->resultset();
        } else {
            $this->database->query('SELECT `icon`, `title`, (SELECT `content` FROM `characteristics` WHERE `feature` = `features`.`ID` AND `subject` = :subject) as `content` FROM `features` WHERE `features`.`website` = :website AND `status` = :status ORDER BY `position` ASC');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':subject', $id);
            $this->database->bind(':status', 'active');
            $result = $this->database->resultset();
        }

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

        # return total prompts
        $this->database->query('SELECT COUNT(*) FROM `prompts` WHERE `prompts`.`subject` = :subject');
        $this->database->bind(':subject', $id);
        $this->database->execute();
        $result['ratings'] = $this->database->fetchColumn();

        return $result;
    }

    # return subject existence
    public function exists($slug = null, $review = null)
    {
        # return subject
        if (!empty($review)){
            $this->database->query('SELECT COUNT(*) FROM `reviews` LEFT JOIN `subjects` ON `reviews`.`subject` = `subjects`.`ID` WHERE `subjects`.`website` = :website AND `subjects`.`slug` = :subject AND `subjects`.`status` = :status AND `reviews`.`slug` = :review AND `reviews`.`status` = :status ');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':review', $review);
            $this->database->bind(':subject', $slug);
            $this->database->bind(':status', 'active');
            $this->database->execute();
            $result = $this->database->fetchColumn();
        # return subject
        } else {
            $this->database->query('SELECT COUNT(*) FROM `subjects` WHERE `website` = :website AND `slug` = :slug AND `status` = :status');
            $this->database->bind(':website', WEBSITE);
            $this->database->bind(':slug', $slug);
            $this->database->bind(':status', 'active');
            $this->database->execute();
            $result = $this->database->fetchColumn();
        }

        return $result;
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

        # set score
        $result['score'] = $this->score($result['ID']);

        # set summary
        $result['summaries'] = $this->summaries($result['ID']);

        # set features
        $result['features'] = $this->features($result['ID']);

        # set impressions
        $result['impressions'] = $this->impressions($result['ID']);

        # set prices
        $result['prices'] = $this->prices($result['offer']);

        # set faq
        $result['faq'] = $this->faq($result['ID']);

        # set prompts
        $result['prompts'] = $this->prompts($result['ID'])['prompts'];

        # set prompts_count
        $result['prompts_count'] = $this->prompts($result['ID'])['prompts_count'];

        # set ratings
        $result['ratings'] = $this->ratings($result['ID']);

        # set reviews
        $result['reviews'] = $this->reviews($result['ID']);

        # set main category
        $result['category'] = $this->category($result['ID']);

        # set total reviews
        $result['count'] = $this->total($result['ID']);

        # set registrations
        $result['registrations'] = (!empty($result['registrations']) ? ceil((time() % 86400) / (86400 / $result['registrations'])) : 0);

        return $result;
    }

    # return review
    public function review($subject = null, $review = null)
    {
        # set reviews
        $this->database->query('SELECT `reviews`.*, (SELECT (AVG(`ratings`.`rating`) * 10) as `rating` FROM `ratings` WHERE `ratings`.`review` = `reviews`.`ID`) as `score` FROM `reviews` LEFT JOIN `subjects` ON `reviews`.`subject` = `subjects`.`ID` WHERE `subjects`.`slug` = :subject AND `subjects`.`status` = :active AND`reviews`.`slug` = :review AND (`reviews`.`status` = :active OR (`reviews`.`status` = :inactive AND `reviews`.`session` = :session)) ORDER BY `reviews`.`ID` DESC');
        $this->database->bind(':review', $review);
        $this->database->bind(':subject', $subject);
        $this->database->bind(':active', 'active');
        $this->database->bind(':inactive', 'inactive');
        $this->database->bind(':session', $_SESSION['session']);
        $result = $this->database->single();

        # set gender
        $gender = (in_array($result['gender'], ['male', 'female', 'other']) ? $result['gender'] : 'other');

        # set age
        if ($result['age'] <= 19) {
            $age = 'teenager';
        } elseif (in_array($result['age'], range(20, 29))) {
            $age = 'adolescent';
        } elseif (in_array($result['age'], range(30, 39))) {
            $age = 'young-adult';
        } elseif (in_array($result['age'], range(40, 49))) {
            $age = 'adult';
        } else {
            $age = 'senior';
        }

        # set thumbnail
        $result['thumbnail'] = $gender . '-' . $age . '.svg';
        
        # set strengths
        $result['strengths'] = $this->strengths($result['ID']);

        # set score
        $result['score'] = ($result['score'] > 0 ? $result['score'] : 60);

        # set ratings
        $this->database->query('SELECT `questions`.`ID`, `questions`.`title`, AVG(`ratings`.`rating`) as `rating` FROM `reviews` LEFT JOIN `ratings` ON `reviews`.`ID` = `ratings`.`review` LEFT JOIN `questions` ON `ratings`.`question` = `questions`.`ID` WHERE `reviews`.`ID` = :id AND `reviews`.`status` = :status AND `questions`.`status` = :status GROUP BY `questions`.`ID`');
        $this->database->bind(':id', $result['ID']);
        $this->database->bind(':status', 'active');
        $result['ratings'] = $this->database->resultset();

        # return result
        return $result;
    }

    # return comments
    public function comments($review = null)
    {
        # set reviews
        $this->database->query('SELECT * FROM `comments` WHERE `comments`.`review` = :review AND (`comments`.`status` = :active OR (`comments`.`status` = :inactive AND `comments`.`session` = :session)) ORDER BY `comments`.`ID` DESC');
        $this->database->bind(':review', $review);
        $this->database->bind(':active', 'active');
        $this->database->bind(':inactive', 'inactive');
        $this->database->bind(':session', $_SESSION['session']);
        $result = $this->database->resultset();

        return $result;
    }

    # return subject rating
    public function score($id = null)
    {
        $this->database->query('SELECT ROUND(AVG(`rating`) / 2, 1) as `average`, ROUND(MAX(`rating`) / 2, 1) as `best`, ROUND(MIN(`rating`) / 2, 1) as `worst` FROM `ratings` WHERE `subject` = :subject');
        $this->database->bind(':subject', $id);
        $result = $this->database->single();

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

    # return impressions
    public function impressions($id = null)
    {
        # set variables
        $result = array();

        # set pros
        $this->database->query('SELECT `title` FROM `impressions` WHERE `subject` = :subject AND `type` = :type');
        $this->database->bind(':subject', $id);
        $this->database->bind(':type', 'pro');
        $result['pros'] = $this->database->resultset();

        # set cons
        $this->database->query('SELECT `title` FROM `impressions` WHERE `subject` = :subject AND `type` = :type');
        $this->database->bind(':subject', $id);
        $this->database->bind(':type', 'con');
        $result['cons'] = $this->database->resultset();

        # return result
        return $result;
    }

    # return prices
    public function prices($id = null)
    {
        # return prices
        $this->database->query('SELECT `price`, `period`, `benefit`, `credits` FROM `prices` WHERE `offer` = :offer');
        $this->database->bind(':offer', $id);
        $result = $this->database->resultset();

        foreach ($result as $key => $row) {
            # set credits
            if ($row['credits'] == 'yes') {
                $result[$key]['period'] = $row['period'] . ' credits';
                $result[$key]['price'] = '&euro;' . $this->functions->currency($row['price'], 2);
                $result[$key]['monthly'] = '';
            # set subscription
            } else {
                $result[$key]['period'] = $this->functions->days($row['period']);
                $result[$key]['price'] = '&euro;' . $this->functions->currency($row['price'], 2);

                # set monthly
                if ($row['period'] >= 30){
                    $result[$key]['monthly'] = '&euro;' . $this->functions->currency(($row['price'] / $row['period']) * 30, 2);
                } else {
                    $result[$key]['monthly'] = '-';
                }
            }
        }

        return $result;
    }

    # return faq
    public function faq($id = null)
    {
        $this->database->query('SELECT `question`, `answer` FROM `faq` WHERE `subject` = :subject AND `status` = :status ORDER BY `position` ASC');
        $this->database->bind(':subject', $id);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

    # return prompts
    public function prompts($id = null)
    {
        $this->database->query('SELECT * FROM `prompts` WHERE `subject` = :subject AND `status` = :status ORDER BY `position` ASC');
        $this->database->bind(':subject', $id);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();
        $prompts_count = $this->getPromptsCountBySubject($id); 
        
        return [
            'prompts' => $result,
            'prompts_count' => $prompts_count
        ];
    }

# return prompts_count
public function getPromptsCountBySubject($subject = null) {
    $this->database->query('SELECT `count` FROM `prompts_count` WHERE `subject` = :subject LIMIT 1');
    $this->database->bind(':subject', $subject);
    $this->database->execute();
    return $this->database->fetchColumn();
}

    # return ratings
    public function ratings($id = null)
    {
        $this->database->query('SELECT `questions`.`ID`, `questions`.`title`, (SELECT AVG(`ratings`.`rating`) FROM `ratings` WHERE `ratings`.`subject` = :subject AND `ratings`.`question` = `questions`.`ID` ) as `rating` FROM `ratings` LEFT JOIN `questions` ON `ratings`.`question` = `questions`.`ID` WHERE `questions`.`website` = :website AND `questions`.`status` = :status GROUP BY `questions`.`ID`');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':subject', $id);
        $this->database->bind(':status', 'active');
        $result = $this->database->resultset();

        return $result;
    }

    # return reviews
    public function reviews($id = null, $limit = 5)
    {
        # set reviews
        $this->database->query('SELECT `reviews`.*, (SELECT (AVG(`ratings`.`rating`) * 10) FROM `ratings` WHERE `ratings`.`review` = `reviews`.`ID`) as `score`, (SELECT COUNT(*) FROM `likes` WHERE `likes`.`review` = `reviews`.`ID`) as `likes`, (SELECT COUNT(*) FROM `likes` WHERE `likes`.`review` = `reviews`.`ID` AND `likes`.`session` = :session) as `liked`, (SELECT COUNT(*) FROM `comments` WHERE `comments`.`review` = `reviews`.`ID` AND (`comments`.`status` = :active OR (`comments`.`status` = :inactive AND `comments`.`session` = :session))) as `comments` FROM `reviews` WHERE `subject` = :subject AND (`status` = :active OR (`status` = :inactive AND `session` = :session)) ORDER BY `reviews`.`ID` DESC LIMIT :current, :limit');
        $this->database->bind(':subject', $id);
        $this->database->bind(':active', 'active');
        $this->database->bind(':inactive', 'inactive');
        $this->database->bind(':session', $_SESSION['session']);
        $this->database->bind(':current', 0);
        $this->database->bind(':limit', $limit);
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
            } elseif ($row['age'] >= 50) {
                $age = 'senior';
            }

            # set thumbnail
            $result[$key]['thumbnail'] = $gender . '-' . $age . '.svg';

            # set strengths
            $result[$key]['strengths'] = $this->strengths($row['ID']);

            # set score
            $result[$key]['score'] = ($row['score'] > 0 ? $row['score'] : 60);
        }

        return $result;
    }

    # return strengths
    public function strengths($id = null)
    {
        # variables
        $result = array();

        # set pros
        $this->database->query('SELECT * FROM `strengths` WHERE `review` = :id AND `type` = :type');
        $this->database->bind(':id', $id);
        $this->database->bind(':type', 'pro');
        $result['pros'] = $this->database->resultset();

        # set cons
        $this->database->query('SELECT * FROM `strengths` WHERE `review` = :id AND `type` = :type');
        $this->database->bind(':id', $id);
        $this->database->bind(':type', 'con');
        $result['cons'] = $this->database->resultset();

        # return result
        return $result;
    }

    # return category
    public function category($id = null)
    {
        $this->database->query('SELECT `category` FROM `crossreference` WHERE `subject` = :subject ORDER BY `position` ASC LIMIT 0,1');
        $this->database->bind(':subject', $id);
        $this->database->execute();
        $result = $this->database->fetchColumn();

        return $result;
    }

    # return characteristics
    public function characteristic($subject = null, $feature = null)
    {
        $this->database->query('SELECT `content`  FROM `characteristics` WHERE `subject` = :subject AND `feature` = :feature');
        $this->database->bind(':subject', $subject);
        $this->database->bind(':feature', $feature);
        $this->database->execute();
        $result = $this->database->fetchColumn();

        return $result;
    }
}
