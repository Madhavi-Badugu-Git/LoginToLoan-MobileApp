<?php
// resources/news.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/dbConfig.php';
include '../includes/navbar.php';

// ── Image base URL: CRM project serves the uploaded images ──
define('NEWS_IMG_BASE', 'http://localhost/crm.logintoloans/gallery/');

// ── Fetch all active news articles ──
$all_result = mysqli_query($conn,
    "SELECT * FROM tbl_uploadnews WHERE status='active' ORDER BY is_featured DESC, is_breaking DESC, created_at DESC"
);
$all_news = [];
while ($row = mysqli_fetch_assoc($all_result)) $all_news[] = $row;

$total_news = count($all_news);

// ── Featured news (first featured or first news) ──
$featured_news = null;
$regular_news = [];
foreach ($all_news as $n) {
    if (!$featured_news && $n['is_featured']) {
        $featured_news = $n;
    } else {
        $regular_news[] = $n;
    }
}
if (!$featured_news && !empty($all_news)) {
    $featured_news = $all_news[0];
    $regular_news = array_slice($all_news, 1);
}

// Side hero news (next 2)
$hero_side = array_slice($regular_news, 0, 2);
$main_news = $regular_news;
$list_news = array_slice($regular_news, 7, 4);

// ── Breaking News (latest breaking) ──
$breaking_result = mysqli_query($conn,
    "SELECT * FROM tbl_uploadnews WHERE status='active' AND is_breaking=1 ORDER BY created_at DESC LIMIT 1"
);
$breaking_news = mysqli_fetch_assoc($breaking_result);

// ── Authors ──
$authors_result = mysqli_query($conn,
    "SELECT author, COUNT(*) as cnt FROM tbl_uploadnews WHERE status='active' GROUP BY author ORDER BY cnt DESC LIMIT 4"
);
$authors = [];
while ($r = mysqli_fetch_assoc($authors_result)) $authors[] = $r;

// ── Categories with counts ──
$cats_result = mysqli_query($conn,
    "SELECT category, COUNT(*) as cnt FROM tbl_uploadnews WHERE status='active' GROUP BY category ORDER BY cnt DESC"
);
$db_categories = [];
while ($r = mysqli_fetch_assoc($cats_result)) $db_categories[] = $r;

// ── Total views ──
$total_views = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(views),0) as t FROM tbl_uploadnews"))['t'];
$views_display = $total_views >= 1000 ? round($total_views/1000,1).'K' : $total_views;

// ── Category labels ──
$cat_labels = [
    'market'    => 'Markets',
    'economy'   => 'Economy',
    'insurance' => 'Insurance',
    'sip'       => 'SIP & MF',
    'rbi'       => 'RBI & Policy',
];

/**
 * Build the correct public URL for a news thumbnail
 */
function get_news_thumb($row) {
    if (empty($row['thumbnail_path'])) return '';
    $path = trim($row['thumbnail_path']);
    if (preg_match('/^https?:\/\//', $path)) return $path;
    return NEWS_IMG_BASE . ltrim($path, '/');
}

function news_time_ago($datetime) {
    $diff = time() - strtotime($datetime);
    if ($diff < 3600)   return round($diff / 60) . ' min ago';
    if ($diff < 86400)  return round($diff / 3600) . ' hrs ago';
    if ($diff < 604800) return round($diff / 86400) . ' days ago';
    return date('d M Y', strtotime($datetime));
}

function news_cat_cls($cat) {
    $map = [
        'market' => 'market', 'economy' => 'economy',
        'insurance' => 'insurance', 'sip' => 'sip', 'rbi' => 'rbi',
    ];
    return $map[strtolower($cat)] ?? 'market';
}
?>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,700;0,9..144,900;1,9..144,400;1,9..144,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
/* ═══════════════════════════════════════════════════
   PREMIUM NEWS — ORANGE × NAVY BLUE THEME
   LoginToLoans · Financial Intelligence Platform
═══════════════════════════════════════════════════ */

:root {
  --navy-900: #040d1f;
  --navy-800: #071229;
  --navy-700: #0c1d3e;
  --navy-600: #112654;
  --navy-500: #1a3570;
  --navy-400: #234a9e;
  --navy-300: #3562c4;
  --navy-200: #6b90d8;
  --navy-100: #b8cef0;
  --navy-50:  #e8f0fc;

  --orange-900: #7a1e00;
  --orange-800: #a32600;
  --orange-700: #cc3000;
  --orange-600: #e84200;
  --orange-500: #f55a1a;
  --orange-400: #ff6b2b;
  --orange-300: #ff8c55;
  --orange-200: #ffb899;
  --orange-100: #ffe4d6;
  --orange-50:  #fff5f0;

  --white: #ffffff;
  --off-white: #fafbfe;
  --gray-50: #f4f6fb;
  --gray-100: #e8ecf5;
  --gray-200: #d1d9eb;
  --gray-300: #a8b4cf;
  --gray-400: #7d8eaf;
  --gray-500: #5a6a8a;
  --gray-600: #3e4e6e;
  --gray-700: #2a3550;
  --gray-800: #1a2238;
  --gray-900: #0d1220;

  --bg-primary: var(--off-white);
  --bg-card: var(--white);
  --text-primary: var(--navy-800);
  --text-secondary: var(--gray-500);
  --text-muted: var(--gray-400);
  --accent: var(--orange-500);
  --accent-hover: var(--orange-600);
  --brand: var(--navy-600);

  --shadow-xs: 0 1px 4px rgba(7,18,41,.06);
  --shadow-sm: 0 4px 16px rgba(7,18,41,.08);
  --shadow-md: 0 8px 32px rgba(7,18,41,.10);
  --shadow-lg: 0 20px 60px rgba(7,18,41,.14);
  --shadow-orange: 0 8px 32px rgba(245,90,26,.25);
  --shadow-navy: 0 8px 32px rgba(17,38,84,.30);

  --r-xs: 6px;
  --r-sm: 10px;
  --r-md: 16px;
  --r-lg: 22px;
  --r-xl: 30px;

  --ease-out: cubic-bezier(.16,1,.3,1);
  --ease-spring: cubic-bezier(.34,1.56,.64,1);
  --ease-smooth: cubic-bezier(.4,0,.2,1);

  --max-w: 1400px;
  --pad-x: 60px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }

body {
  font-family: 'Sora', system-ui, sans-serif;
  background: var(--bg-primary);
  color: var(--text-primary);
  overflow-x: hidden;
}

img { display: block; max-width: 100%; }
a { text-decoration: none; color: inherit; }
button { cursor: pointer; border: none; background: none; font-family: inherit; }

::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--gray-50); }
::-webkit-scrollbar-thumb { background: linear-gradient(var(--orange-400), var(--orange-600)); border-radius: 3px; }

/* ── TICKER BAR ── */
.ticker-bar {
  background: var(--navy-800);
  height: 40px;
  display: flex;
  align-items: center;
  overflow: hidden;
  border-bottom: 1px solid rgba(255,107,43,.12);
  position: relative;
}
.ticker-bar::after {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 80px;
  background: linear-gradient(90deg, transparent, var(--navy-800));
  pointer-events: none;
  z-index: 2;
}
.ticker-badge {
  flex-shrink: 0;
  height: 100%;
  background: linear-gradient(135deg, var(--orange-500), var(--orange-400));
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 0 24px 0 16px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: .2em;
  color: #fff;
  text-transform: uppercase;
  clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 50%, calc(100% - 10px) 100%, 0 100%);
  z-index: 3;
}
.ticker-track {
  flex: 1;
  overflow: hidden;
  mask-image: linear-gradient(90deg, transparent, #000 3%, #000 90%, transparent);
}
.ticker-inner {
  display: inline-flex;
  white-space: nowrap;
  animation: ticker-roll 40s linear infinite;
}
.ticker-inner:hover { animation-play-state: paused; }
@keyframes ticker-roll { to { transform: translateX(-50%); } }
.t-item {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  padding: 0 28px;
  font-size: 11px;
  color: rgba(255,255,255,.5);
  border-right: 1px solid rgba(255,255,255,.06);
}
.t-sym { font-family: 'DM Mono', monospace; font-size: 9px; letter-spacing: .1em; color: rgba(255,107,43,.7); font-weight: 500; }
.t-val { font-weight: 600; color: rgba(255,255,255,.9); font-family: 'DM Mono', monospace; }
.t-chg { font-size: 10px; font-weight: 700; display: flex; align-items: center; gap: 3px; }
.t-up { color: #34d399; } .t-dn { color: #f87171; }

/* ── HERO SECTION ── */
.news-hero {
  background: var(--navy-900);
  position: relative;
  overflow: hidden;
  padding-bottom: 0;
}
.news-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,107,43,.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,107,43,.04) 1px, transparent 1px);
  background-size: 60px 60px;
  pointer-events: none;
}
.hero-glow {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.hero-glow-1 {
  width: 800px; height: 800px;
  top: -300px; right: -200px;
  background: radial-gradient(circle, rgba(255,107,43,.10) 0%, transparent 60%);
  animation: glow-pulse 8s ease-in-out infinite;
}
.hero-glow-2 {
  width: 600px; height: 600px;
  bottom: -200px; left: -100px;
  background: radial-gradient(circle, rgba(35,74,158,.18) 0%, transparent 60%);
  animation: glow-pulse 11s ease-in-out infinite reverse 3s;
}
@keyframes glow-pulse {
  0%, 100% { transform: scale(1) translate(0, 0); opacity: 1; }
  50% { transform: scale(1.1) translate(20px, -15px); opacity: .75; }
}

.hero-inner {
  max-width: var(--max-w);
  margin: 0 auto;
  padding: 52px var(--pad-x) 0;
  position: relative;
  z-index: 2;
}

.masthead {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 24px;
  border-bottom: 1px solid rgba(255,107,43,.15);
  margin-bottom: 52px;
}
.masthead-brand {
  display: flex;
  align-items: center;
  gap: 12px;
}
.masthead-logo-dot {
  width: 8px; height: 8px;
  background: var(--orange-400);
  border-radius: 50%;
  box-shadow: 0 0 12px rgba(255,107,43,.6);
  animation: blink-dot 2s ease-in-out infinite;
}
@keyframes blink-dot { 0%,100%{opacity:1}50%{opacity:.4} }
.masthead-label {
  font-size: 10px;
  font-weight: 600;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: rgba(255,107,43,.8);
}
.masthead-center {
  font-family: 'Fraunces', serif;
  font-size: 13px;
  letter-spacing: .25em;
  text-transform: uppercase;
  color: rgba(255,255,255,.35);
  font-style: italic;
}
.masthead-date {
  font-size: 10px;
  letter-spacing: .12em;
  color: rgba(255,255,255,.3);
  font-family: 'DM Mono', monospace;
}

/* Hero Featured */
.hero-feature-wrap {
  display: grid;
  grid-template-columns: 1fr;
  margin: 0 calc(-1 * var(--pad-x));
}
.hero-feature-card {
  position: relative;
  height: 580px;
  overflow: hidden;
  cursor: pointer;
}
.hero-feature-card img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 1.4s var(--ease-out);
  filter: brightness(.7);
}
.hero-feature-card:hover img { transform: scale(1.06); }
.hero-feature-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    165deg,
    rgba(4,13,31,.15) 0%,
    rgba(4,13,31,.5) 45%,
    rgba(4,13,31,.92) 100%
  );
}
.hero-feature-card::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--orange-500), var(--orange-300), transparent);
}
.hero-feature-content {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  padding: 48px 60px;
}
.hero-live-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(4,13,31,.7);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,107,43,.25);
  border-radius: 50px;
  padding: 6px 16px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: .18em;
  color: var(--orange-300);
  text-transform: uppercase;
  margin-bottom: 18px;
}
.hero-live-dot {
  width: 7px; height: 7px;
  background: #34d399;
  border-radius: 50%;
  animation: pulse-live 1.6s ease-in-out infinite;
}
@keyframes pulse-live {
  0%,100%{box-shadow:0 0 0 0 rgba(52,211,153,.6)}
  50%{box-shadow:0 0 0 6px rgba(52,211,153,0)}
}
.hero-cat-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 9.5px;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: #fff;
  padding: 5px 16px;
  border-radius: 50px;
  margin-bottom: 18px;
}
.hero-feature-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(28px, 3.2vw, 48px);
  font-weight: 700;
  color: #fff;
  line-height: 1.15;
  margin-bottom: 16px;
  letter-spacing: -.02em;
  max-width: 720px;
}
.hero-feature-excerpt {
  font-size: 14px;
  font-weight: 300;
  color: rgba(255,255,255,.5);
  line-height: 1.8;
  max-width: 560px;
  margin-bottom: 24px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.hero-feature-meta {
  display: flex;
  align-items: center;
  gap: 20px;
  font-size: 12px;
  color: rgba(255,255,255,.4);
}
.hero-feature-meta i { color: var(--orange-400); font-size: 10px; }
.hero-read-btn {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  background: linear-gradient(135deg, var(--orange-500), var(--orange-400));
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  padding: 12px 28px;
  border-radius: 50px;
  box-shadow: var(--shadow-orange);
  transition: all .3s var(--ease-out);
  margin-top: 24px;
}
.hero-read-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(245,90,26,.4); }
.hero-read-btn i { transition: transform .3s; }
.hero-read-btn:hover i { transform: translateX(4px); }

