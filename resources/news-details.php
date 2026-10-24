<?php
// resources/news-details.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/dbConfig.php';

// ── Image base URL: CRM project serves the uploaded images ──
define('NEWS_IMG_BASE', 'http://localhost/crm.logintoloans/gallery/');

// Validate ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: news.php");
    exit;
}

// Now include navbar after all redirects
include '../includes/navbar.php';

// Fetch news article
$news_result = mysqli_query($conn, "SELECT * FROM tbl_uploadnews WHERE id = $id AND status = 'active'");
$news = mysqli_fetch_assoc($news_result);

if (!$news) {
    header("Location: news.php");
    exit;
}

// Increment views
mysqli_query($conn, "UPDATE tbl_uploadnews SET views = views + 1 WHERE id = $id");

// Category labels
$cat_labels = [
    'market' => 'Markets',
    'economy' => 'Economy',
    'insurance' => 'Insurance',
    'sip' => 'SIP & MF',
    'rbi' => 'RBI & Policy',
];

$cat_key = strtolower($news['category'] ?? 'market');
$cat_label = $cat_labels[$cat_key] ?? ucfirst($news['category']);

function get_news_thumb($row) {
    if (empty($row['thumbnail_path'])) return '';
    $path = trim($row['thumbnail_path']);
    if (preg_match('/^https?:\/\//', $path)) return $path;
    return NEWS_IMG_BASE . ltrim($path, '/');
}

function time_ago($dt) {
    if (empty($dt)) return 'Just now';
    $diff = time() - strtotime($dt);
    if ($diff < 60) return $diff . ' seconds ago';
    if ($diff < 3600) return round($diff / 60) . ' minutes ago';
    if ($diff < 86400) return round($diff / 3600) . ' hours ago';
    if ($diff < 604800) return round($diff / 86400) . ' days ago';
    return date('M d, Y', strtotime($dt));
}

