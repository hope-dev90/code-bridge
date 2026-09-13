<?php
/**
 * Allowlisted CodeBridge data layer for the public AI assistant.
 * Queries the TYPO3 MySQL database with fixed SQL only — never interpolates
 * user text into queries, and never selects passwords, emails, or other PII.
 */
declare(strict_types=1);

function cb_is_admin(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
    return !empty($_SESSION['careers_admin']);
}

function cb_db(): ?mysqli
{
    static $conn = false;
    if ($conn !== false) {
        return $conn instanceof mysqli ? $conn : null;
    }
    $conn = null;
    $cfgFile = __DIR__ . '/typo3conf/LocalConfiguration.php';
    if (!is_file($cfgFile)) {
        return null;
    }
    $cfg = include $cfgFile;
    $db = $cfg['DB']['Connections']['Default'] ?? [];
    if (!is_array($db) || empty($db['user']) || empty($db['dbname'])) {
        return null;
    }
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = @new mysqli(
        (string)($db['host'] ?? 'localhost'),
        (string)$db['user'],
        (string)($db['password'] ?? ''),
        (string)$db['dbname'],
        (int)($db['port'] ?? 3306)
    );
    if ($mysqli->connect_error) {
        return null;
    }
    $mysqli->set_charset('utf8mb4');
    $conn = $mysqli;
    return $conn;
}

function cb_table_exists(mysqli $db, string $table): bool
{
    static $cache = [];
    if (isset($cache[$table])) {
        return $cache[$table];
    }
    $stmt = $db->prepare(
        'SELECT 1 FROM information_schema.tables
         WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1'
    );
    if (!$stmt) {
        $cache[$table] = false;
        return false;
    }
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $res = $stmt->get_result();
    $cache[$table] = $res && $res->fetch_row() !== null;
    return $cache[$table];
}

function cb_tz(): DateTimeZone
{
    return new DateTimeZone('Africa/Kigali');
}

function cb_now(): DateTimeImmutable
{
    return new DateTimeImmutable('now', cb_tz());
}

/**
 * @return array{key:string,label_en:string,label_fr:string,label_rw:string,start:int,end:int}
 */
function cb_parse_period(string $q): array
{
    $q = mb_strtolower($q);
    $now = cb_now();
    $startOfToday = $now->setTime(0, 0, 0);

    $pack = static function (string $key, DateTimeImmutable $start, DateTimeImmutable $end, string $en, string $fr, string $rw): array {
        return [
            'key' => $key,
            'label_en' => $en,
            'label_fr' => $fr,
            'label_rw' => $rw,
            'start' => $start->getTimestamp(),
            'end' => $end->getTimestamp(),
        ];
    };

    if (preg_match('/\btoday\b|aujourd.?hui|\benone\b/u', $q)) {
        return $pack('today', $startOfToday, $startOfToday->modify('+1 day'), 'today', 'aujourd’hui', 'uyu munsi');
    }
    if (preg_match('/\byesterday\b|hier|\bejo\b/u', $q)) {
        $y = $startOfToday->modify('-1 day');
        return $pack('yesterday', $y, $startOfToday, 'yesterday', 'hier', 'ejo hashize');
    }
    if (preg_match('/last\s+week|semaine\s+derni|icyumweru\s+gishize/u', $q)) {
        $thisMon = $startOfToday->modify('monday this week');
        if ($thisMon > $startOfToday) {
            $thisMon = $thisMon->modify('-7 days');
        }
        $lastMon = $thisMon->modify('-7 days');
        return $pack('last_week', $lastMon, $thisMon, 'last week', 'la semaine dernière', 'icyumweru gishize');
    }
    if (preg_match('/this\s+week|cette\s+semaine|iki\s+cyumweru/u', $q)) {
        $thisMon = $startOfToday->modify('monday this week');
        if ($thisMon > $startOfToday) {
            $thisMon = $thisMon->modify('-7 days');
        }
        return $pack('this_week', $thisMon, $thisMon->modify('+7 days'), 'this week', 'cette semaine', 'iki cyumweru');
    }
    if (preg_match('/last\s+month|mois\s+dernier|ukwezi\s+gushize/u', $q)) {
        $first = $now->modify('first day of last month')->setTime(0, 0, 0);
        $end = $now->modify('first day of this month')->setTime(0, 0, 0);
        $en = 'last month (' . $first->format('F Y') . ')';
        return $pack('last_month', $first, $end, $en, 'le mois dernier (' . $first->format('F Y') . ')', 'ukwezi gushize');
    }
    if (preg_match('/this\s+month|ce\s+mois|uku\s+kwezi/u', $q)) {
        $first = $now->modify('first day of this month')->setTime(0, 0, 0);
        $end = $first->modify('first day of next month');
        $en = 'this month (' . $first->format('F Y') . ')';
        return $pack('this_month', $first, $end, $en, 'ce mois (' . $first->format('F Y') . ')', 'uku kwezi (' . $first->format('F Y') . ')');
    }
    if (preg_match('/last\s+year|l.?année\s+derni|umwaka\s+ushize/u', $q)) {
        $y = (int)$now->format('Y') - 1;
        $first = new DateTimeImmutable("$y-01-01 00:00:00", cb_tz());
        $end = new DateTimeImmutable(($y + 1) . '-01-01 00:00:00', cb_tz());
        return $pack('last_year', $first, $end, "last year ($y)", "l’année dernière ($y)", "umwaka ushize ($y)");
    }
    if (preg_match('/this\s+year|cette\s+année|uyu\s+mwaka/u', $q)) {
        $y = (int)$now->format('Y');
        $first = new DateTimeImmutable("$y-01-01 00:00:00", cb_tz());
        $end = new DateTimeImmutable(($y + 1) . '-01-01 00:00:00', cb_tz());
        return $pack('this_year', $first, $end, "this year ($y)", "cette année ($y)", "uyu mwaka ($y)");
    }

    $months = [
        'january' => 1, 'janvier' => 1, 'mutarama' => 1,
        'february' => 2, 'février' => 2, 'fevrier' => 2, 'gashyantare' => 2,
        'march' => 3, 'mars' => 3, 'werurwe' => 3,
        'april' => 4, 'avril' => 4, 'mata' => 4,
        'may' => 5, 'mai' => 5, 'gicurasi' => 5,
        'june' => 6, 'juin' => 6, 'kamena' => 6,
        'july' => 7, 'juillet' => 7, 'nyakanga' => 7,
        'august' => 8, 'août' => 8, 'aout' => 8, 'kanama' => 8,
        'september' => 9, 'septembre' => 9, 'nzeli' => 9,
        'october' => 10, 'octobre' => 10, 'ukwakira' => 10,
        'november' => 11, 'novembre' => 11, 'ugushyingo' => 11,
        'december' => 12, 'décembre' => 12, 'decembre' => 12, 'ukuboza' => 12,
    ];
    foreach ($months as $name => $num) {
        if (!preg_match('/\b' . preg_quote($name, '/') . '\b/u', $q)) {
            continue;
        }
        $year = (int)$now->format('Y');
        if (preg_match('/\b(20\d{2})\b/', $q, $ym)) {
            $year = (int)$ym[1];
        } elseif ($num > (int)$now->format('n')) {
            $year--;
        }
        $first = new DateTimeImmutable(sprintf('%04d-%02d-01 00:00:00', $year, $num), cb_tz());
        $end = $first->modify('first day of next month');
        $label = $first->format('F Y');
        return $pack('month', $first, $end, $label, $label, $label);
    }

    $allStart = new DateTimeImmutable('2000-01-01 00:00:00', cb_tz());
    $allEnd = $now->modify('+1 day')->setTime(0, 0, 0);
    return $pack('all', $allStart, $allEnd, 'all time', 'toute la période disponible', 'igihe cyose kiboneka');
}

