<?php
declare(strict_types=1);

$cfgFile = __DIR__ . '/typo3conf/LocalConfiguration.php';
if (!is_file($cfgFile)) {
    http_response_code(503);
    echo 'News is temporarily unavailable.';
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
    http_response_code(503);
    echo 'News is temporarily unavailable.';
    exit;
}
$mysqli->set_charset('utf8mb4');

$slug = trim((string)($_GET['slug'] ?? ''));
$now = time();
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 9;

function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function news_image(?string $identifier): string
{
    if (!$identifier) {
        return '/images/handsb.png';
    }
    return '/fileadmin' . (str_starts_with($identifier, '/') ? $identifier : '/' . ltrim($identifier, '/'));
}

$sql = "SELECT n.uid, n.title, n.teaser, n.bodytext, n.path_segment, n.datetime,
               f.identifier AS file_identifier
        FROM tx_news_domain_model_news n
        LEFT JOIN sys_file_reference r
            ON r.uid_foreign = n.uid
           AND r.tablenames = 'tx_news_domain_model_news'
           AND r.fieldname = 'fal_media'
           AND r.deleted = 0 AND r.hidden = 0
        LEFT JOIN sys_file f ON f.uid = r.uid_local
        WHERE n.deleted = 0 AND n.hidden = 0
          AND (n.sys_language_uid = 0 OR n.sys_language_uid IS NULL)
          AND (n.starttime = 0 OR n.starttime <= ?)
          AND (n.endtime = 0 OR n.endtime > ?)
        ORDER BY n.datetime DESC, n.uid DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ii', $now, $now);