// Fetch related news articles
$rel_result = mysqli_query($conn, "SELECT id, title, excerpt, author, created_at, read_time, thumbnail_path, category, is_breaking 
    FROM tbl_uploadnews WHERE status='active' AND category = '" . mysqli_real_escape_string($conn, $news['category']) . "' AND id != $id 
    ORDER BY created_at DESC LIMIT 3");
$related_news = [];
while ($row = mysqli_fetch_assoc($rel_result)) $related_news[] = $row;

if (empty($related_news)) {
    $rel_result = mysqli_query($conn, "SELECT id, title, excerpt, author, created_at, read_time, thumbnail_path, category, is_breaking 
        FROM tbl_uploadnews WHERE status='active' AND id != $id ORDER BY created_at DESC LIMIT 3");
    while ($row = mysqli_fetch_assoc($rel_result)) $related_news[] = $row;
}

// Fetch recent news for sidebar
$recent_result = mysqli_query($conn, "SELECT id, title, category, author, created_at, thumbnail_path 
    FROM tbl_uploadnews WHERE status='active' AND id != $id ORDER BY created_at DESC LIMIT 5");
$recent_news = [];
while ($row = mysqli_fetch_assoc($recent_result)) $recent_news[] = $row;

// Fetch categories with counts
$cats_result = mysqli_query($conn, "SELECT category, COUNT(*) as cnt FROM tbl_uploadnews WHERE status='active' GROUP BY category ORDER BY cnt DESC");
$categories = [];
while ($row = mysqli_fetch_assoc($cats_result)) $categories[] = $row;

$share_url = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$share_title = urlencode($news['title']);
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?> - LoginToLoans News</title>
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($news['excerpt'] ?? $news['content']), 0, 160)); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($news['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars(substr(strip_tags($news['excerpt'] ?? $news['content']), 0, 160)); ?>">
    <meta property="og:image" content="<?php echo get_news_thumb($news); ?>">
    <meta property="og:type" content="article">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,700;0,9..144,900;1,9..144,400;1,9..144,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>

/* ══════════════════════════════════════════════════════════
   NEWS DETAILS — PREMIUM ORANGE × NAVY  |  DARK/LIGHT MODE
══════════════════════════════════════════════════════════ */

:root,
[data-theme="light"] {
  --bg: #fafbfe;
  --bg-card: #ffffff;
  --bg-muted: #f2f5fc;
  --text-1: #071229;
  --text-2: #2a3550;
  --text-3: #5a6a8a;
  --text-4: #8a9ab8;
  --border: rgba(17,38,84,.10);
  --border-2: rgba(17,38,84,.16);
  --orange-600: #e84200;
  --orange-500: #f55a1a;
  --orange-400: #ff6b2b;
  --orange-300: #ff8c55;
  --orange-100: #ffe4d6;
  --orange-50: #fff5f0;
  --prose-h: #071229;
  --prose-body: #3e4e6e;
  --shadow-sm: 0 4px 16px rgba(7,18,41,.08);
  --shadow-md: 0 8px 32px rgba(7,18,41,.10);
  --shadow-lg: 0 20px 60px rgba(7,18,41,.14);
  --shadow-orange: 0 8px 32px rgba(245,90,26,.25);
}

[data-theme="dark"] {
  --bg: #060d1e;
  --bg-card: #0c1630;
  --bg-muted: #101d38;
  --text-1: #e8f0fc;
  --text-2: #b8cef0;
  --text-3: #7090c0;
  --text-4: #4a6090;
  --border: rgba(184,206,240,.08);
  --orange-50: rgba(245,90,26,.1);
  --prose-h: #e8f0fc;
  --prose-body: #8aabce;
  --shadow-sm: 0 4px 16px rgba(0,0,0,.35);
  --shadow-md: 0 8px 32px rgba(0,0,0,.40);
  --shadow-lg: 0 20px 60px rgba(0,0,0,.50);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'Sora', system-ui, sans-serif;
  background: var(--bg);
  color: var(--text-1);
  overflow-x: hidden;
  transition: background .35s, color .35s;
}
img { display: block; max-width: 100%; }
a { text-decoration: none; color: inherit; }
button { cursor: pointer; border: none; background: none; }

/* Progress Bar */
.progress-bar {
  position: fixed;
  top: 0; left: 0;
  width: 0%;
  height: 3px;
  background: linear-gradient(90deg, var(--orange-400), var(--orange-600));
  z-index: 10000;
  transition: width .1s linear;
}

/* Theme Toggle */
.theme-toggle {
  position: fixed;
  top: 50%;
  right: 28px;
  transform: translateY(-50%);
  z-index: 900;
}
.ft-btn {
  width: 46px; height: 46px;
  border-radius: 50%;
  background: var(--bg-card);
  border: 1px solid var(--border-2);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all .28s;
}
.ft-btn:hover {
  background: linear-gradient(135deg, var(--orange-500), var(--orange-400));
  color: #fff;
  transform: scale(1.12);
}
.toggle-track {
  width: 28px; height: 16px;
  background: rgba(0,0,0,.2);
  border-radius: 8px;
  position: relative;
}
.toggle-thumb {
  width: 12px; height: 12px;
  background: #fff;
  border-radius: 50%;
  position: absolute;
  top: 2px; left: 2px;
  transition: transform .3s;
}
[data-theme="dark"] .toggle-thumb { transform: translateX(12px); }
[data-theme="dark"] .toggle-track { background: rgba(245,90,26,.5); }

.theme-toggle-mobile { display: none; position: fixed; bottom: 24px; right: 20px; z-index: 900; }
@media (max-width: 860px) { .theme-toggle-mobile { display: block; } .theme-toggle { display: none; } }
.theme-fab {
  width: 50px; height: 50px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--orange-500), var(--orange-400));
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  cursor: pointer;
  box-shadow: var(--shadow-orange);
}

/* Breaking Badge */
.breaking-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(220,38,38,.9);
  backdrop-filter: blur(8px);
  border-radius: 50px;
  padding: 6px 16px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: .18em;
  color: #fff;
  text-transform: uppercase;
  margin-bottom: 18px;
}
.breaking-hero-badge i { font-size: 10px; animation: pulse 1.5s infinite; }
@keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .5; } }