function cb_period_label(array $period, string $lang): string
{
    if ($lang === 'FR') {
        return $period['label_fr'];
    }
    if ($lang === 'KINY') {
        return $period['label_rw'];
    }
    return $period['label_en'];
}

function cb_int_result(?mysqli_result $res): ?int
{
    if (!$res) {
        return null;
    }
    $row = $res->fetch_row();
    return $row ? (int)$row[0] : 0;
}

function cb_count_news(?mysqli $db, int $startTs, int $endTs): ?int
{
    if (!$db || !cb_table_exists($db, 'tx_news_domain_model_news')) {
        return null;
    }
    $now = time();
    $stmt = $db->prepare(
        'SELECT COUNT(*) FROM tx_news_domain_model_news
         WHERE deleted = 0 AND hidden = 0
           AND (sys_language_uid = 0 OR sys_language_uid IS NULL)
           AND (starttime = 0 OR starttime <= ?)
           AND (endtime = 0 OR endtime > ?)
           AND datetime >= ? AND datetime < ?'
    );
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('iiii', $now, $now, $startTs, $endTs);
    $stmt->execute();
    return cb_int_result($stmt->get_result());
}

/**
 * @return list<array{title:string,date:string,slug:string,datetime:int}>
 */
function cb_list_news(?mysqli $db, int $limit = 12): array
{
    if (!$db || !cb_table_exists($db, 'tx_news_domain_model_news')) {
        return [];
    }
    $limit = max(1, min(20, $limit));
    $now = time();
    $sql = 'SELECT title, datetime, path_segment FROM tx_news_domain_model_news
            WHERE deleted = 0 AND hidden = 0
              AND (sys_language_uid = 0 OR sys_language_uid IS NULL)
              AND (starttime = 0 OR starttime <= ?)
              AND (endtime = 0 OR endtime > ?)
            ORDER BY datetime DESC, uid DESC
            LIMIT ' . $limit;
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param('ii', $now, $now);
    $stmt->execute();
    $res = $stmt->get_result();
    $out = [];
    while ($res && ($row = $res->fetch_assoc())) {
        $ts = (int)$row['datetime'];
        $slug = trim((string)$row['path_segment']);
        $out[] = [
            'title' => (string)$row['title'],
            'date' => $ts ? (new DateTimeImmutable('@' . $ts))->setTimezone(cb_tz())->format('j F Y') : '',
            'slug' => $slug,
            'datetime' => $ts,
        ];
    }
    return $out;
}

