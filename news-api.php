<?php
/**
 * Public JSON feed of published TYPO3 news (ext:news).
 * Same source as /news and the homepage carousel.
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60');

$limit = isset($_GET['limit']) ? max(1, min(50, (int)$_GET['limit'])) : 12;
$slug = trim((string)($_GET['slug'] ?? ''));

$cfgFile = __DIR__ . '/typo3conf/LocalConfiguration.php';
if (!is_file($cfgFile)) {
    echo json_encode(['success' => false, 'posts' => [], 'error' => 'cms_unavailable']);
    exit;
}

$cfg = include $cfgFile;
$db = $cfg['DB']['Connections']['Default'] ?? [];
mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = @new mysqli(
    (string)($db['host'] ?? 'localhost'),
    (string)($db['user'] ?? ''),
    (string)($db['password'] ?? ''),
    (string)($db['dbname'] ?? ''),
    (int)($db['port'] ?? 3306)
);
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'posts' => [], 'error' => 'db_unavailable']);
    exit;
}
$mysqli->set_charset('utf8mb4');

$now = time();
$sql = "SELECT n.uid, n.title, n.teaser, n.bodytext, n.path_segment, n.datetime, n.fal_media,
               f.identifier AS file_identifier
        FROM tx_news_domain_model_news n
        LEFT JOIN sys_file_reference r
            ON r.uid_foreign = n.uid
           AND r.tablenames = 'tx_news_domain_model_news'
           AND r.fieldname = 'fal_media'
           AND r.deleted = 0
           AND r.hidden = 0
        LEFT JOIN sys_file f ON f.uid = r.uid_local
        WHERE n.deleted = 0
          AND n.hidden = 0
          AND (n.sys_language_uid = 0 OR n.sys_language_uid IS NULL)
          AND (n.starttime = 0 OR n.starttime <= ?)
          AND (n.endtime = 0 OR n.endtime > ?)
        ORDER BY n.datetime DESC, n.uid DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ii', $now, $now);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
$seen = [];
while ($row = $result->fetch_assoc()) {
    $uid = (int)$row['uid'];
    if (isset($seen[$uid])) {
        continue;
    }
    $seen[$uid] = true;
    $path = trim((string)$row['path_segment']);
    if ($path === '') {
        $path = 'article-' . $uid;
    }
    $identifier = (string)($row['file_identifier'] ?? '');
    $image = '';
    if ($identifier !== '') {
        $image = '/fileadmin' . (str_starts_with($identifier, '/') ? $identifier : '/' . ltrim($identifier, '/'));
    }
    $posts[] = [
        'uid' => $uid,
        'title' => (string)$row['title'],
        'excerpt' => trim(html_entity_decode(strip_tags((string)$row['teaser']), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
        'body' => (string)$row['bodytext'],
        'slug' => $path,
        'url' => '/news/' . rawurlencode($path),
        'date' => date('l, F j Y', (int)$row['datetime'] ?: $now),
        'datetime' => (int)$row['datetime'],
        'image' => $image,
        'badge' => count($posts) === 0 ? 'Latest' : '',
    ];
}

if ($slug !== '') {
    $match = null;
    foreach ($posts as $post) {
        if ($post['slug'] === $slug) {
            $match = $post;
            break;
        }
    }
    echo json_encode(['success' => (bool)$match, 'post' => $match, 'posts' => $match ? [$match] : []], JSON_UNESCAPED_UNICODE);
    exit;
}

$posts = array_slice($posts, 0, $limit);
echo json_encode(['success' => true, 'posts' => $posts], JSON_UNESCAPED_UNICODE);