/* News Hero */
.news-hero {
  position: relative;
  overflow: hidden;
  height: clamp(480px, 62vh, 700px);
  background: #040d1f;
}
.news-hero-img {
  position: absolute;
  inset: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  filter: brightness(.55);
}
.news-hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(175deg, rgba(4,13,31,.08) 0%, rgba(4,13,31,.35) 40%, rgba(4,13,31,.94) 100%);
}
.news-hero::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--orange-500), var(--orange-300), transparent);
}
.news-hero-content {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  padding: 52px 68px 56px;
  z-index: 3;
}
.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  color: rgba(255,255,255,.45);
  font-size: 9.5px;
  font-weight: 600;
  letter-spacing: .2em;
  text-transform: uppercase;
  margin-bottom: 26px;
  padding: 8px 16px;
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 50px;
  backdrop-filter: blur(8px);
}
.back-btn:hover { color: #fff; border-color: rgba(255,107,43,.4); background: rgba(255,107,43,.1); }
.hero-cat {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(4,13,31,.55);
  backdrop-filter: blur(14px);
  border: 1px solid rgba(255,107,43,.22);
  border-radius: 50px;
  padding: 7px 18px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: .16em;
  color: var(--orange-200);
  margin-bottom: 20px;
}
.hero-cat-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--orange-400);
  animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot { 0%,100% { opacity: 1; } 50% { opacity: .4; } }
.hero-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(28px, 4.2vw, 58px);
  font-weight: 800;
  color: #fff;
  line-height: 1.1;
  margin-bottom: 22px;
}
.hero-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 18px;
  font-size: 12.5px;
  color: rgba(255,255,255,.45);
}
.hero-meta-author { display: flex; align-items: center; gap: 10px; }
.hero-ava {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  color: #fff;
}
.views-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(4,13,31,.5);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,107,43,.15);
  border-radius: 50px;
  padding: 5px 14px;
  font-size: 11px;
}