function cb_count_simple(?mysqli $db, string $table, string $whereSql): ?int
{
    if (!$db || !cb_table_exists($db, $table)) {
        return null;
    }
    $res = $db->query('SELECT COUNT(*) FROM `' . $table . '` ' . $whereSql);
    return cb_int_result($res);
}

/**
 * @return list<array{title:string,type:string}>
 */
function cb_list_jobs(?mysqli $db): array
{
    if (!$db || !cb_table_exists($db, 'codebridge_jobs')) {
        return [];
    }
    $res = $db->query(
        'SELECT title, job_type FROM codebridge_jobs WHERE status = "active" ORDER BY created_at DESC LIMIT 30'
    );
    $out = [];
    while ($res && ($row = $res->fetch_assoc())) {
        $out[] = [
            'title' => (string)$row['title'],
            'type' => (string)$row['job_type'],
        ];
    }
    return $out;
}

/**
 * Official public website facts. These are published on codebrige.rw, not stored
 * as countable rows in MySQL. Always label them as website content.
 */
function cb_website_catalog(): array
{
    return [
        'services_featured' => [
            'Cybersecurity',
            'AI & Machine Learning',
            'Consultancy & Strategy',
            'Internship Program',
            'IKiraro Innovation Hub',
            'IKiraro E-Learning Platform',
        ],
        'services_also' => [
            'Software development',
            'Web and mobile apps',
            'Online integration with Rwanda e-government platforms (Irembo, RURA, RSSB)',
            'Safety services (CCTV, incident alerts, fire equipment)',
        ],
        'projects' => [
            'School management system',
            'HR platform',
            'Food delivery app',
            'Crop disease detector',
            'Cyberlympics training system',
            'IKiraro E-Learning',
        ],
        'partners' => [
            'Core Group — https://coregroup.rw/',
            'Loxotech — https://loxotech.com/#home',
            'Le Plaisir d’Enfant School — https://leplaisirdenfant.com/',
            'Saint Emmanuel School Complex — https://saintemanuelschool.com/',
            'Codible Group — https://www.codiblegroup.com/',
            'Karenge Adventist Secondary School — https://kasschool.wordpress.com/',
            'IKiraro Innovation Hub (Codebridge service)',
        ],
        'team' => [
            'HABUMUGISHA Olivier — Chief Executive Officer',
            'MWENZI Teddy — Chief Financial Officer',
            'MUGISHA Patrick — Lead Software Engineer',
            'UWIMANA Sandra — Head of Cybersecurity',
            'NIYONZIMA Eric — DevOps Engineer',
        ],
        'success_stories_on_homepage' => 3,
        'contact' => [
            'email' => 'info@codebrige.rw',
            'phone' => '+250 788 288 546',
            'location' => 'Kigali, Gasabo, Kabuga (Rusoro), 26G9+6H9, Rwanda',
            'hours' => 'Mon–Fri 8:00–17:00; Sat & Sun 12:00–17:00',
            'website' => 'https://codebrige.rw',
            'elearning' => 'https://ikiraro-elearrning.vercel.app/',
        ],
    ];
}

/**
 * @return array<string,mixed>
 */