.hero-empty {
  text-align: center;
  padding: 100px 20px;
  position: relative;
  z-index: 2;
}
.hero-empty h2 {
  font-family: 'Fraunces', serif;
  font-size: clamp(36px,5vw,64px);
  font-weight: 700;
  color: #fff;
  margin-bottom: 16px;
}
.hero-empty h2 em { font-style: italic; color: var(--orange-300); }
.hero-empty p { font-size: 15px; color: rgba(255,255,255,.35); max-width: 400px; margin: 0 auto; }

/* ── CATEGORY NAVIGATION BAR ── */
.cat-nav {
  background: var(--white);
  border-bottom: 1px solid var(--gray-100);
  box-shadow: var(--shadow-xs);
  position: sticky;
  top: 0;
  z-index: 100;
}
.cat-nav-inner {
  max-width: var(--max-w);
  margin: 0 auto;
  padding: 0 var(--pad-x);
  display: flex;
  align-items: center;
  overflow-x: auto;
  scrollbar-width: none;
  gap: 2px;
}
.cat-nav-inner::-webkit-scrollbar { display: none; }
.cat-pill {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 16px 20px;
  font-size: 12px;
  font-weight: 500;
  color: var(--gray-500);
  border-bottom: 2.5px solid transparent;
  transition: all .22s var(--ease-smooth);
  cursor: pointer;
  white-space: nowrap;
}
.cat-pill i { font-size: 11px; opacity: .65; }
.cat-pill:hover { color: var(--orange-500); background: var(--orange-50); }
.cat-pill.active {
  color: var(--orange-500);
  border-bottom-color: var(--orange-500);
  font-weight: 700;
}
.cat-pill .pill-count {
  background: var(--orange-100);
  color: var(--orange-600);
  font-size: 9.5px;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 50px;
  font-family: 'DM Mono', monospace;
}
.cat-pill.active .pill-count {
  background: var(--orange-500);
  color: #fff;
}

/* ── PAGE LAYOUT ── */
.page-wrap {
  max-width: var(--max-w);
  margin: 0 auto;
  padding: 72px var(--pad-x) 96px;
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 72px;
}

/* ── SECTION HEADERS ── */
.section-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 36px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--gray-100);
  position: relative;
}
.section-header::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 56px;
  height: 2px;
  background: linear-gradient(90deg, var(--orange-500), var(--orange-300));
  border-radius: 1px;
}
.section-eyebrow {
  font-size: 9.5px;
  font-weight: 700;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: var(--orange-500);
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.section-eyebrow::before {
  content: '';
  width: 20px;
  height: 2px;
  background: var(--orange-500);
  border-radius: 1px;
}
.section-title {
  font-family: 'Fraunces', serif;
  font-size: 26px;
  font-weight: 700;
  color: var(--navy-800);
  line-height: 1.2;
}
.section-title em { font-style: italic; color: var(--orange-500); }
.section-link {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--navy-500);
  padding: 9px 20px;
  border: 1.5px solid var(--gray-200);
  border-radius: 50px;
  transition: all .25s var(--ease-out);
  white-space: nowrap;
}
.section-link:hover {
  background: var(--navy-600);
  color: #fff;
  border-color: var(--navy-600);
  transform: translateY(-1px);
  box-shadow: var(--shadow-navy);
}
.section-link i { transition: transform .25s; }
.section-link:hover i { transform: translateX(3px); }

/* ── NEWS CARDS ── */
.cards-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 28px;
  margin-bottom: 28px;
}
.cards-grid-3 {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
  margin-bottom: 40px;
}

.b-card {
  background: var(--white);
  border: 1px solid var(--gray-100);
  border-radius: var(--r-lg);
  overflow: hidden;
  cursor: pointer;
  transition: all .35s var(--ease-out);
  display: flex;
  flex-direction: column;
  position: relative;
  box-shadow: var(--shadow-xs);
}
.b-card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: var(--r-lg);
  background: linear-gradient(135deg, rgba(255,107,43,.04), rgba(17,38,84,.04));
  opacity: 0;
  transition: opacity .35s;
  pointer-events: none;
}
.b-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-lg);
  border-color: rgba(255,107,43,.15);
}
.b-card:hover::before { opacity: 1; }
.b-card::after {
  content: '';
  position: absolute;
  bottom: 0; left: 10%; right: 10%;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--orange-400), transparent);
  opacity: 0;
  transform: scaleX(0);
  transition: all .35s var(--ease-out);
}
.b-card:hover::after { opacity: 1; transform: scaleX(1); }