$stmt->execute();
$res = $stmt->get_result();
$all = [];
$seen = [];
while ($row = $res->fetch_assoc()) {
    $uid = (int)$row['uid'];
    if (isset($seen[$uid])) {
        continue;
    }
    $seen[$uid] = true;
    $path = trim((string)$row['path_segment']) ?: ('article-' . $uid);
    $all[] = [
        'uid' => $uid,
        'title' => (string)$row['title'],
        'teaser' => trim(html_entity_decode(strip_tags((string)$row['teaser']), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
        'body' => (string)$row['bodytext'],
        'slug' => $path,
        'datetime' => (int)$row['datetime'],
        'image' => news_image($row['file_identifier'] ?? null),
    ];
}

$article = null;
if ($slug !== '') {
    foreach ($all as $item) {
        if ($item['slug'] === $slug) {
            $article = $item;
            break;
        }
    }
    if (!$article) {
        http_response_code(404);
    }
}

$total = count($all);
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);
$list = $article ? [] : array_slice($all, ($page - 1) * $perPage, $perPage);
$title = $article ? ($article['title'] . ' | Codebridge') : 'News | Codebridge';
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($title) ?></title>
<link rel="icon" type="image/png" sizes="32x32" href="/images/icon-32.png?v=round-1">
<link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192.png?v=round-1">
<link rel="apple-touch-icon" href="/images/apple-touch-icon.png?v=round-1">
<link rel="icon" href="/favicon.ico?v=round-1" sizes="any">
<link rel="stylesheet" href="/style.css?v=nav-logo-1">
<link rel="stylesheet" href="/cb-final.css?v=nav-logo-1">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
.news-page { padding: 140px 0 80px; }
.news-page-header { max-width: 820px; margin: 0 auto 48px; text-align: center; }
.news-page-header h1 { font-size: 42px; margin: 0 0 12px; }
.news-page-header p { color: var(--cb-text-muted, #5b6573); }
.news-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
@media (max-width: 980px) { .news-list { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .news-list { grid-template-columns: 1fr; } }
.news-article { max-width: 820px; margin: 0 auto; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,.06); }
.news-article img { width: 100%; height: 360px; object-fit: cover; display: block; }
.news-article-body { padding: 36px; }
.news-article-body .news-date { color: var(--cb-text-muted, #5b6573); margin-bottom: 10px; }
.news-article-body h1 { margin: 0 0 18px; font-size: 34px; }
.news-article-body .prose { line-height: 1.7; color: #243042; }
.news-article-body .prose p { margin: 0 0 1em; }
.news-back { display: inline-block; margin-bottom: 18px; font-weight: 700; color: var(--cb-red, #ee3a2f); text-decoration: none; }
.news-pager { display: flex; justify-content: center; gap: 10px; margin-top: 40px; }
.news-pager a, .news-pager span { padding: 8px 14px; border-radius: 999px; text-decoration: none; color: inherit; border: 1px solid #d8deea; }
.news-pager .is-current { background: var(--cb-navy, #0b1b3a); color: #fff; border-color: transparent; }
.news-empty { text-align: center; padding: 48px 16px; color: var(--cb-text-muted, #5b6573); }
</style>
</head>
<body>
<nav class="site-nav" aria-label="Main navigation">
  <div class="site-nav-pill">
    <a href="index.html" class="site-nav-logo" aria-label="Codebridge home">
      <img src="images/logo.png" alt="Codebridge logo" />
    </a>
    <div class="site-nav-links">
      <a href="index.html" data-i18n="nav.home">Home</a>
      <div class="nav-item">
        <div class="nav-item-row">
          <a href="about.html" class="nav-item-link" data-i18n="nav.about">About us</a>
          <button class="nav-item-caret" aria-label="Toggle About us submenu" aria-expanded="false">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
        </div>
        <div class="nav-dropdown">
          <a href="about.html#mission-vision" data-i18n="nav.about.mission">Mission &amp; vision</a>
          <a href="about.html#journey" data-i18n="nav.about.journey">Our journey</a>
          <a href="about.html#team" data-i18n="nav.about.team">Our team</a>
        </div>
      </div>
      <div class="nav-item">
        <div class="nav-item-row">
          <a href="services.html" class="nav-item-link" data-i18n="nav.services">Services</a>
          <button class="nav-item-caret" aria-label="Toggle Services submenu" aria-expanded="false">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
        </div>
        <div class="nav-dropdown">
          <a href="services.html#cybersecurity" data-i18n="nav.services.cyber">Cybersecurity</a>
          <a href="services.html#ai-ml" data-i18n="nav.services.ai">AI &amp; Machine Learning</a>
          <a href="services.html#consultancy" data-i18n="nav.services.consultancy">Consultancy &amp; Strategy</a>
          <a href="services.html#internship" data-i18n="nav.services.internship">Internship Program</a>
          <a href="services.html#ikiraro-hub" data-i18n="nav.services.ikiraroHub">IKiraro Innovation Hub</a>
          <a href="https://ikiraro-elearrning.vercel.app/" target="_blank" rel="noopener noreferrer" data-i18n="nav.services.ikiraroElearning">IKiraro E-Learning</a>
        </div>
      </div>
      <div class="nav-item">
        <div class="nav-item-row">
          <a href="careers.html" class="nav-item-link" data-i18n="nav.careers">Careers</a>
          <button class="nav-item-caret" aria-label="Toggle Careers submenu" aria-expanded="false">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
        </div>
        <div class="nav-dropdown">
          <a href="careers.html#job-opportunities" data-i18n="nav.careers.jobs">Job opportunities</a>
          <a href="careers.html#internships" data-i18n="nav.careers.internships">Internship programs</a>
        </div>
      </div>
      <div class="nav-item">
        <div class="nav-item-row">
          <a href="innovations.html" class="nav-item-link" data-i18n="nav.innovations">Innovations</a>
          <button class="nav-item-caret" aria-label="Toggle Innovations submenu" aria-expanded="false">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
        </div>
        <div class="nav-dropdown">
          <a href="innovations.html#technical-reels" data-i18n="nav.innovations.reels">Technical reels</a>
          <a href="innovations.html#innovation-in-action" data-i18n="nav.innovations.action">Innovation in action</a>
          <a href="innovations.html#featured-projects" data-i18n="nav.innovations.projects">Featured projects</a>
        </div>
      </div>
      <a href="/news" class="active" data-i18n="nav.blog">News</a>
      <a href="gallery.html" data-i18n="nav.gallery">Gallery</a>
    </div>
    <div class="site-nav-lang-wrap">
      <button class="site-nav-lang" aria-label="Language: English" aria-expanded="false">EN</button>
      <div class="site-nav-lang-menu" role="menu">
        <button class="site-nav-lang-option active" data-value="EN" data-label="English" role="menuitem">English</button>
        <button class="site-nav-lang-option" data-value="FR" data-label="Français" role="menuitem">Français</button>
        <button class="site-nav-lang-option" data-value="KINY" data-label="Kinyarwanda" role="menuitem">Kinyarwanda</button>
      </div>
    </div>
    <button class="site-nav-toggle" aria-label="Open menu">☰</button>
  </div>
  <a href="contact.html" class="site-nav-contact" data-i18n="nav.contact">Contact us</a>
</nav>
<main class="news-page">
  <div class="container">
<?php if ($slug !== '' && !$article): ?>
    <div class="news-empty">
      <h1 data-i18n="news.notFound.title">Article not found</h1>
      <p data-i18n="news.notFound.body">This news article is unpublished or does not exist.</p>
      <p><a class="news-back" href="/news" data-i18n="news.backToNews">&larr; Back to news</a></p>
    </div>
<?php elseif ($article): ?>
    <article class="news-article">
      <img src="<?= h($article['image']) ?>" alt="">
      <div class="news-article-body">
        <a class="news-back" href="/news" data-i18n="news.allNews">&larr; All news</a>
        <div class="news-date"><?= h(date('l, F j Y', $article['datetime'] ?: $now)) ?></div>
        <h1><?= h($article['title']) ?></h1>
        <div class="prose"><?= $article['body'] !== '' ? $article['body'] : '<p>' . h($article['teaser']) . '</p>' ?></div>
      </div>
    </article>
<?php else: ?>
    <header class="news-page-header">
      <h1 data-i18n="news.header.title">Company <span class="accent" data-i18n="news.header.accent">news</span></h1>
      <p data-i18n="news.header.subtitle">Updates from Codebridge &mdash; published through the TYPO3 backend and shown here automatically.</p>
    </header>
    <?php if (!$list): ?>
      <div class="news-empty" data-i18n="news.empty">No published articles yet.</div>
    <?php else: ?>
      <div class="news-list">
        <?php foreach ($list as $i => $item): ?>
        <article class="news-card<?= $i === 0 && $page === 1 ? ' news-card--hot' : '' ?>">
          <div class="news-card-img">
            <?php if ($i === 0 && $page === 1): ?><span class="trending-badge" data-i18n="news.latestBadge">Latest</span><?php endif; ?>
            <img src="<?= h($item['image']) ?>" alt="">
          </div>
          <div class="news-card-body">
            <div class="news-date"><?= h(date('l, F j Y', $item['datetime'] ?: $now)) ?></div>
            <h3 class="news-title"><?= h($item['title']) ?></h3>
            <p class="news-excerpt"><?= h($item['teaser']) ?></p>
            <a href="/news/<?= h(rawurlencode($item['slug'])) ?>" class="read-more">
              <span data-i18n="news.readArticle">Read article</span> <span>&rarr;</span>
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php if ($pages > 1): ?>
      <nav class="news-pager" aria-label="News pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <?php if ($i === $page): ?><span class="is-current"><?= $i ?></span>
          <?php else: ?><a href="/news?page=<?= $i ?>"><?= $i ?></a><?php endif; ?>
        <?php endfor; ?>
      </nav>
      <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
  </div>
</main>

<footer class="site-footer">
  <div class="footer-map-bg" aria-hidden="true"></div>
  <div class="container footer-inner">
    <div class="footer-col footer-col--brand">
      <div class="footer-logo"><img src="/images/logow.png" alt="Codebridge"></div>
      <p class="footer-tagline" data-i18n="footer.tagline">We develop, secure, and deploy cutting-edge digital solutions.</p>
      <div class="footer-social">
        <a href="#" aria-label="Facebook">f</a>
        <a href="#" aria-label="X / Twitter">X</a>
        <a href="#" aria-label="LinkedIn">in</a>
        <a href="#" aria-label="YouTube">&#9654;</a>
      </div>
    </div>
    <div class="footer-col footer-col--links">
      <div class="footer-heading" data-i18n="footer.quicklink">Quick Link</div>
      <ul>
        <li><a href="/about.html"><span class="footer-chevron" aria-hidden="true">&raquo;</span><span data-i18n="nav.about">About us</span></a></li>
        <li><a href="/services.html"><span class="footer-chevron" aria-hidden="true">&raquo;</span><span data-i18n="nav.services">Services</span></a></li>
        <li><a href="/innovations.html"><span class="footer-chevron" aria-hidden="true">&raquo;</span><span data-i18n="nav.innovations">Innovations</span></a></li>
        <li><a href="/news"><span class="footer-chevron" aria-hidden="true">&raquo;</span><span data-i18n="nav.blog">News</span></a></li>
        <li><a href="/careers.html"><span class="footer-chevron" aria-hidden="true">&raquo;</span><span data-i18n="nav.careers">Careers</span></a></li>
      </ul>
    </div>
    <div class="footer-col footer-col--contact">
      <div class="footer-heading" data-i18n="footer.contact_us">Contact Us</div>
      <div class="footer-info-row">
        <span class="footer-info-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </span>
        <span data-i18n="footer.address">Kigali &mdash; Gasabo, Kabuga (Rusoro), 26G9+6H9</span>
      </div>
      <div class="footer-info-row">
        <span class="footer-info-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
        </span>
        <div>
          <strong data-i18n="footer.hours_label">Opening Hours:</strong>
          <span data-i18n="footer.hours">Mon&ndash;Fri: 8:00 AM &ndash; 5:00 PM, Sat &amp; Sun: 12:00 PM &ndash; 5:00 PM</span>
        </div>
      </div>
      <div class="footer-info-row">
        <span class="footer-info-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </span>
        <div>
          <strong data-i18n="footer.phone_label">Phone Call:</strong>
          <span>+250 788 288 546</span>
        </div>
      </div>
      <div class="footer-info-row">
        <span class="footer-info-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 6.5l9 6 9-6"/></svg>
        </span>
        <div>
          <strong data-i18n="footer.email_label">Email:</strong>
          <span>info@codebrige.rw</span>
        </div>
      </div>
    </div>
    <div class="footer-col footer-col--map">
      <div class="footer-heading" data-i18n="footer.find_us">Find Us</div>
      <div class="footer-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m3!2m1!1sLight+Church+Kabuga,26G9%2B6H9,Kabuga!6i16" title="Codebridge HQ location map" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" aria-label="Map showing Codebridge HQ in Kabuga (Rusoro), Kigali"></iframe>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="footer-bottom">
      <p class="footer-copyright" data-i18n="footer.copyright">&copy; All rights reserved <?= date('Y') ?> by Codebridge.</p>
      <a href="/privacy-policy.html" class="footer-privacy-link" data-i18n="footer.privacy_policy">Privacy Policy</a>
    </div>
  </div>
</footer>
    <button class="hero-ai-btn" aria-label="AI assistant" type="button">
  <img src="chat.png" alt="AI assistant" class="hero-ai-icon" />
</button>
<script src="/i18n-extra.js?v=db-ai-1"></script>
<script src="/i18n.js"></script>
<script src="/lang-switcher.js"></script>
<script src="/cb-enhancements.js?v=preloader-1"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true });</script>
</body>
</html>