function cb_live_snapshot(bool $includeAdmin): array
{
    $db = cb_db();
    $now = cb_now();
    $queriedAt = $now->format('j F Y, H:i') . ' Africa/Kigali';
    $monthStart = $now->modify('first day of this month')->setTime(0, 0, 0);
    $monthEnd = $monthStart->modify('first day of next month');
    $yearStart = new DateTimeImmutable($now->format('Y') . '-01-01 00:00:00', cb_tz());
    $yearEnd = $yearStart->modify('+1 year');
    $allStart = new DateTimeImmutable('2000-01-01 00:00:00', cb_tz());
    $allEnd = $now->modify('+1 day')->setTime(0, 0, 0);

    $newsAll = cb_count_news($db, $allStart->getTimestamp(), $allEnd->getTimestamp());
    $newsMonth = cb_count_news($db, $monthStart->getTimestamp(), $monthEnd->getTimestamp());
    $newsYear = cb_count_news($db, $yearStart->getTimestamp(), $yearEnd->getTimestamp());
    $newsToday = cb_count_news($db, $now->setTime(0, 0, 0)->getTimestamp(), $now->setTime(0, 0, 0)->modify('+1 day')->getTimestamp());

    $jobs = cb_list_jobs($db);
    $jobTotal = cb_count_simple($db, 'codebridge_jobs', 'WHERE status = "active"');
    $jobIntern = cb_count_simple($db, 'codebridge_jobs', 'WHERE status = "active" AND job_type = "internship"');
    $jobRoles = cb_count_simple($db, 'codebridge_jobs', 'WHERE status = "active" AND job_type = "job"');
    $blog = cb_count_simple($db, 'codebridge_blog_posts', 'WHERE status = "published"');
    $gallery = cb_count_simple($db, 'codebridge_gallery', 'WHERE status = "published"');
    $feUsers = cb_count_simple($db, 'fe_users', 'WHERE deleted = 0 AND disable = 0');

    $unavailable = [
        'unique_visitors' => 'No visitor or analytics tables exist in the connected database.',
        'total_visits' => 'No visit log is stored.',
        'sessions' => 'No session analytics table is stored for the public website.',
        'page_views' => 'No page-view log is stored.',
        'newsletter_subscribers' => 'The newsletter form does not save addresses to the database.',
        'contact_inquiries' => 'Contact form messages are emailed to info@codebrige.rw and are not stored in MySQL.',
    ];

    $snap = [
        'queried_at' => $queriedAt,
        'db_connected' => $db instanceof mysqli,
        'admin' => $includeAdmin,
        'news' => [
            'source' => 'tx_news_domain_model_news',
            'published_all_time' => $newsAll,
            'published_today' => $newsToday,
            'published_this_month' => $newsMonth,
            'published_this_year' => $newsYear,
            'titles' => cb_list_news($db, 12),
        ],
        'jobs' => [
            'source' => 'codebridge_jobs',
            'active_total' => $jobTotal,
            'active_jobs' => $jobRoles,
            'active_internships' => $jobIntern,
            'titles' => $jobs,
        ],
        'blog_posts' => [
            'source' => 'codebridge_blog_posts',
            'published' => $blog,
        ],
        'gallery' => [
            'source' => 'codebridge_gallery',
            'published' => $gallery,
        ],
        'registered_frontend_users' => [
            'source' => 'fe_users',
            'count' => $feUsers,
            'note' => 'TYPO3 frontend users. The public website does not offer self-registration.',
        ],
        'unavailable' => $unavailable,
        'website' => cb_website_catalog(),
    ];

    if ($includeAdmin) {
        $appsAll = cb_count_simple($db, 'codebridge_job_applications', '');
        $appsMonth = null;
        if ($db && cb_table_exists($db, 'codebridge_job_applications')) {
            $start = $monthStart->format('Y-m-d H:i:s');
            $end = $monthEnd->format('Y-m-d H:i:s');
            $stmt = $db->prepare('SELECT COUNT(*) FROM codebridge_job_applications WHERE created_at >= ? AND created_at < ?');
            if ($stmt) {
                $stmt->bind_param('ss', $start, $end);
                $stmt->execute();
                $appsMonth = cb_int_result($stmt->get_result());
            }
        }
        $byStatus = [];
        if ($db && cb_table_exists($db, 'codebridge_job_applications')) {
            $res = $db->query('SELECT status, COUNT(*) AS c FROM codebridge_job_applications GROUP BY status');
            while ($res && ($row = $res->fetch_assoc())) {
                $byStatus[(string)$row['status']] = (int)$row['c'];
            }
        }
        $snap['applications'] = [
            'source' => 'codebridge_job_applications',
            'total' => $appsAll,
            'this_month' => $appsMonth,
            'by_status' => $byStatus,
            'note' => 'Counts only. Candidate names, emails, phones and files are not included.',
        ];
    } else {
        $snap['restricted'] = [
            'job_applications' => 'Internal. A public visitor may not receive application records or counts.',
            'backend_users' => 'Internal. CMS account details are never exposed.',
        ];
    }

    return $snap;
}

function cb_snapshot_text(array $snap): string
{
    $w = $snap['website'];
    $lines = [];
    $lines[] = 'LIVE DATABASE SNAPSHOT queried at ' . $snap['queried_at'] . '.';
    $lines[] = $snap['db_connected']
        ? 'Database connection: successful. Numbers below are database values unless labelled otherwise.'
        : 'Database connection: unavailable. Do not invent replacement statistics.';
    $lines[] = '';
    $lines[] = 'Published news articles (table tx_news_domain_model_news):';
    $lines[] = 'All time: ' . cb_num($snap['news']['published_all_time']);
    $lines[] = 'Today: ' . cb_num($snap['news']['published_today']);
    $lines[] = 'This month: ' . cb_num($snap['news']['published_this_month']);
    $lines[] = 'This year: ' . cb_num($snap['news']['published_this_year']);
    if ($snap['news']['titles']) {
        $lines[] = 'Latest titles:';
        foreach ($snap['news']['titles'] as $i => $item) {
            $n = $i + 1;
            $lines[] = $n . ') ' . $item['title'] . ($item['date'] ? ' (' . $item['date'] . ')' : '');
        }
    }
    $lines[] = '';
    $lines[] = 'Active job listings (table codebridge_jobs): ' . cb_num($snap['jobs']['active_total']);
    $lines[] = 'Active jobs: ' . cb_num($snap['jobs']['active_jobs']) . '; active internships: ' . cb_num($snap['jobs']['active_internships']);
    if ($snap['jobs']['titles']) {
        foreach ($snap['jobs']['titles'] as $i => $job) {
            $lines[] = ($i + 1) . ') ' . $job['title'] . ' [' . $job['type'] . ']';
        }
    }
    $lines[] = 'Published CMS blog posts (codebridge_blog_posts): ' . cb_num($snap['blog_posts']['published']);
    $lines[] = 'Published gallery photos (codebridge_gallery): ' . cb_num($snap['gallery']['published']);
    $lines[] = 'Registered frontend users (fe_users, not deleted/disabled): ' . cb_num($snap['registered_frontend_users']['count']);
    $lines[] = $snap['registered_frontend_users']['note'];
    $lines[] = '';
    $lines[] = 'NOT IN THE DATABASE (say this is unavailable; never estimate):';
    foreach ($snap['unavailable'] as $key => $why) {
        $lines[] = $key . ': ' . $why;
    }
    if (!empty($snap['restricted'])) {
        $lines[] = '';
        $lines[] = 'RESTRICTED FOR THIS VISITOR (reply exactly: You don\'t have permission to access that information.):';
        foreach ($snap['restricted'] as $key => $why) {
            $lines[] = $key . ': ' . $why;
        }
    }
    if (!empty($snap['applications'])) {
        $a = $snap['applications'];
        $lines[] = '';
        $lines[] = 'ADMIN-ONLY COUNTS (no personal data):';
        $lines[] = 'Job applications total: ' . cb_num($a['total']);
        $lines[] = 'Job applications this month: ' . cb_num($a['this_month']);
        if ($a['by_status']) {
            foreach ($a['by_status'] as $st => $c) {
                $lines[] = 'Status ' . $st . ': ' . $c;
            }
        }
    }
    $lines[] = '';
    $lines[] = 'OFFICIAL WEBSITE CONTENT (not MySQL tables — say so if asked for a database count):';
    $lines[] = 'Featured services (' . count($w['services_featured']) . '): ' . implode('; ', $w['services_featured']);
    $lines[] = 'Also listed: ' . implode('; ', $w['services_also']);
    $lines[] = 'Published projects (' . count($w['projects']) . '): ' . implode('; ', $w['projects']);
    $lines[] = 'Partners (' . count($w['partners']) . '): ' . implode('; ', $w['partners']);
    $lines[] = 'Team members on the About page (' . count($w['team']) . '): ' . implode('; ', $w['team']);
    $lines[] = 'Success stories shown on the homepage: ' . $w['success_stories_on_homepage'];
    $c = $w['contact'];
    $lines[] = 'Contact: ' . $c['email'] . ', ' . $c['phone'] . ', ' . $c['location'] . '. Hours: ' . $c['hours'] . '. Site: ' . $c['website'] . '. IKiraro E-Learning: ' . $c['elearning'];
    return implode("\n", $lines);
}