/* Breaking Badge */
.breaking-badge-card {
  position: absolute;
  top: 14px; right: 14px;
  background: linear-gradient(135deg, #dc2626, #ef4444);
  color: #fff;
  font-size: 8px;
  font-weight: 800;
  letter-spacing: .1em;
  padding: 4px 12px;
  border-radius: 50px;
  z-index: 2;
  box-shadow: 0 2px 8px rgba(220,38,38,.4);
  text-transform: uppercase;
}
.breaking-badge-card i { margin-right: 4px; font-size: 7px; }

.bc-thumb {
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
}
.b-card.tall .bc-thumb { height: 250px; }
.b-card.med .bc-thumb { height: 200px; }
.b-card.sm .bc-thumb { height: 180px; }
.bc-thumb img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform .8s var(--ease-out);
}
.b-card:hover .bc-thumb img { transform: scale(1.07); }
.bc-tag {
  position: absolute;
  top: 14px; left: 14px;
  font-size: 8px;
  font-weight: 800;
  letter-spacing: .14em;
  text-transform: uppercase;
  padding: 5px 14px;
  border-radius: 50px;
  backdrop-filter: blur(10px);
  z-index: 2;
  border: 1px solid rgba(255,255,255,.15);
}
.bc-tag.market { background: rgba(245,90,26,.88); color: #fff; }
.bc-tag.economy { background: rgba(139,92,246,.88); color: #fff; }
.bc-tag.insurance { background: rgba(20,184,166,.88); color: #fff; }
.bc-tag.sip { background: rgba(22,163,74,.88); color: #fff; }
.bc-tag.rbi { background: rgba(220,38,38,.88); color: #fff; }

.bc-body {
  padding: 24px 26px 28px;
  display: flex;
  flex-direction: column;
  flex: 1;
  position: relative;
  z-index: 2;
}
.bc-title {
  font-family: 'Fraunces', serif;
  font-weight: 600;
  color: var(--navy-800);
  line-height: 1.3;
  margin-bottom: 12px;
  transition: color .25s;
}
.b-card.tall .bc-title { font-size: 21px; }
.b-card.med .bc-title { font-size: 19px; }
.b-card.sm .bc-title { font-size: 17.5px; }
.b-card:hover .bc-title { color: var(--orange-600); }
.bc-excerpt {
  font-size: 13px;
  font-weight: 400;
  color: var(--gray-500);
  line-height: 1.85;
  margin-bottom: 20px;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.bc-foot {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 16px;
  border-top: 1px solid var(--gray-100);
  margin-top: auto;
}
.bc-author { display: flex; align-items: center; gap: 11px; }
.bc-ava {
  width: 32px; height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--navy-400), var(--navy-600));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 800;
  color: #fff;
  flex-shrink: 0;
  font-family: 'Fraunces', serif;
}
.bc-a-name { font-size: 12px; font-weight: 600; color: var(--navy-700); line-height: 1; }
.bc-a-time {
  font-size: 10.5px;
  color: var(--gray-400);
  margin-top: 3px;
  display: flex;
  align-items: center;
  gap: 4px;
  font-family: 'DM Mono', monospace;
}
.bc-a-time i { font-size: 9px; color: var(--orange-400); }
.bc-cta {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: .08em;
  color: var(--orange-500);
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 7px 16px;
  border: 1.5px solid rgba(245,90,26,.2);
  border-radius: 50px;
  transition: all .25s var(--ease-out);
  background: var(--orange-50);
}
.b-card:hover .bc-cta {
  background: var(--orange-500);
  color: #fff;
  border-color: var(--orange-500);
  gap: 8px;
  box-shadow: var(--shadow-orange);
}
.bc-cta i { transition: transform .25s; }
.b-card:hover .bc-cta i { transform: translateX(3px); }

.no-image {
  background: linear-gradient(135deg, var(--navy-700), var(--navy-800));
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: rgba(255,255,255,.2);
  text-align: center;
  width: 100%;
  height: 100%;
  min-height: 180px;
}
.no-image i { font-size: 32px; margin-bottom: 8px; }

/* ── LIST CARDS ── */
.list-stack { display: flex; flex-direction: column; gap: 14px; }
.l-card {
  display: grid;
  grid-template-columns: 92px 1fr;
  background: var(--white);
  border: 1px solid var(--gray-100);
  border-radius: var(--r-md);
  overflow: hidden;
  cursor: pointer;
  transition: all .28s var(--ease-out);
  box-shadow: var(--shadow-xs);
  position: relative;
}
.l-card::before {
  content: '';
  position: absolute;
  left: 0; top: 0; bottom: 0;
  width: 3px;
  background: linear-gradient(180deg, var(--orange-400), var(--orange-600));
  opacity: 0;
  transition: opacity .28s;
}
.l-card:hover { transform: translateX(6px); box-shadow: var(--shadow-md); border-color: rgba(245,90,26,.12); }
.l-card:hover::before { opacity: 1; }
.lc-thumb { overflow: hidden; height: 92px; }
.lc-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .55s var(--ease-out); }
.l-card:hover .lc-thumb img { transform: scale(1.12); }
.lc-body { padding: 14px 18px; display: flex; flex-direction: column; justify-content: center; }
.lc-cat {
  font-size: 8.5px;
  font-weight: 800;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--orange-500);
  margin-bottom: 5px;
}
.lc-title {
  font-family: 'Fraunces', serif;
  font-size: 15.5px;
  font-weight: 600;
  color: var(--navy-700);
  line-height: 1.3;
  margin-bottom: 7px;
  transition: color .22s;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.l-card:hover .lc-title { color: var(--orange-600); }
.lc-meta {
  font-size: 10.5px;
  color: var(--gray-400);
  display: flex;
  align-items: center;
  gap: 5px;
  font-family: 'DM Mono', monospace;
}
.lc-meta i { font-size: 9px; color: var(--orange-400); }

/* ── EMPTY STATE ── */
.empty-news {
  text-align: center;
  padding: 80px 20px;
  grid-column: 1 / -1;
}
.empty-news-icon {
  width: 80px; height: 80px;
  background: var(--orange-50);
  border: 2px solid var(--orange-100);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
}
.empty-news-icon i { font-size: 30px; color: var(--orange-400); }
.empty-news h3 { font-family: 'Fraunces', serif; font-size: 26px; font-weight: 700; color: var(--navy-700); margin-bottom: 10px; }
.empty-news p { font-size: 14px; color: var(--gray-400); max-width: 360px; margin: 0 auto; line-height: 1.7; }

/* ── LOAD MORE ── */
.load-more { text-align: center; padding-top: 52px; }
.load-more-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: transparent;
  border: 2px solid var(--gray-200);
  color: var(--navy-600);
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  padding: 15px 44px;
  border-radius: 50px;
  transition: all .3s var(--ease-out);
  font-family: 'Sora', sans-serif;
}
.load-more-btn:hover {
  background: var(--navy-800);
  border-color: var(--navy-800);
  color: #fff;
  transform: translateY(-3px);
  box-shadow: var(--shadow-navy);
}
.load-more-btn i { transition: transform .35s; }
.load-more-btn:hover i { transform: rotate(180deg); }

/* ── SIDEBAR ── */
.sidebar-col { position: relative; }
.sb-sticky { position: sticky; top: 80px; display: flex; flex-direction: column; gap: 36px; }
.sb-head {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 1px solid var(--gray-100);
  position: relative;
}
.sb-head::after {
  content: '';
  position: absolute;
  bottom: -1px; left: 0;
  width: 40px;
  height: 2px;
  background: var(--orange-500);
}
.sb-icon {
  width: 32px; height: 32px;
  border-radius: 8px;
  background: linear-gradient(135deg, var(--orange-100), var(--orange-50));
  border: 1px solid rgba(245,90,26,.15);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  color: var(--orange-500);
}
.sb-title { font-family: 'Fraunces', serif; font-size: 21px; font-weight: 700; color: var(--navy-800); }

/* Breaking News Widget */
.breaking-widget {
  background: linear-gradient(135deg, #fff5f0, #fff0ea);
  border-left: 4px solid #dc2626;
}
.breaking-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: var(--white);
  border-radius: var(--r-md);
  cursor: pointer;
  transition: all .25s;
  border: 1px solid var(--gray-100);
}
.breaking-item:hover { transform: translateX(4px); box-shadow: var(--shadow-sm); border-color: rgba(220,38,38,.2); }
.breaking-icon {
  width: 36px; height: 36px;
  background: #dc2626;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 14px;
}
.breaking-title { font-weight: 600; font-size: 13px; line-height: 1.3; margin-bottom: 4px; }
.breaking-time { font-size: 9px; color: var(--gray-400); font-family: 'DM Mono', monospace; }

/* Stats */
.stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.stat-box {
  background: linear-gradient(145deg, var(--navy-50), var(--white));
  border: 1px solid var(--gray-100);
  border-radius: var(--r-md);
  padding: 18px 16px;
  text-align: center;
  transition: all .25s var(--ease-out);
  position: relative;
  overflow: hidden;
}
.stat-box::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, var(--orange-400), var(--orange-300));
  opacity: 0;
  transition: opacity .25s;
}
.stat-box:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.stat-box:hover::after { opacity: 1; }
.stat-num {
  font-family: 'Fraunces', serif;
  font-size: 30px;
  font-weight: 900;
  color: var(--orange-500);
  line-height: 1;
  margin-bottom: 4px;
}
.stat-label { font-size: 10px; font-weight: 600; color: var(--gray-400); letter-spacing: .05em; text-transform: uppercase; }

