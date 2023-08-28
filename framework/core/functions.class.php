<?php
declare(strict_types=1);
 
class Functions
{
    protected Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

  # return tooltip
    public function tooltip()
    {
        # variables
        $tooltip = array();

        # return subjects 
        $this->database->query('SELECT * FROM `subjects` WHERE `subjects`.`website` = :website');
        $this->database->bind(':website', WEBSITE);
        $subjects = $this->database->resultset();

        # set default tooltip
        foreach ($subjects as $key => $row) {
            # default consumer engagement score
            $tooltip[$row['ID']]['consumer'] = 'n/a';

            # default customer feedback
            $tooltip[$row['ID']]['feedback'] = 'n/a';

            # default brand reputation
            $tooltip[$row['ID']]['brand'] = 'n/a';

            # default features and benefits
            $tooltip[$row['ID']]['benefits'] = 'n/a';

            # default clicks
            $tooltip[$row['ID']]['clicks'] = 0;
        }

        # return clicks
        $this->database->query('SELECT `clicks`.`subject`, COUNT(*) as `count` FROM `clicks` LEFT JOIN `subjects` ON `clicks`.`subject` = `subjects`.`ID` WHERE `subjects`.`website` = :website AND `clicks`.`created` >= :created GROUP BY `subject` ORDER BY `count` DESC');
        $this->database->bind(':website', WEBSITE);
        $this->database->bind(':created', (time() - 2678400));
        $result = $this->database->resultset();

        # set consumer engagement score
        foreach ($result as $key => $row) {
            # set consumer engagement score
            $tooltip[$row['subject']]['consumer'] = number_format(10 - (floatval($key) / 10), 1);

            # set total clicks
            $tooltip[$row['subject']]['clicks'] = $row['count'];
        }

        # return average rating
        $this->database->query('SELECT `subjects`.`ID`, ROUND(SUM(`ratings`.`rating`) / COUNT(`ratings`.`rating`)) as `rating` FROM `ratings` LEFT JOIN `reviews` ON `ratings`.`review` = `reviews`.`ID` LEFT JOIN `subjects` ON `reviews`.`subject` = `subjects`.`ID` WHERE `subjects`.`website` = :website GROUP BY `subjects`.`ID`');
        $this->database->bind(':website', WEBSITE);
        $result = $this->database->resultset();

        # set customer feedback
        foreach ($result as $key => $row) {
            $tooltip[$row['ID']]['feedback'] = number_format(floatval($row['rating']), 1);
        }

        # return website rating
        $this->database->query('SELECT `subjects`.`ID`, `subjects`.`rating` FROM `subjects` WHERE `subjects`.`website` = :website');
        $this->database->bind(':website', WEBSITE);
        $result = $this->database->resultset();

        # return subject prompts
        $tooltip = array();
        foreach ($subjects as $subject) {
            $this->database->query('SELECT `prompts_count`.`subject`, `prompts_count`.`count`, COUNT(*) as `prompt_count` FROM `prompts_count` WHERE `prompts_count`.`subject` = :subject');
            $this->database->bind(':subject', $subject['ID']);
            $result = $this->database->resultset();
    
            # set brand reputation
            foreach ($result as $key => $row) {
                if (is_numeric($tooltip[$row['ID']]['feedback'])) {
                    $tooltip[$row['ID']]['brand'] = number_format((floatval($tooltip[$row['ID']]['feedback']) + floatval($row['rating'])) / 2, 1);
                } else {
                    $tooltip[$row['ID']]['brand'] = number_format(floatval($row['rating']), 1);
                }
            }
        }
    
        return $tooltip;
    }

    public function score(?int $score = null): string
    {
        return match (true) {
            $score >= 80 => 'Excellent',
            $score >= 60 => 'Good',
            $score >= 40 => 'Average',
            $score >= 20 => 'Moderate',
            default => 'Bad',
        };
    }

    public function bot(): bool
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT']);
    }

    public function shorter(string $string, int $length, string $trailing = '…'): string
    {
        return mb_strlen($string) > $length
            ? trim(mb_substr($string, 0, ($length - mb_strlen($trailing)))) . $trailing
            : $string;
    }

    public function compare(?string $subject = null): bool
    {
        return !empty($subject) && isset($_SESSION['compare']) && is_array($_SESSION['compare']) && in_array($subject, $_SESSION['compare']);
    }

    public function device(): string
    {
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($agent, 0, 4))
            ? 'mobile'
            : 'desktop';
    }

    public function browser(): string
    {
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        return match (true) {
            preg_match('/MSIE/i', $agent) && !preg_match('/Opera/i', $agent) => 'Internet Explorer',
            preg_match('/Firefox/i', $agent) => 'Mozilla Firefox',
            preg_match('/OPR/i', $agent) => 'Opera',
            preg_match('/Chrome/i', $agent) && !preg_match('/Edge/i', $agent) => 'Google Chrome',
            preg_match('/Safari/i', $agent) && !preg_match('/Edge/i', $agent) => 'Safari',
            preg_match('/Netscape/i', $agent) => 'Netscape',
            preg_match('/Edge/i', $agent) => 'Microsoft Edge',
            preg_match('/Trident/i', $agent) => 'Internet Explorer',
            default => '',
        };
    }

    public function platform(): string
    {
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        return match (true) {
            preg_match('/linux/i', $agent) => 'Linux',
            preg_match('/macintosh|mac os x/i', $agent) => 'MacOS',
            preg_match('/windows|win32/i', $agent) => 'Windows',
            default => '',
        };
    }

    public function host(?string $url = null): ?string
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return parse_url($url)['host'] ?? null;
    }

    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function redirect(?string $location = null): void
    {
        header('location: ' . $location, true, 303);
        exit;
    }

    public function days(?int $days = null): string
    {
        return match (true) {
            $days < 7 => $days . ($days !== 1 ? ' dagen' : ' dag'),
            $days >= 7 && $days < 30 => floor($days / 7) . ' weken',
            $days >= 30 && $days < 365 => floor($days / 30) . ' maanden',
            $days >= 365 => floor($days / 365) . ' jaar',
            default => 'Proef',
        };
    }

    public function currency(float $amount = 0, int $decimal = 2): string
    {
        return number_format($amount, $decimal, ',', '.');
    }

    public function breadcrumbs(array $data = []): void
    {
        echo '<ul>';
        echo '<li><a href="/">Home</a></li>';

        foreach ($data as $key => $row) {
            echo array_key_last($data) !== $key
                ? '<li><a href="' . $row['href'] . '">' . $row['name'] . '</a></li>'
                : '<li><span>' . $row['name'] . '</span></li>';
        }

        echo '</ul>';
    } 

     public function timestamp(?int $timestamp = null, string $format = 'j F Y'): string
    {
        setlocale(LC_TIME, 'nl_NL.utf8');

        $date = $timestamp !== null && $timestamp > 0
            ? (new DateTime())->setTimestamp($timestamp)
            : new DateTime();

        $result = $date->format($format);

        return str_replace(
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
            $result
        );
    }
}