function cb_num($value): string
{
    if ($value === null) {
        return 'not available (table missing or query failed)';
    }
    return (string)(int)$value;
}

function cb_normalize_chat(string $text): string
{
    $q = mb_strtolower(trim($text));
    $q = preg_replace('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', '', $q) ?? $q;
    $q = str_replace(['’', '‘'], "'", $q);
    $q = preg_replace('/[^\p{L}\p{N}\s\']/u', ' ', $q) ?? $q;
    $q = preg_replace('/\s+/u', ' ', $q) ?? $q;
    $q = trim($q);
    $q = preg_replace('/\bcode\s*bridges?\b/u', '', $q) ?? $q;
    $q = preg_replace('/\bcodebrige\b/u', '', $q) ?? $q;
    return trim(preg_replace('/\s+/u', ' ', $q) ?? $q);
}

function cb_smalltalk_reply(string $text, string $lang): ?string
{
    $q = cb_normalize_chat($text);
    if ($q === '') {
        return null;
    }

    $pack = static function (string $en, string $fr, string $rw) use ($lang): string {
        if ($lang === 'FR') {
            return $fr;
        }
        if ($lang === 'KINY') {
            return $rw;
        }
        return $en;
    };

    $hello = ['hello', 'hi', 'hey', 'hiya', 'yo', 'hola', 'bonjour', 'salut', 'bonsoir', 'muraho', 'bite', 'mwiriwe', 'hi there', 'hello there', 'hey there'];
    $morning = ['good morning'];
    $evening = ['good evening', 'good night', 'bonsoir', 'bonne nuit'];
    $afternoon = ['good afternoon'];
    $how = ['how are you', 'how are you doing', 'how are you today', 'how do you do', "how's it going", 'how is it going', 'comment ca va', 'comment ça va', 'comment vas tu', 'ca va', 'amakuru', 'umeze gute'];
    $thanks = ['thanks', 'thank you', 'thank you so much', 'thanks a lot', 'thx', 'ty', 'merci', 'merci beaucoup', 'murakoze', 'murakoze cyane'];
    $welcome = ["you're welcome", 'you are welcome', 'welcome', 'de rien', 'ntacyo'];
    $bye = ['bye', 'goodbye', 'good bye', 'see you', 'see ya', 'later', 'ciao', 'au revoir', 'bye bye', 'uragendaye', 'tuzabonana'];
    $who = ['who are you', 'what are you', 'what is your name', "what's your name", 'qui es tu', 'qui etes vous', 'uri nde', 'ni nde'];
    $cando = ['what can you do', 'what do you do', 'how can you help', 'how can you help me', 'what can you help with', 'help', 'que peux tu faire', 'ushobora gukora iki'];

    $hit = static function (array $needles, string $q): bool {
        return in_array($q, $needles, true);
    };

    if ($hit($who, $q)) {
        return $pack(
            "I'm the CodeBridge AI assistant. I can help you with CodeBridge's services, projects, team, statistics, analytics, and other official information.",
            "Je suis l’assistant IA CodeBridge. Je peux vous aider sur les services, projets, l’équipe, les statistiques et les informations officielles de CodeBridge.",
            "Ndi umufasha wa CodeBridge. Nshobora kugufasha ku serivisi, imishinga, ikipe, imibare n'amakuru y'umwihariko ya CodeBridge."
        );
    }
    if ($hit($cando, $q)) {
        return $pack(
            "I can help you explore CodeBridge, answer questions about our services and projects, and provide available statistics and information from the CodeBridge database.",
            "Je peux vous faire découvrir CodeBridge, répondre sur nos services et projets, et donner les statistiques et informations disponibles dans la base CodeBridge.",
            "Nshobora kugufasha kumenya CodeBridge, gusubiza ku serivisi n'imishinga, no gutanga imibare n'amakuru aboneka muri database ya CodeBridge."
        );
    }
    if ($hit($how, $q)) {
        return $pack(
            "I'm doing well, thanks! I'm the CodeBridge AI assistant. How can I help you?",
            "Je vais bien, merci ! Je suis l’assistant IA CodeBridge. Comment puis-je vous aider ?",
            "Meze neza, murakoze! Ndi umufasha wa CodeBridge. Nagufasha iki?"
        );
    }
    if ($hit($thanks, $q)) {
        return $pack(
            "You're welcome! 😊",
            "Avec plaisir ! 😊",
            "Nta kibazo! 😊"
        );
    }
    if ($hit($welcome, $q)) {
        return $pack("Glad I could help.", "Avec plaisir.", "Twishimiye kugufasha.");
    }
    if ($hit($bye, $q)) {
        return $pack(
            "Goodbye! If you need anything about CodeBridge, I’m here.",
            "Au revoir ! Revenez quand vous voulez pour CodeBridge.",
            "Murabeho! Niba ukeneye ikindi cyerekeye CodeBridge, ndi hano."
        );
    }
    if ($hit($morning, $q) || $q === 'good morning') {
        return $pack(
            "Good morning! 👋 How can I help you today?",
            "Bonjour ! 👋 Comment puis-je vous aider aujourd’hui ?",
            "Mwaramutse! 👋 Nagufasha iki uyu munsi?"
        );
    }
    if ($hit($afternoon, $q)) {
        return $pack(
            "Good afternoon! 👋 How can I help you today?",
            "Bon après-midi ! 👋 Comment puis-je vous aider ?",
            "Mwiriwe! 👋 Nagufasha iki?"
        );
    }
    if ($hit($evening, $q)) {
        return $pack(
            "Good evening! 👋 How can I help you today?",
            "Bonsoir ! 👋 Comment puis-je vous aider ?",
            "Mwiriwe! 👋 Nagufasha iki?"
        );
    }
    if ($hit($hello, $q)) {
        if ($q === 'hi') {
            return $pack(
                "Hi! 👋 How can I help you with CodeBridge?",
                "Salut ! 👋 Comment puis-je vous aider pour CodeBridge ?",
                "Bite! 👋 Nagufasha iki kuri CodeBridge?"
            );
        }
        return $pack(
            "Hello! 👋 Welcome to CodeBridge. How can I help you today?",
            "Bonjour ! 👋 Bienvenue chez CodeBridge. Comment puis-je vous aider ?",
            "Muraho! 👋 Murakaza neza kuri CodeBridge. Nagufasha iki uyu munsi?"
        );
    }
    return null;
}