/* Authors */
.author-list { display: flex; flex-direction: column; gap: 10px; }
.a-row {
  display: flex;
  align-items: center;
  gap: 13px;
  padding: 13px 16px;
  background: var(--gray-50);
  border: 1px solid var(--gray-100);
  border-radius: var(--r-md);
  cursor: pointer;
  transition: all .25s var(--ease-out);
}
.a-row:hover { background: var(--white); box-shadow: var(--shadow-sm); transform: translateX(4px); border-color: rgba(245,90,26,.12); }
.a-ava {
  width: 40px; height: 40px;
  border-radius: 50%;
  flex-shrink: 0;
  background: linear-gradient(135deg, var(--navy-400), var(--navy-600));
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Fraunces', serif;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
}
.a-info { flex: 1; }
.a-name { font-size: 13px; font-weight: 600; color: var(--navy-700); line-height: 1; }
.a-role { font-size: 10.5px; color: var(--gray-400); margin-top: 3px; }
.a-count { text-align: right; }
.a-num { font-family: 'Fraunces', serif; font-size: 22px; font-weight: 900; color: var(--orange-500); line-height: 1; }
.a-num-lbl { font-size: 9px; color: var(--gray-400); letter-spacing: .05em; }

/* Newsletter */
.nl-box {
  background: linear-gradient(145deg, var(--navy-900), var(--navy-800));
  border-radius: var(--r-xl);
  padding: 30px 24px;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255,107,43,.12);
}
.nl-box::before {
  content: '';
  position: absolute;
  top: -80px; right: -80px;
  width: 280px; height: 280px;
  background: radial-gradient(circle, rgba(255,107,43,.10), transparent 65%);
  pointer-events: none;
}
.nl-icon {
  width: 50px; height: 50px;
  background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #fff;
  margin-bottom: 16px;
  box-shadow: var(--shadow-orange);
  position: relative;
  z-index: 1;
}
.nl-h { font-family: 'Fraunces', serif; font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 8px; position: relative; z-index: 1; }
.nl-p { font-size: 13px; font-weight: 300; color: rgba(255,255,255,.38); line-height: 1.75; margin-bottom: 20px; position: relative; z-index: 1; }
.nl-input {
  width: 100%;
  background: rgba(255,255,255,.07);
  border: 1px solid rgba(255,255,255,.10);
  border-radius: var(--r-sm);
  padding: 13px 16px;
  font-family: 'Sora', sans-serif;
  font-size: 13px;
  color: #fff;
  outline: none;
  transition: border-color .22s;
  margin-bottom: 12px;
  position: relative;
  z-index: 1;
}
.nl-input::placeholder { color: rgba(255,255,255,.22); }
.nl-input:focus { border-color: rgba(255,107,43,.4); background: rgba(255,255,255,.10); }
.nl-btn {
  width: 100%;
  background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
  color: #fff;
  border: none;
  border-radius: var(--r-sm);
  padding: 13px;
  font-family: 'Sora', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: all .28s var(--ease-out);
  box-shadow: var(--shadow-orange);
  position: relative;
  z-index: 1;
}
.nl-btn:hover { filter: brightness(1.08); transform: translateY(-2px); }
.nl-note { font-size: 10px; color: rgba(255,255,255,.2); text-align: center; margin-top: 10px; position: relative; z-index: 1; display: flex; align-items: center; justify-content: center; gap: 5px; }

/* Tags */
.tag-cloud { display: flex; flex-wrap: wrap; gap: 8px; }
.tag-pill {
  background: var(--gray-50);
  border: 1px solid var(--gray-200);
  color: var(--gray-600);
  font-size: 11.5px;
  font-weight: 500;
  padding: 7px 15px;
  border-radius: 50px;
  cursor: pointer;
  transition: all .25s var(--ease-spring);
  display: inline-flex;
  align-items: center;
  gap: 5px;
}
.tag-pill:hover, .tag-pill.hot {
  background: var(--orange-50);
  border-color: rgba(245,90,26,.2);
  color: var(--orange-600);
  transform: translateY(-2px);
}