/* Article Layout */
.article-wrap {
  max-width: 1360px;
  margin: 0 auto;
  padding: 72px 68px 96px;
  display: grid;
  grid-template-columns: 1fr 348px;
  gap: 76px;
}
.article-lead {
  font-family: 'Fraunces', serif;
  font-size: 20px;
  font-style: italic;
  color: var(--text-2);
  border-left: 3.5px solid var(--orange-500);
  padding: 18px 0 18px 26px;
  margin-bottom: 48px;
  background: linear-gradient(90deg, var(--orange-50), transparent 60%);
}
.prose {
  font-size: 17px;
  line-height: 1.95;
  color: var(--prose-body);
}
.prose h2 {
  font-size: 30px;
  font-family: 'Fraunces', serif;
  color: var(--prose-h);
  margin: 2em 0 .8em;
  padding-bottom: 14px;
  border-bottom: 1px solid var(--border);
}
.prose p { margin-bottom: 1.7em; }
.prose img {
  border-radius: 16px;
  margin: 2em 0;
  width: 100%;
  cursor: zoom-in;
}
.prose blockquote {
  border-left: 4px solid var(--orange-500);
  background: var(--orange-50);
  padding: 22px 26px;
  margin: 2em 0;
  font-style: italic;
}
.article-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 9px;
  margin: 52px 0 36px;
}
.tag-chip {
  background: var(--orange-50);
  border: 1px solid rgba(245,90,26,.18);
  color: var(--orange-600);
  font-size: 12px;
  font-weight: 600;
  padding: 7px 16px;
  border-radius: 50px;
  transition: all .25s;
}
.tag-chip:hover {
  background: var(--orange-500);
  color: #fff;
  transform: translateY(-2px);
}
.share-bar {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 26px 30px;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 18px;
  margin-bottom: 44px;
}
.share-label {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: .2em;
  color: var(--orange-500);
}
.share-btns { display: flex; gap: 9px; flex-wrap: wrap; }
.share-btn {
  padding: 10px 20px;
  border-radius: 50px;
  font-size: 12.5px;
  font-weight: 700;
  transition: all .28s;
}
.share-btn:hover { transform: translateY(-2px); }
.share-wa { background: #25d366; color: #fff; }
.share-fb { background: #1877f2; color: #fff; }
.share-tw { background: #000; color: #fff; }
.share-copy {
  background: var(--orange-50);
  color: var(--orange-500);
  border: 1.5px solid rgba(245,90,26,.2);
}
.share-copy:hover { background: var(--orange-500); color: #fff; }
.author-box {
  margin-top: 0;
  background: linear-gradient(145deg, #071229, #040d1f);
  border-radius: 24px;
  padding: 36px;
}
.ab-inner {
  display: flex;
  gap: 22px;
  flex-wrap: wrap;
}
.ab-ava {
  width: 68px; height: 68px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  font-weight: 800;
  color: #fff;
}
.ab-name {
  font-family: 'Fraunces', serif;
  font-size: 22px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 10px;
}
.ab-bio {
  font-size: 13.5px;
  color: rgba(255,255,255,.5);
  line-height: 1.8;
}

/* Sidebar */
.sidebar { position: relative; }
.sb-sticky { position: sticky; top: 90px; display: flex; flex-direction: column; gap: 32px; }
.sb-head {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 1px solid var(--border);
  position: relative;
}
.sb-head::after {
  content: '';
  position: absolute;
  bottom: -1px; left: 0;
  width: 40px; height: 2px;
  background: var(--orange-500);
}
.sb-icon {
  width: 32px; height: 32px;
  border-radius: 9px;
  background: var(--orange-50);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--orange-500);
}
.sb-title { font-family: 'Fraunces', serif; font-size: 21px; font-weight: 700; }
.recent-list { display: flex; flex-direction: column; gap: 12px; }
.recent-item {
  display: grid;
  grid-template-columns: 74px 1fr;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
  transition: all .28s;
  text-decoration: none;
}
.recent-item:hover { transform: translateX(5px); border-color: var(--orange-500); }
.ri-thumb { overflow: hidden; height: 74px; }
.ri-thumb img { width: 100%; height: 100%; object-fit: cover; }
.ri-body { padding: 11px 14px; }
.ri-cat {
  font-size: 8px;
  font-weight: 700;
  letter-spacing: .12em;
  color: var(--orange-500);
  margin-bottom: 3px;
}
.ri-title {
  font-family: 'Fraunces', serif;
  font-size: 13.5px;
  font-weight: 600;
  color: var(--text-1);
  line-height: 1.3;
}
.ri-date { font-size: 10px; color: var(--text-4); margin-top: 4px; }
.cat-list { display: flex; flex-direction: column; gap: 7px; }
.cat-item {
  display: flex;
  justify-content: space-between;
  padding: 11px 16px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 12px;
  cursor: pointer;
}
.cat-item:hover { background: var(--orange-50); transform: translateX(4px); }
.ci-left { display: flex; align-items: center; gap: 10px; font-size: 13px; }
.ci-left i { color: var(--orange-400); }
.ci-count {
  font-size: 10px;
  font-weight: 700;
  background: var(--orange-50);
  padding: 2px 10px;
  border-radius: 50px;
}
.nl-box {
  background: linear-gradient(145deg, #040d1f, #071229);
  border-radius: 22px;
  padding: 28px 24px;
}
.nl-icon {
  width: 48px; height: 48px;
  background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}
.nl-h { font-family: 'Fraunces', serif; font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.nl-p { font-size: 12.5px; color: rgba(255,255,255,.4); line-height: 1.75; margin-bottom: 18px; }
.nl-input {
  width: 100%;
  background: rgba(255,255,255,.07);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 10px;
  padding: 12px 15px;
  color: #fff;
  margin-bottom: 10px;
}
.nl-btn {
  width: 100%;
  background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 13px;
  font-weight: 700;
  cursor: pointer;
}
.related-section {
  background: var(--bg-muted);
  padding: 80px 68px 96px;
}
.related-inner { max-width: 1360px; margin: 0 auto; }
.related-head {
  display: flex;
  justify-content: space-between;
  margin-bottom: 40px;
  padding-bottom: 22px;
  border-bottom: 1px solid var(--border);
}
.related-eyebrow {
  font-size: 9.5px;
  font-weight: 700;
  letter-spacing: .22em;
  color: var(--orange-500);
  margin-bottom: 7px;
}
.related-title {
  font-family: 'Fraunces', serif;
  font-size: 30px;
  font-weight: 700;
}
.related-title em { color: var(--orange-500); font-style: italic; }
.related-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 26px;
}
.rel-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 22px;
  overflow: hidden;
  transition: all .35s;
  text-decoration: none;
}
.rel-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); }
.rel-thumb { height: 190px; overflow: hidden; position: relative; }
.rel-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .8s; }
.rel-card:hover .rel-thumb img { transform: scale(1.08); }
.rel-breaking {
  position: absolute;
  top: 12px; right: 12px;
  background: #dc2626;
  color: #fff;
  font-size: 7px;
  font-weight: 800;
  letter-spacing: .1em;
  padding: 3px 10px;
  border-radius: 50px;
}
.rel-body { padding: 22px 24px 26px; }
.rel-cat {
  font-size: 8.5px;
  font-weight: 800;
  letter-spacing: .14em;
  color: var(--orange-500);
  margin-bottom: 9px;
}
.rel-title {
  font-family: 'Fraunces', serif;
  font-size: 18px;
  font-weight: 600;
  color: var(--text-1);
  line-height: 1.3;
  margin-bottom: 11px;
}
.rel-excerpt {
  font-size: 13px;
  color: var(--text-3);
  line-height: 1.78;
  margin-bottom: 18px;
}
.rel-meta {
  font-size: 11px;
  color: var(--text-4);
  display: flex;
  align-items: center;
  gap: 5px;
}
.rel-cta {
  font-size: 10px;
  font-weight: 700;
  color: var(--orange-500);
  display: flex;
  align-items: center;
  gap: 5px;
}
.view-all-link {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  color: var(--orange-500);
  display: flex;
  align-items: center;
  gap: 5px;
}
.reveal { opacity: 0; transform: translateY(28px); transition: opacity .75s, transform .75s; }
.reveal.visible { opacity: 1; transform: none; }
.d1 { transition-delay: .1s; }
.d2 { transition-delay: .2s; }
.d3 { transition-delay: .3s; }

@media (max-width: 1200px) {
  .article-wrap { grid-template-columns: 1fr; gap: 52px; padding: 52px 40px 72px; }
  .sidebar { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; }
  .related-section { padding: 60px 40px 72px; }
}
@media (max-width: 860px) {
  .news-hero-content { padding: 36px 28px 42px; }
  .related-grid { grid-template-columns: 1fr; }
  .article-wrap { padding: 40px 22px 56px; }
  .related-section { padding: 44px 22px 56px; }
  .sidebar { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="progress-bar" id="progressBar"></div>

<div class="theme-toggle">
  <button class="ft-btn" onclick="toggleTheme()">
    <div class="toggle-track"><div class="toggle-thumb"></div></div>
  </button>
</div>

<div class="theme-toggle-mobile">
  <button class="theme-fab" onclick="toggleTheme()">
    <i class="fa-solid fa-circle-half-stroke"></i>
  </button>
</div>

<!-- Hero Section -->
<section class="news-hero">
  <?php $img_src = get_news_thumb($news); ?>
  <?php if (!empty($img_src)): ?>
    <img class="news-hero-img" src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" loading="eager">
  <?php else: ?>
    <div class="news-hero-img" style="background: linear-gradient(135deg, #1a3570, #0c1d3e);"></div>
  <?php endif; ?>
  <div class="news-hero-overlay"></div>
  <div class="news-hero-content">
    <div class="hero-inner-wrap">
      <a href="news.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i>&nbsp;Back to News</a>
      <?php if ($news['is_breaking']): ?>
        <div class="breaking-hero-badge"><i class="fa-solid fa-bolt"></i> Breaking News</div>
      <?php endif; ?>
      <div class="hero-cat"><div class="hero-cat-dot"></div><?php echo htmlspecialchars($cat_label); ?></div>
      <h1 class="hero-title"><?php echo htmlspecialchars($news['title']); ?></h1>
      <div class="hero-meta">
        <div class="hero-meta-author">
          <div class="hero-ava"><?php echo htmlspecialchars(substr($news['author'] ?? 'ND', 0, 2)); ?></div>
          <span><?php echo htmlspecialchars($news['author'] ?? 'News Desk'); ?></span>
        </div>
        <span><i class="fa-regular fa-calendar"></i>&nbsp;<?php echo date('d M Y', strtotime($news['created_at'])); ?></span>
        <?php if (!empty($news['read_time'])): ?>
          <span><i class="fa-solid fa-book-open"></i>&nbsp;<?php echo htmlspecialchars($news['read_time']); ?> read</span>
        <?php endif; ?>
        <span class="views-badge"><i class="fa-regular fa-eye"></i> <?php $v = (int)($news['views'] ?? 0) + 1; echo $v >= 1000 ? round($v/1000, 1).'K' : $v; ?> views</span>
      </div>
    </div>
  </div>
</section>

<!-- Article + Sidebar -->
<div class="article-wrap">

  <article id="articleBody">
    <?php if (!empty($news['excerpt'])): ?>
    <div class="article-lead reveal"><?php echo nl2br(htmlspecialchars($news['excerpt'])); ?></div>
    <?php endif; ?>

    <div class="prose reveal d1"><?php echo $news['content'] ?? '<p>No content available.</p>'; ?></div>

    <div class="article-tags reveal">
      <span class="tag-chip">#<?php echo htmlspecialchars(str_replace('-', ' ', $cat_key)); ?></span>
      <span class="tag-chip">#FinancialNews</span>
      <span class="tag-chip">#MarketUpdate</span>
    </div>

    <div class="share-bar reveal">
      <div class="share-label">Share this news</div>
      <div class="share-btns">
        <a class="share-btn share-wa" target="_blank" href="https://wa.me/?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
        <a class="share-btn share-fb" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
        <a class="share-btn share-tw" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>"><i class="fa-brands fa-x-twitter"></i> X</a>
        <button class="share-btn share-copy" onclick="copyLink()"><i class="fa-solid fa-copy"></i> Copy Link</button>
      </div>
    </div>

    <div class="author-box reveal">
      <div class="ab-inner">
        <div class="ab-ava"><?php echo htmlspecialchars(substr($news['author'] ?? 'ND', 0, 2)); ?></div>
        <div class="ab-info">
          <div class="ab-name"><?php echo htmlspecialchars($news['author'] ?? 'News Desk'); ?></div>
          <div class="ab-bio">A financial journalist at LoginToLoans, covering <?php echo htmlspecialchars($cat_label); ?> and economic policies. Committed to delivering accurate, timely financial news.</div>
        </div>
      </div>
    </div>
  </article>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sb-sticky">
      <?php if (!empty($recent_news)): ?>
      <div class="sb-widget reveal">
        <div class="sb-head"><div class="sb-icon"><i class="fa-solid fa-clock-rotate-left"></i></div><div class="sb-title">Recent News</div></div>
        <div class="recent-list">
          <?php foreach ($recent_news as $rn): ?>
          <a class="recent-item" href="news-details.php?id=<?php echo (int)$rn['id']; ?>">
            <div class="ri-thumb">
              <?php $rimg = get_news_thumb($rn); ?>
              <?php if (!empty($rimg)): ?>
                <img src="<?php echo htmlspecialchars($rimg); ?>" alt="<?php echo htmlspecialchars($rn['title']); ?>" loading="lazy">
              <?php else: ?>
                <div style="background: linear-gradient(135deg, #1a3570, #0c1d3e); width:100%; height:100%;"></div>
              <?php endif; ?>
            </div>
            <div class="ri-body">
              <div class="ri-cat"><?php echo htmlspecialchars($cat_labels[$rn['category']] ?? ucfirst($rn['category'])); ?></div>
              <div class="ri-title"><?php echo htmlspecialchars($rn['title']); ?></div>
              <div class="ri-date"><?php echo time_ago($rn['created_at']); ?></div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($categories)): ?>
      <div class="sb-widget reveal d1">
        <div class="sb-head"><div class="sb-icon"><i class="fa-solid fa-layer-group"></i></div><div class="sb-title">Categories</div></div>
        <div class="cat-list">
          <?php foreach ($categories as $cat): $ck = $cat['category']; $clbl = $cat_labels[$ck] ?? ucfirst($ck); ?>
          <div class="cat-item" onclick="window.location='news.php?cat=<?php echo htmlspecialchars($ck); ?>'">
            <div class="ci-left"><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($clbl); ?></div>
            <span class="ci-count"><?php echo (int)$cat['cnt']; ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="sb-widget reveal d2">
        <div class="nl-box">
          <div class="nl-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
          <div class="nl-h">Daily Newsletter</div>
          <div class="nl-p">Get the day's top financial news delivered to your inbox.</div>
          <input class="nl-input" type="email" placeholder="your@email.com">
          <button class="nl-btn"><i class="fa-solid fa-paper-plane"></i> Subscribe Free</button>
        </div>
      </div>
    </div>
  </aside>
</div>

<!-- Related News -->
<?php if (!empty($related_news)): ?>
<section class="related-section">
  <div class="related-inner">
    <div class="related-head">
      <div><div class="related-eyebrow">You Might Also Like</div><div class="related-title">More from <em><?php echo htmlspecialchars($cat_label); ?></em></div></div>
      <a href="news.php" class="view-all-link">View All &nbsp;<i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="related-grid">
      <?php foreach ($related_news as $i => $rn): ?>
      <a class="rel-card reveal <?php echo $i > 0 ? 'd'.($i+1) : ''; ?>" href="news-details.php?id=<?php echo (int)$rn['id']; ?>">
        <div class="rel-thumb">
          <?php $relimg = get_news_thumb($rn); ?>
          <?php if (!empty($relimg)): ?>
            <img src="<?php echo htmlspecialchars($relimg); ?>" alt="<?php echo htmlspecialchars($rn['title']); ?>" loading="lazy">
          <?php else: ?>
            <div style="background: linear-gradient(135deg, #1a3570, #0c1d3e); width:100%; height:100%;"></div>
          <?php endif; ?>
          <?php if ($rn['is_breaking']): ?>
            <div class="rel-breaking"><i class="fa-solid fa-bolt"></i> BREAKING</div>
          <?php endif; ?>
        </div>
        <div class="rel-body">
          <div class="rel-cat"><?php echo htmlspecialchars($cat_labels[$rn['category']] ?? ucfirst($rn['category'])); ?></div>
          <div class="rel-title"><?php echo htmlspecialchars($rn['title']); ?></div>
          <div class="rel-excerpt"><?php echo htmlspecialchars(substr($rn['excerpt'] ?? '', 0, 100)) . '...'; ?></div>
          <div class="rel-foot"><div class="rel-meta"><i class="fa-regular fa-clock"></i> <?php echo time_ago($rn['created_at']); ?></div><span class="rel-cta">Read &nbsp;<i class="fa-solid fa-arrow-right"></i></span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<script>
function toggleTheme() {
  const html = document.documentElement;
  const curr = html.getAttribute('data-theme') || 'light';
  const next = curr === 'light' ? 'dark' : 'light';
  html.setAttribute('data-theme', next);
  localStorage.setItem('ltl-theme', next);
}

(function() {
  const saved = localStorage.getItem('ltl-theme') || 'light';
  document.documentElement.setAttribute('data-theme', saved);
})();

const bar = document.getElementById('progressBar');
window.addEventListener('scroll', () => {
  const article = document.getElementById('articleBody');
  if (!article) return;
  const total = article.offsetHeight - window.innerHeight;
  const scrolled = Math.max(0, -article.getBoundingClientRect().top);
  bar.style.width = Math.min(100, (scrolled / total) * 100) + '%';
}, { passive: true });

function copyLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const btn = document.querySelector('.share-copy');
    if (btn) {
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
      setTimeout(() => { btn.innerHTML = orig; }, 2400);
    }
  });
}

const ro = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); } });
}, { threshold: .07 });
document.querySelectorAll('.reveal').forEach(el => ro.observe(el));

document.querySelectorAll('.prose img').forEach(img => {
  img.style.cursor = 'zoom-in';
  img.addEventListener('click', () => {
    const ov = document.createElement('div');
    ov.style.cssText = 'position:fixed; inset:0; background:rgba(0,0,0,.95); z-index:10000; display:flex; align-items:center; justify-content:center; cursor:zoom-out;';
    const clone = img.cloneNode();
    clone.style.cssText = 'max-width:90vw; max-height:90vh; border-radius:16px;';
    ov.appendChild(clone);
    ov.addEventListener('click', () => ov.remove());
    document.body.appendChild(ov);
  });
});
</script>

<?php mysqli_close($conn); include '../includes/footer.php'; ?>
</body>
</html>