function cb_is_off_topic(string $q): bool
{
    if (cb_smalltalk_reply($q, 'EN') !== null) {
        return false;
    }
    $q = mb_strtolower($q);
    if (preg_match('/code\s*bridg|ikiraro|nyarugenge|codebrige|typo3 news|internship|cyber/u', $q)) {
        return false;
    }
    $unrelated = '/\b(recipe|weather|forecast|horoscope|lottery|bitcoin|cryptocurrency|capital of|who won|world cup|premier league|write .{0,40}(poem|essay|python|javascript)|homework|love poem|tell me a joke|translate this|movie times|scrape websites)\b/u';
    return (bool)preg_match($unrelated, $q);
}

function cb_is_restricted_ask(string $q, bool $admin): bool
{
    if ($admin) {
        return false;
    }
    $q = mb_strtolower($q);
    $internal = (bool)preg_match('/\b(applications?|applicants?|cvs?|resumes?|candidates?|shortlist|who applied)\b/u', $q);
    $wantsData = (bool)preg_match('/how many|number of|count|list|names?|emails?|phone/u', $q);
    return $internal && $wantsData;
}

function cb_stats_kind(string $q): ?string
{
    $q = mb_strtolower($q);
    $asksCount = (bool)preg_match('/how many|number of|count|statistic|stats|combien|ongera|umubare/u', $q);
    if (preg_match('/unique visitors?|visiteur unique/u', $q)) {
        return 'unique_visitors';
    }
    if (preg_match('/page ?views?/u', $q)) {
        return 'page_views';
    }
    if (preg_match('/\bsessions?\b/u', $q) && preg_match('/visit|user|web|site|analytics/u', $q)) {
        return 'sessions';
    }
    if (preg_match('/visit(?:ed|s|ors?)?|traffic|analytics|people (?:who )?visited/u', $q)
        && ($asksCount || preg_match('/this (month|week|year)|today|yesterday/u', $q))) {
        return 'visits';
    }
    if (preg_match('/newsletter|subscriber/u', $q) && $asksCount) {
        return 'newsletter';
    }
    if (preg_match('/inquir|contact request|contact form|feedback/u', $q) && $asksCount) {
        return 'inquiries';
    }
    if (preg_match('/registered user|user registration|sign.?ups?|fe_users/u', $q)) {
        return 'users';
    }
    if (preg_match('/active user/u', $q)) {
        return 'active_users';
    }
    if (preg_match('/\b(news|articles?|blog posts?|ingingo|actualité)\b/u', $q) && $asksCount) {
        return 'news';
    }
    if (preg_match('/\bprojects?\b|imishinga|projets?/u', $q) && $asksCount) {
        return 'projects';
    }
    if (preg_match('/\bservices?\b|serivisi/u', $q) && $asksCount) {
        return 'services';
    }
    if (preg_match('/partner|partenaire|abafatanya/u', $q) && $asksCount) {
        return 'partners';
    }
    if (preg_match('/team members?|\bstaff\b|employees?|équipe|how many people (?:work|are on the team|in the team)/u', $q) && $asksCount) {
        return 'team';
    }
    if (preg_match('/testimonial|success stor/u', $q) && $asksCount) {
        return 'stories';
    }
    if (preg_match('/galler(y|ies)|photos?/u', $q) && $asksCount) {
        return 'gallery';
    }
    if (preg_match('/applications?|applicants?/u', $q) && $asksCount) {
        return 'applications';
    }
    if (preg_match('/\bjobs?\b|openings?|vacancies|internship listings?/u', $q) && $asksCount) {
        return 'jobs';
    }
    return null;
}