/* ── SCROLL REVEAL ── */
.reveal {
  opacity: 0;
  transform: translateY(32px);
  transition: opacity .75s var(--ease-out), transform .75s var(--ease-out);
}
.reveal.visible { opacity: 1; transform: none; }
.delay-1 { transition-delay: .1s; }
.delay-2 { transition-delay: .2s; }
.delay-3 { transition-delay: .3s; }
.delay-4 { transition-delay: .4s; }

/* ── RESPONSIVE ── */
@media (max-width: 1200px) {
  :root { --pad-x: 36px; }
  .page-wrap { grid-template-columns: 1fr; }
  .sidebar-col { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; }
  .sb-sticky { position: static; display: contents; }
}
@media (max-width: 900px) {
  :root { --pad-x: 24px; }
  .cards-grid-2 { grid-template-columns: 1fr; }
  .cards-grid-3 { grid-template-columns: 1fr 1fr; }
  .hero-feature-content { padding: 32px 28px; }
  .hero-feature-card { height: 460px; }
}
@media (max-width: 640px) {
  :root { --pad-x: 16px; }
  .cards-grid-2, .cards-grid-3 { grid-template-columns: 1fr; }
  .sidebar-col { grid-template-columns: 1fr; }
  .hero-feature-card { height: 380px; }
  .page-wrap { padding: 40px var(--pad-x) 64px; }
}
</style>

<!-- ═══════ TICKER BAR ═══════ -->
<div class="ticker-bar" style="margin-top:-5px;">
  <div class="ticker-badge"><i class="fa-solid fa-rss"></i>&nbsp;LIVE</div>
  <div class="ticker-track">
    <div class="ticker-inner" id="tickerEl"></div>
  </div>
</div>

<!-- ═══════ HERO SECTION ═══════ -->
<section class="news-hero">
  <div class="hero-glow hero-glow-1"></div>
  <div class="hero-glow hero-glow-2"></div>

  <div class="hero-inner">
    <div class="masthead">
      <div class="masthead-brand">
        <div class="masthead-logo-dot"></div>
        <span class="masthead-label">LoginToLoans News &middot; Stay Ahead</span>
      </div>
      <span class="masthead-center">Markets &middot; Economy &middot; Policy &middot; Finance</span>
      <span class="masthead-date" id="hDate"></span>
    </div>
  </div>

  <?php if ($featured_news): ?>
  <div class="hero-feature-wrap">
    <div class="hero-feature-card" onclick="window.location.href='news-details.php?id=<?php echo $featured_news['id']; ?>'">
      <?php $img_src = get_news_thumb($featured_news); ?>
      <?php if (!empty($img_src)): ?>
        <img src="<?php echo htmlspecialchars($img_src); ?>"
             alt="<?php echo htmlspecialchars($featured_news['title']); ?>"
             loading="eager"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" />
        <div class="no-image" style="height:580px;display:none"><i class="fa-regular fa-image"></i><p>No Image</p></div>
      <?php else: ?>
        <div class="no-image" style="height:580px"><i class="fa-regular fa-image"></i><p>No Image Available</p></div>
      <?php endif; ?>
      <div class="hero-feature-overlay"></div>
      <div class="hero-feature-content">
        <?php if ($featured_news['is_breaking']): ?>
        <div class="hero-live-badge"><div class="hero-live-dot"></div> Breaking News</div>
        <?php endif; ?>
        <div class="hero-cat-tag tag-<?php echo news_cat_cls($featured_news['category']); ?>">
          <?php echo htmlspecialchars($cat_labels[$featured_news['category']] ?? ucfirst($featured_news['category'])); ?>
        </div>
        <div class="hero-feature-title"><?php echo htmlspecialchars($featured_news['title']); ?></div>
        <div class="hero-feature-excerpt"><?php echo htmlspecialchars($featured_news['excerpt'] ?? ''); ?></div>
        <div class="hero-feature-meta">
          <span><i class="fa-regular fa-user"></i>&nbsp;<?php echo htmlspecialchars($featured_news['author'] ?? 'News Desk'); ?></span>
          <span><i class="fa-regular fa-clock"></i>&nbsp;<?php echo news_time_ago($featured_news['created_at']); ?></span>
          <?php if (!empty($featured_news['read_time'])): ?>
          <span><i class="fa-solid fa-book-open"></i>&nbsp;<?php echo htmlspecialchars($featured_news['read_time']); ?> read</span>
          <?php endif; ?>
        </div>
        <button class="hero-read-btn">Read Full Article&nbsp;<i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="hero-empty">
    <h2>News <em>Room</em></h2>
    <p>Markets · Economy · Policy · Financial Intelligence</p>
  </div>
  <?php endif; ?>
</section>

<!-- ═══════ CATEGORY NAV BAR ═══════ -->
<div class="cat-nav">
  <div class="cat-nav-inner">
    <div class="cat-pill active" data-cat="all" onclick="setCatBar('all',this)">
      <i class="fa-solid fa-layer-group"></i> All News
      <span class="pill-count"><?php echo $total_news; ?></span>
    </div>
    <?php foreach ($db_categories as $dc):
      $icons = [
        'market' => 'fa-chart-line', 'economy' => 'fa-globe',
        'insurance' => 'fa-shield-halved', 'sip' => 'fa-chart-simple',
        'rbi' => 'fa-bank',
      ];
      $icon = $icons[$dc['category']] ?? 'fa-newspaper';
    ?>
    <div class="cat-pill" data-cat="<?php echo htmlspecialchars($dc['category']); ?>" onclick="setCatBar('<?php echo htmlspecialchars($dc['category']); ?>',this)">
      <i class="fa-solid <?php echo $icon; ?>"></i>
      <?php echo htmlspecialchars($cat_labels[$dc['category']] ?? ucfirst($dc['category'])); ?>
      <span class="pill-count"><?php echo $dc['cnt']; ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ═══════ MAIN PAGE LAYOUT ═══════ -->