function cb_direct_stats_reply(string $q, string $lang, array $snap): ?string
{
    $kind = cb_stats_kind($q);
    if ($kind === null) {
        return null;
    }
    $period = cb_parse_period($q);
    $pl = cb_period_label($period, $lang);
    $db = cb_db();

    $unavailableVisitors = [
        'EN' => "I couldn't find visitor statistics for $pl in the available CodeBridge data. Unique visitors, total visits, sessions and page views are not stored in the connected database, so I cannot give a number.",
        'FR' => "Je n’ai trouvé aucune statistique de visiteurs pour $pl dans les données CodeBridge disponibles. Les visiteurs uniques, visites, sessions et pages vues ne sont pas enregistrés dans la base, je ne peux donc pas donner de chiffre.",
        'KINY' => "Sinabona imibare y'abasura kuri $pl mu makuru ya CodeBridge aboneka. Abasura, visits, sessions na page views ntabwo bibikwa muri database, ntashobora gutanga umubare.",
    ];

    switch ($kind) {
        case 'unique_visitors':
        case 'visits':
        case 'sessions':
        case 'page_views':
        case 'active_users':
            return $unavailableVisitors[$lang] ?? $unavailableVisitors['EN'];
        case 'newsletter':
            return [
                'EN' => 'Newsletter subscribers are not stored in the CodeBridge database. The website form does not save addresses, so I cannot give a count.',
                'FR' => 'Les abonnés à la newsletter ne sont pas enregistrés dans la base CodeBridge. Le formulaire du site ne sauvegarde pas les adresses, je ne peux donc pas donner de nombre.',
                'KINY' => 'Abayobowe kuri newsletter ntabwo babikwa muri database ya CodeBridge. Form y\'urubuga ntiyabika aderesi, ntashobora gutanga umubare.',
            ][$lang];
        case 'inquiries':
            return [
                'EN' => 'Contact requests are emailed to info@codebrige.rw and are not stored in the CodeBridge database, so I cannot give a count.',
                'FR' => 'Les demandes de contact sont envoyées par e-mail à info@codebrige.rw et ne sont pas stockées dans la base, je ne peux donc pas donner de nombre.',
                'KINY' => 'Ubutumwa bwo kuvugana bwoherezwa kuri info@codebrige.rw kandi ntibubikwa muri database, ntashobora gutanga umubare.',
            ][$lang];
        case 'users':
            $n = $snap['registered_frontend_users']['count'];
            return [
                'EN' => "CodeBridge — registered frontend users (database table fe_users, $pl if filtered by registration is not available for public self-signup): " . cb_num($n) . '. The public website does not offer user self-registration. This is a database value, not an estimate.',
                'FR' => 'Utilisateurs frontend enregistrés (table fe_users) : ' . cb_num($n) . '. Le site public n’offre pas d’inscription libre. Valeur issue de la base, pas une estimation.',
                'KINY' => 'Abakoresha fe_users banditswe: ' . cb_num($n) . '. Urubuga rwa rubanda ntirwemerera kwiyandikisha. Ni umubare uva muri database.',
            ][$lang];
        case 'news':
            $count = $period['key'] === 'all'
                ? $snap['news']['published_all_time']
                : cb_count_news($db, $period['start'], $period['end']);
            $titles = '';
            if ($snap['news']['titles'] && $period['key'] === 'all') {
                $bits = [];
                foreach ($snap['news']['titles'] as $i => $item) {
                    $bits[] = ($i + 1) . ') ' . $item['title'] . ($item['date'] ? ' (' . $item['date'] . ')' : '');
                }
                $titles = ' Latest published titles: ' . implode(' ', $bits);
            }
            return [
                'EN' => 'CodeBridge — published news articles, ' . $pl . '. Database value from tx_news_domain_model_news: ' . cb_num($count) . '.' . $titles,
                'FR' => 'Articles d’actualité publiés, ' . $pl . '. Valeur base (tx_news_domain_model_news) : ' . cb_num($count) . '.' . $titles,
                'KINY' => 'Ingingo z\'amakuru zasohotse, ' . $pl . '. Umubare wa database (tx_news_domain_model_news): ' . cb_num($count) . '.' . $titles,
            ][$lang];
        case 'projects':
            $n = count($snap['website']['projects']);
            $list = implode('; ', $snap['website']['projects']);
            return [
                'EN' => "The CodeBridge database does not have a projects table. The official website currently lists $n published projects: $list. That $n is website content, not a database row count.",
                'FR' => "La base CodeBridge n’a pas de table projets. Le site officiel liste actuellement $n projets : $list. Ce chiffre vient du site, pas d’un COUNT SQL.",
                'KINY' => "Database ya CodeBridge nta table y'imishinga ifite. Urubuga rwatangaza imishinga $n: $list. Uwo mubare uva ku rubuga, si COUNT ya database.",
            ][$lang];
        case 'services':
            $n = count($snap['website']['services_featured']);
            $list = implode('; ', $snap['website']['services_featured']);
            $also = implode('; ', $snap['website']['services_also']);
            return [
                'EN' => "The CodeBridge database does not have a services table. The website currently highlights $n featured services: $list. Also listed: $also. These are website values, not database counts.",
                'FR' => "Pas de table services en base. Le site met en avant $n services : $list. Aussi : $also. Contenu du site, pas un COUNT SQL.",
                'KINY' => "Nta table ya serivisi muri database. Urubuga rushimangira serivisi $n: $list. Hari kandi: $also. Ni ibivugwa ku rubuga, si COUNT.",
            ][$lang];
        case 'partners':
            $n = count($snap['website']['partners']);
            $list = implode('; ', $snap['website']['partners']);
            return [
                'EN' => "The CodeBridge database does not have a partners table. The official website currently lists $n partners: $list. That is website content, not a database count.",
                'FR' => "Pas de table partenaires en base. Le site liste $n partenaires : $list.",
                'KINY' => "Nta table y'abafatanyabikorwa muri database. Urubuga rutanga $n: $list.",
            ][$lang];
        case 'team':
            $n = count($snap['website']['team']);
            $list = implode('; ', $snap['website']['team']);
            return [
                'EN' => "The CodeBridge database does not have a team table. The About page currently lists $n team members: $list. That is website content, not a database count.",
                'FR' => "Pas de table équipe en base. La page À propos liste $n personnes : $list.",
                'KINY' => "Nta table y'ikipe muri database. Paji About irerekana $n: $list.",
            ][$lang];
        case 'stories':
            $n = (int)$snap['website']['success_stories_on_homepage'];
            return [
                'EN' => "There is no testimonials table in the database. The homepage currently shows $n successful stories. That is website content, not a database count.",
                'FR' => "Pas de table témoignages en base. L’accueil affiche actuellement $n success stories.",
                'KINY' => "Nta table y'ubuhamya muri database. Ahabanza hagaragaza inkuru $n z'intsinzi.",
            ][$lang];
        case 'gallery':
            return [
                'EN' => 'CodeBridge — published gallery photos, database table codebridge_gallery: ' . cb_num($snap['gallery']['published']) . '.',
                'FR' => 'Photos de galerie publiées (codebridge_gallery) : ' . cb_num($snap['gallery']['published']) . '.',
                'KINY' => 'Amafoto ya gallery yasohotse (codebridge_gallery): ' . cb_num($snap['gallery']['published']) . '.',
            ][$lang];
        case 'jobs':
            $n = $snap['jobs']['active_total'];
            return [
                'EN' => 'CodeBridge — active listings in database table codebridge_jobs: ' . cb_num($n) . ' (jobs: ' . cb_num($snap['jobs']['active_jobs']) . ', internships: ' . cb_num($snap['jobs']['active_internships']) . '). The careers page may still show static demo cards when this table is empty.',
                'FR' => 'Offres actives (codebridge_jobs) : ' . cb_num($n) . '. Si la table est vide, la page carrières peut encore afficher des cartes de démonstration.',
                'KINY' => 'Amahugurwa n\'akazi bikora muri codebridge_jobs: ' . cb_num($n) . '.',
            ][$lang];
        case 'applications':
            if (empty($snap['applications'])) {
                return 'You don\'t have permission to access that information.';
            }
            $a = $snap['applications'];
            return 'CodeBridge — job applications (database table codebridge_job_applications, counts only). Period: ' . $pl . '. Total stored: ' . cb_num($a['total']) . '. This month: ' . cb_num($a['this_month']) . '. No names, emails or files are included.';
        default:
            return null;
    }
}

function cb_off_topic_reply(): string
{
    return "I'm the CodeBridge AI assistant, so I mainly help with CodeBridge and its platform. What would you like to know about CodeBridge?";
}

function cb_permission_reply(): string
{
    return "You don't have permission to access that information.";
}