<div class="page-wrap">

  <main>
    <div class="section-header reveal">
      <div>
        <div class="section-eyebrow">Latest News</div>
        <div class="section-title">Top <em>Stories</em></div>
      </div>
      <a class="section-link" href="#">View All &nbsp;<i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <?php if (!empty($main_news)): ?>

    <div class="cards-grid-2" id="row1">
      <?php foreach (array_slice($main_news, 0, 2) as $i => $news): ?>
      <div class="b-card tall reveal <?php echo $i > 0 ? 'delay-1' : ''; ?>"
           data-cat="<?php echo htmlspecialchars($news['category']); ?>"
           onclick="window.location.href='news-details.php?id=<?php echo $news['id']; ?>'">
        <div class="bc-thumb">
          <?php $img_src = get_news_thumb($news); ?>
          <?php if (!empty($img_src)): ?>
            <img src="<?php echo htmlspecialchars($img_src); ?>"
                 alt="<?php echo htmlspecialchars($news['title']); ?>"
                 loading="lazy"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" />
            <div class="no-image" style="display:none"><i class="fa-regular fa-image"></i></div>
          <?php else: ?>
            <div class="no-image"><i class="fa-regular fa-image"></i></div>
          <?php endif; ?>
          <?php if ($news['is_breaking']): ?>
            <div class="breaking-badge-card"><i class="fa-solid fa-bolt"></i> BREAKING</div>
          <?php endif; ?>
          <div class="bc-tag <?php echo news_cat_cls($news['category']); ?>">
            <?php echo htmlspecialchars($cat_labels[$news['category']] ?? ucfirst($news['category'])); ?>
          </div>
        </div>
        <div class="bc-body">
          <div class="bc-title"><?php echo htmlspecialchars($news['title']); ?></div>
          <div class="bc-excerpt"><?php echo htmlspecialchars($news['excerpt'] ?? ''); ?></div>
          <div class="bc-foot">
            <div class="bc-author">
              <div class="bc-ava"><?php echo htmlspecialchars(substr($news['author'] ?? 'ND', 0, 2)); ?></div>
              <div>
                <div class="bc-a-name"><?php echo htmlspecialchars($news['author'] ?? 'News Desk'); ?></div>
                <div class="bc-a-time">
                  <i class="fa-regular fa-clock"></i>
                  <?php echo news_time_ago($news['created_at']); ?>
                  <?php echo $news['read_time'] ? ' · ' . htmlspecialchars($news['read_time']) . ' read' : ''; ?>
                </div>
              </div>
            </div>
            <span class="bc-cta">Read <i class="fa-solid fa-arrow-right"></i></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if (count($main_news) > 2): ?>
    <div class="cards-grid-3" id="row2">
      <?php foreach (array_slice($main_news, 2, 3) as $i => $news): ?>
      <div class="b-card sm reveal <?php echo ['','delay-1','delay-2'][$i] ?? ''; ?>"
           data-cat="<?php echo htmlspecialchars($news['category']); ?>"
           onclick="window.location.href='news-details.php?id=<?php echo $news['id']; ?>'">
        <div class="bc-thumb">
          <?php $img_src = get_news_thumb($news); ?>
          <?php if (!empty($img_src)): ?>
            <img src="<?php echo htmlspecialchars($img_src); ?>"
                 alt="<?php echo htmlspecialchars($news['title']); ?>"
                 loading="lazy"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" />
            <div class="no-image" style="display:none"><i class="fa-regular fa-image"></i></div>
          <?php else: ?>
            <div class="no-image"><i class="fa-regular fa-image"></i></div>
          <?php endif; ?>
          <?php if ($news['is_breaking']): ?>
            <div class="breaking-badge-card"><i class="fa-solid fa-bolt"></i> BREAKING</div>
          <?php endif; ?>
          <div class="bc-tag <?php echo news_cat_cls($news['category']); ?>">
            <?php echo htmlspecialchars($cat_labels[$news['category']] ?? ucfirst($news['category'])); ?>
          </div>
        </div>
        <div class="bc-body">
          <div class="bc-title"><?php echo htmlspecialchars($news['title']); ?></div>
          <div class="bc-excerpt"><?php echo htmlspecialchars($news['excerpt'] ?? ''); ?></div>
          <div class="bc-foot">
            <div class="bc-author">
              <div class="bc-ava"><?php echo htmlspecialchars(substr($news['author'] ?? 'ND', 0, 2)); ?></div>
              <div>
                <div class="bc-a-name"><?php echo htmlspecialchars($news['author'] ?? ''); ?></div>
                <div class="bc-a-time">
                  <i class="fa-regular fa-clock"></i>
                  <?php echo $news['read_time'] ? htmlspecialchars($news['read_time']) . ' read' : news_time_ago($news['created_at']); ?>
                </div>
              </div>
            </div>
            <span class="bc-cta">Read <i class="fa-solid fa-arrow-right"></i></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="empty-news">
      <div class="empty-news-icon"><i class="fa-solid fa-newspaper"></i></div>
      <h3>No news yet</h3>
      <p>News articles will appear here once published from the admin panel.</p>
    </div>
    <?php endif; ?>

    <?php if (!empty($list_news)): ?>
    <div class="section-header reveal" style="margin-top:52px">
      <div>
        <div class="section-eyebrow">More Reading</div>
        <div class="section-title">Worth Your <em>Time</em></div>
      </div>
      <a class="section-link" href="#">All News &nbsp;<i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="list-stack" id="listStack">
      <?php foreach ($list_news as $news): ?>
      <div class="l-card reveal"
           data-cat="<?php echo htmlspecialchars($news['category']); ?>"
           onclick="window.location.href='news-details.php?id=<?php echo $news['id']; ?>'">
        <div class="lc-thumb">
          <?php $img_src = get_news_thumb($news); ?>
          <?php if (!empty($img_src)): ?>
            <img src="<?php echo htmlspecialchars($img_src); ?>"
                 alt="<?php echo htmlspecialchars($news['title']); ?>"
                 loading="lazy"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" />
            <div class="no-image" style="height:92px;display:none"><i class="fa-regular fa-image"></i></div>
          <?php else: ?>
            <div class="no-image" style="height:92px"><i class="fa-regular fa-image"></i></div>
          <?php endif; ?>
        </div>
        <div class="lc-body">
          <div class="lc-cat"><?php echo htmlspecialchars($cat_labels[$news['category']] ?? ucfirst($news['category'])); ?></div>
          <div class="lc-title"><?php echo htmlspecialchars($news['title']); ?></div>
          <div class="lc-meta">
            <i class="fa-regular fa-clock"></i>
            <?php echo news_time_ago($news['created_at']); ?>
            <?php echo $news['read_time'] ? ' · ' . htmlspecialchars($news['read_time']) . ' read' : ''; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="load-more">
      <button class="load-more-btn" onclick="loadMore()">
        <i class="fa-solid fa-chevron-down"></i>
        <span>Load More News</span>
      </button>
    </div>
  </main>

  <!-- SIDEBAR -->
  <aside class="sidebar-col">
    <div class="sb-sticky">

      <!-- Breaking News Widget -->
      <?php if ($breaking_news): ?>
      <div class="sb-widget reveal breaking-widget">
        <div class="sb-head">
          <div class="sb-icon"><i class="fa-solid fa-bolt" style="color:#dc2626;"></i></div>
          <div class="sb-title">Breaking News</div>
        </div>
        <div class="breaking-item" onclick="window.location.href='news-details.php?id=<?php echo $breaking_news['id']; ?>'">
          <div class="breaking-icon"><i class="fa-solid fa-bolt"></i></div>
          <div>
            <div class="breaking-title"><?php echo htmlspecialchars($breaking_news['title']); ?></div>
            <div class="breaking-time"><?php echo news_time_ago($breaking_news['created_at']); ?></div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Stats -->
      <div class="sb-widget reveal delay-1">
        <div class="sb-head">
          <div class="sb-icon"><i class="fa-solid fa-chart-pie"></i></div>
          <div class="sb-title">By the Numbers</div>
        </div>
        <div class="stats-grid">
          <div class="stat-box"><div class="stat-num"><?php echo $total_news; ?></div><div class="stat-label">Articles</div></div>
          <div class="stat-box"><div class="stat-num"><?php echo $views_display; ?></div><div class="stat-label">Readers</div></div>
          <div class="stat-box"><div class="stat-num"><?php echo count($db_categories); ?></div><div class="stat-label">Topics</div></div>
          <div class="stat-box"><div class="stat-num"><?php echo count($authors); ?></div><div class="stat-label">Authors</div></div>
        </div>
      </div>

      <!-- Authors -->
      <?php if (!empty($authors)): ?>
      <div class="sb-widget reveal delay-2">
        <div class="sb-head">
          <div class="sb-icon"><i class="fa-solid fa-pen-nib"></i></div>
          <div class="sb-title">Our Journalists</div>
        </div>
        <div class="author-list">
          <?php foreach ($authors as $a): ?>
          <div class="a-row">
            <div class="a-ava"><?php echo htmlspecialchars(substr($a['author'], 0, 2)); ?></div>
            <div class="a-info">
              <div class="a-name"><?php echo htmlspecialchars($a['author']); ?></div>
              <div class="a-role">Financial Journalist</div>
            </div>
            <div class="a-count">
              <div class="a-num"><?php echo (int)$a['cnt']; ?></div>
              <div class="a-num-lbl">articles</div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Newsletter -->
      <div class="sb-widget reveal delay-3">
        <div class="nl-box">
          <div class="nl-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
          <div class="nl-h">Daily Newsletter</div>
          <div class="nl-p">Get the day's top financial news delivered to your inbox.</div>
          <input class="nl-input" type="email" placeholder="your@email.com" />
          <button class="nl-btn"><i class="fa-solid fa-paper-plane" style="margin-right:8px"></i>Subscribe Free</button>
          <div class="nl-note"><i class="fa-solid fa-lock" style="font-size:8px"></i>&nbsp;No spam · Unsubscribe anytime</div>
        </div>
      </div>

      <!-- Tags -->
      <div class="sb-widget reveal delay-4">
        <div class="sb-head">
          <div class="sb-icon"><i class="fa-solid fa-hashtag"></i></div>
          <div class="sb-title">Trending Topics</div>
        </div>
        <div class="tag-cloud">
          <span class="tag-pill hot"><i class="fa-solid fa-fire"></i>#Budget2025</span>
          <span class="tag-pill">#RepoRate</span>
          <span class="tag-pill">#Inflation</span>
          <span class="tag-pill hot"><i class="fa-solid fa-fire"></i>#Nifty50</span>
          <span class="tag-pill">#FDIRates</span>
          <span class="tag-pill">#NPSScheme</span>
          <span class="tag-pill">#HealthInsurance</span>
          <span class="tag-pill">#ITReturns</span>
        </div>
      </div>

    </div>
  </aside>
</div>

<script>
/* Ticker */
const TICKER_DATA = [
  {s:'NIFTY 50', v:'24,468', c:'+1.24%', u:true},
  {s:'SENSEX',   v:'80,242', c:'+1.18%', u:true},
  {s:'BANK NIFTY',v:'52,140',c:'-0.31%', u:false},
  {s:'HDFC BANK', v:'₹1,742',c:'+0.88%', u:true},
  {s:'REPO RATE', v:'6.50%', c:'Unchanged',u:true},
  {s:'GOLD MCX',  v:'₹74,230',c:'+2.30%',u:true},
  {s:'USD/INR',   v:'84.12', c:'-0.08%', u:false},
];

function buildTicker() {
  const html = TICKER_DATA.map(t => `
    <span class="t-item">
      <span class="t-sym">${t.s}</span>
      <span class="t-val">${t.v}</span>
      <span class="t-chg ${t.u?'t-up':'t-dn'}">
        <i class="fa-solid fa-caret-${t.u?'up':'down'}"></i>${t.c}
      </span>
    </span>`).join('');
  document.getElementById('tickerEl').innerHTML = html + html;
}

/* Category filter */
function setCatBar(cat, el) {
  document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.b-card, .l-card').forEach(card => {
    const cardCat = card.dataset.cat || '';
    card.style.display = (cat === 'all' || cardCat === cat) ? '' : 'none';
  });
}

/* Load more */
function loadMore() {
  const btn = document.querySelector('.load-more-btn');
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Loading…</span>';
  btn.disabled = true;
  setTimeout(() => {
    btn.innerHTML = '<i class="fa-solid fa-check"></i><span>You\'re all caught up!</span>';
    btn.style.opacity = '.55';
    btn.style.cursor = 'default';
  }, 1800);
}

/* Scroll reveal */
const ro = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      ro.unobserve(e.target);
    }
  });
}, { threshold: 0.06 });
document.querySelectorAll('.reveal').forEach(el => ro.observe(el));

/* Date */
document.getElementById('hDate').textContent =
  new Date().toLocaleDateString('en-IN', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
  });

buildTicker();
</script>

<?php mysqli_close($conn); include '../includes/footer.php'; ?>