<?php
/**
 * Internal dashboard: careers applications, blog posts, job postings and
 * gallery photos. Not linked from public navigation — bookmark the URL.
 */

session_start();
require __DIR__ . '/careers-backend/db_config.php';
require __DIR__ . '/careers-backend/admin_config.php';

function is_logged_in(): bool
{
    return !empty($_SESSION['careers_admin']);
}

// ---- Logout ----
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: careers-admin.php');
    exit;
}

// ---- Login ----
$loginError = '';
if (!is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $passHash = hash('sha256', $pass);
    if (hash_equals(ADMIN_USERNAME, $user) && hash_equals(ADMIN_PASSWORD_SHA256, $passHash)) {
        $_SESSION['careers_admin'] = true;
    } else {
        $loginError = 'Incorrect username or password.';
    }
}

if (!is_logged_in()) {
    ?>
    <!doctype html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Careers Admin — Login</title>
      <meta name="robots" content="noindex, nofollow">
      <style>
        body{font-family:system-ui,sans-serif;background:#f2f3f7;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;}
        form{background:#fff;padding:36px 32px;border-radius:14px;box-shadow:0 8px 32px rgba(20,20,58,.12);width:280px;}
        h1{font-size:18px;margin:0 0 20px;color:#1a1a4d;}
        label{display:block;font-size:12px;font-weight:700;color:#666;margin:14px 0 6px;text-transform:uppercase;}
        input{width:100%;box-sizing:border-box;padding:10px 12px;border:1px solid #dfe3ea;border-radius:8px;font-size:14px;}
        button{margin-top:20px;width:100%;padding:11px;border:none;border-radius:999px;background:#1a1a4d;color:#fff;font-weight:700;cursor:pointer;}
        .err{color:#c0392b;font-size:13px;margin-top:12px;}
      </style>
    </head>
    <body>
      <form method="post">
        <h1>Careers Admin</h1>
        <label>Username</label>
        <input type="text" name="username" autocomplete="username" required>
        <label>Password</label>
        <input type="password" name="password" autocomplete="current-password" required>
        <button type="submit" name="login" value="1">Sign in</button>
        <?php if ($loginError): ?><div class="err"><?= htmlspecialchars($loginError) ?></div><?php endif; ?>
      </form>
    </body>
    </html>
    <?php
    exit;
}

$conn = careers_db();
$tab = in_array($_GET['tab'] ?? '', ['blog', 'jobs', 'gallery', 'reels'], true) ? $_GET['tab'] : 'applications';
$actionError = $_GET['error'] ?? '';

// ---- File download (CV / cover letter), streamed through PHP so the
//      uploads/careers/ folder itself stays blocked from direct access ----
if (isset($_GET['download'])) {
    $id = (int) $_GET['download'];
    $type = $_GET['type'] === 'cover' ? 'cover_letter_filename' : 'cv_filename';
    $stmt = $conn->prepare("SELECT {$type} AS fname, full_name FROM codebridge_job_applications WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row || !$row['fname']) {
        http_response_code(404);
        die('File not found.');
    }
    $path = __DIR__ . '/uploads/careers/' . $row['fname'];
    if (!is_file($path)) {
        http_response_code(404);
        die('File not found.');
    }
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $downloadName = preg_replace('/[^a-zA-Z0-9_ -]/', '', $row['full_name']) . '-' . ($type === 'cv_filename' ? 'CV' : 'CoverLetter') . '.' . $ext;
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $downloadName . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}

// ---- Application status update ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];
    $allowed = ['new', 'reviewed', 'shortlisted', 'rejected', 'hired'];
    if (in_array($status, $allowed, true)) {
        $stmt = $conn->prepare('UPDATE codebridge_job_applications SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
    }
    header('Location: careers-admin.php' . (isset($_GET['position']) ? '?position=' . urlencode($_GET['position']) : ''));
    exit;
}

// ---- CSV export ----
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="codebridge-applications.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID', 'Position', 'Name', 'Email', 'Phone', 'Status', 'Applied At']);
    $res = $conn->query('SELECT id, position, full_name, email, phone, status, created_at FROM codebridge_job_applications ORDER BY created_at DESC');
    while ($row = $res->fetch_assoc()) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}

// ---- Applications list ----
$positionFilter = $_GET['position'] ?? '';
if ($positionFilter !== '') {
    $stmt = $conn->prepare('SELECT * FROM codebridge_job_applications WHERE position = ? ORDER BY created_at DESC');
    $stmt->bind_param('s', $positionFilter);
    $stmt->execute();
    $applications = $stmt->get_result();
} else {
    $applications = $conn->query('SELECT * FROM codebridge_job_applications ORDER BY created_at DESC');
}
$positions = $conn->query('SELECT DISTINCT position FROM codebridge_job_applications ORDER BY position');

// ---- Blog: list + edit-target ----
$blogPosts = $conn->query('SELECT * FROM codebridge_blog_posts ORDER BY created_at DESC');
$editBlog = null;
if (isset($_GET['edit_blog'])) {
    $eid = (int) $_GET['edit_blog'];
    $stmt = $conn->prepare('SELECT * FROM codebridge_blog_posts WHERE id = ?');
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $editBlog = $stmt->get_result()->fetch_assoc();
}

// ---- Jobs: list + edit-target ----
$jobsList = $conn->query('SELECT * FROM codebridge_jobs ORDER BY created_at DESC');
$editJob = null;
if (isset($_GET['edit_job'])) {
    $eid = (int) $_GET['edit_job'];
    $stmt = $conn->prepare('SELECT * FROM codebridge_jobs WHERE id = ?');
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $editJob = $stmt->get_result()->fetch_assoc();
}

// ---- Gallery: list ----
$galleryPhotos = $conn->query('SELECT * FROM codebridge_gallery ORDER BY created_at DESC');

// ---- Reels: list (table may not exist yet until the first upload) ----
$reelsList = @$conn->query('SELECT * FROM codebridge_reels ORDER BY created_at DESC') ?: null;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Codebridge Admin</title>
<meta name="robots" content="noindex, nofollow">
<style>
  body{font-family:system-ui,sans-serif;background:#f2f3f7;margin:0;color:#1a1a2e;}
  header{background:#1a1a4d;color:#fff;padding:18px 28px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
  header h1{font-size:16px;margin:0;font-weight:700;}
  header a{color:#fff;opacity:.8;text-decoration:none;font-size:13px;}
  nav.tabs{background:#fff;border-bottom:1px solid #e2e5ec;display:flex;gap:4px;padding:0 28px;}
  nav.tabs a{padding:14px 16px;font-size:13px;font-weight:700;color:#666;text-decoration:none;border-bottom:2px solid transparent;}
  nav.tabs a.active{color:#1a1a4d;border-bottom-color:#ee3a2f;}
  main{padding:24px 28px;max-width:1200px;}
  .toolbar{display:flex;gap:12px;align-items:center;margin-bottom:16px;flex-wrap:wrap;}
  select,.btn,input[type=text],input[type=url],input[type=file],textarea{padding:8px 14px;border-radius:8px;border:1px solid #dfe3ea;font-size:13px;background:#fff;font-family:inherit;}
  .btn{background:#1a1a4d;color:#fff;text-decoration:none;border:none;cursor:pointer;display:inline-block;}
  .btn-danger{background:#c0392b;}
  .btn-ghost{background:#fff;color:#1a1a4d;border:1px solid #dfe3ea;}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(20,20,58,.06);margin-bottom:28px;}
  th,td{padding:10px 14px;text-align:left;font-size:13px;border-bottom:1px solid #eef0f5;vertical-align:top;}
  th{background:#f7f8fb;font-weight:700;color:#555;text-transform:uppercase;font-size:11px;letter-spacing:.04em;}
  .status{padding:4px 10px;border-radius:999px;font-size:11px;font-weight:700;text-transform:uppercase;}
  .status-new{background:#e8eaf6;color:#3949ab;}
  .status-reviewed{background:#fff3e0;color:#e65100;}
  .status-shortlisted{background:#e3f2fd;color:#1565c0;}
  .status-rejected{background:#ffebee;color:#c62828;}
  .status-hired{background:#e8f5e9;color:#2e7d32;}
  .status-published,.status-active{background:#e8f5e9;color:#2e7d32;}
  .status-draft,.status-closed{background:#f1f1f4;color:#666;}
  .links a{margin-right:10px;font-size:12px;color:#1a1a4d;}
  .empty{padding:40px;text-align:center;color:#888;background:#fff;border-radius:12px;margin-bottom:28px;}
  .panel{background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 10px rgba(20,20,58,.06);margin-bottom:28px;}
  .panel h2{font-size:15px;margin:0 0 18px;}
  .field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px;}
  .field-full{margin-bottom:14px;}
  .field-row label,.field-full label{display:block;font-size:11px;font-weight:700;color:#666;text-transform:uppercase;margin-bottom:6px;}
  .field-row input,.field-row select,.field-full input,.field-full select,.field-full textarea{width:100%;box-sizing:border-box;}
  textarea{min-height:80px;resize:vertical;}
  .thumb{width:44px;height:44px;object-fit:cover;border-radius:6px;}
  .gallery-grid-admin{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;}
  .gallery-grid-admin .card{background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(20,20,58,.06);}
  .gallery-grid-admin img{width:100%;height:110px;object-fit:cover;display:block;}
  .gallery-grid-admin .card-body{padding:10px 12px;}
  .gallery-grid-admin .card-body strong{font-size:12px;display:block;margin-bottom:4px;}
  .alert-err{background:#ffebee;color:#c0392b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;}
</style>
</head>
<body>
<header>
  <h1>Codebridge Admin</h1>
  <a href="careers-admin.php?logout=1">Log out</a>
</header>
<nav class="tabs">
  <a href="careers-admin.php" class="<?= $tab === 'applications' ? 'active' : '' ?>">Applications</a>
  <a href="careers-admin.php?tab=blog" class="<?= $tab === 'blog' ? 'active' : '' ?>">Blog</a>
  <a href="careers-admin.php?tab=jobs" class="<?= $tab === 'jobs' ? 'active' : '' ?>">Jobs</a>
  <a href="careers-admin.php?tab=gallery" class="<?= $tab === 'gallery' ? 'active' : '' ?>">Gallery</a>
  <a href="careers-admin.php?tab=reels" class="<?= $tab === 'reels' ? 'active' : '' ?>">Reels</a>
</nav>
<main>
  <?php if ($actionError): ?><div class="alert-err"><?= htmlspecialchars($actionError) ?></div><?php endif; ?>

  <?php if ($tab === 'applications'): ?>
    <div class="toolbar">
      <form method="get">
        <select name="position" onchange="this.form.submit()">
          <option value="">All positions</option>
          <?php while ($p = $positions->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($p['position']) ?>" <?= $positionFilter === $p['position'] ? 'selected' : '' ?>><?= htmlspecialchars($p['position']) ?></option>
          <?php endwhile; ?>
        </select>
      </form>
      <a class="btn" href="careers-admin.php?export=csv<?= $positionFilter !== '' ? '&position=' . urlencode($positionFilter) : '' ?>">Export CSV</a>
    </div>

    <table>
      <thead>
        <tr><th>Applicant</th><th>Position</th><th>Contact</th><th>Applied</th><th>Files</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php $count = 0; while ($row = $applications->fetch_assoc()): $count++; ?>
          <tr>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?><br><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td class="links">
              <?php if ($row['cv_filename']): ?><a href="careers-admin.php?download=<?= $row['id'] ?>&type=cv">CV</a><?php endif; ?>
              <?php if ($row['cover_letter_filename']): ?><a href="careers-admin.php?download=<?= $row['id'] ?>&type=cover">Cover letter</a><?php endif; ?>
            </td>
            <td>
              <form method="post" style="display:flex;gap:6px;align-items:center;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <select name="status" class="status status-<?= htmlspecialchars($row['status']) ?>">
                  <?php foreach (['new','reviewed','shortlisted','rejected','hired'] as $s): ?>
                    <option value="<?= $s ?>" <?= $row['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn" type="submit" name="update_status" value="1" style="padding:4px 10px;">Save</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php if ($count === 0): ?><div class="empty">No applications yet.</div><?php endif; ?>

  <?php elseif ($tab === 'blog'): ?>
    <div class="panel">
      <h2><?= $editBlog ? 'Edit post' : 'New blog post' ?></h2>
      <form method="post" action="careers-backend/blog-save.php" enctype="multipart/form-data">
        <?php if ($editBlog): ?><input type="hidden" name="id" value="<?= $editBlog['id'] ?>"><?php endif; ?>
        <div class="field-row">
          <div>
            <label>Title</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($editBlog['title'] ?? '') ?>">
          </div>
          <div>
            <label>Badge (optional, e.g. "Hot" / "Trending now")</label>
            <input type="text" name="badge" value="<?= htmlspecialchars($editBlog['badge'] ?? '') ?>">
          </div>
        </div>
        <div class="field-full">
          <label>Excerpt (shown on the card)</label>
          <input type="text" name="excerpt" required value="<?= htmlspecialchars($editBlog['excerpt'] ?? '') ?>">
        </div>
        <div class="field-full">
          <label>Full body (optional, for a future full-article view)</label>
          <textarea name="body"><?= htmlspecialchars($editBlog['body'] ?? '') ?></textarea>
        </div>
        <div class="field-row">
          <div>
            <label>Image <?= $editBlog ? '(leave empty to keep current)' : '' ?></label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif">
          </div>
          <div>
            <label>Status</label>
            <select name="status">
              <option value="published" <?= ($editBlog['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
              <option value="draft" <?= ($editBlog['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
          </div>
        </div>
        <button class="btn" type="submit"><?= $editBlog ? 'Save changes' : 'Publish post' ?></button>
        <?php if ($editBlog): ?><a class="btn btn-ghost" href="careers-admin.php?tab=blog">Cancel</a><?php endif; ?>
      </form>
    </div>

    <table>
      <thead><tr><th></th><th>Title</th><th>Status</th><th>Published</th><th></th></tr></thead>
      <tbody>
        <?php $count = 0; while ($row = $blogPosts->fetch_assoc()): $count++; ?>
          <tr>
            <td><?php if ($row['image_filename']): ?><img class="thumb" src="uploads/blog/<?= htmlspecialchars($row['image_filename']) ?>" alt=""><?php endif; ?></td>
            <td><strong><?= htmlspecialchars($row['title']) ?></strong><br><span style="color:#888;"><?= htmlspecialchars($row['excerpt']) ?></span></td>
            <td><span class="status status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td class="links">
              <a href="careers-admin.php?tab=blog&edit_blog=<?= $row['id'] ?>">Edit</a>
              <form method="post" action="careers-backend/blog-delete.php" style="display:inline;" onsubmit="return confirm('Delete this post?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" style="background:none;border:none;color:#c0392b;font-size:12px;cursor:pointer;padding:0;">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php if ($count === 0): ?><div class="empty">No blog posts yet — add your first one above.</div><?php endif; ?>

  <?php elseif ($tab === 'jobs'): ?>
    <div class="panel">
      <h2><?= $editJob ? 'Edit job posting' : 'New job posting' ?></h2>
      <form method="post" action="careers-backend/jobs-save.php">
        <?php if ($editJob): ?><input type="hidden" name="id" value="<?= $editJob['id'] ?>"><?php endif; ?>
        <div class="field-row">
          <div>
            <label>Job title</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($editJob['title'] ?? '') ?>">
          </div>
          <div>
            <label>Meta line (e.g. "React · Node.js · Remote · Kigali")</label>
            <input type="text" name="meta_line" value="<?= htmlspecialchars($editJob['meta_line'] ?? '') ?>">
          </div>
        </div>
        <div class="field-row">
          <div>
            <label>Category</label>
            <select name="category">
              <?php foreach (['dev' => 'Development', 'uiux' => 'UI/UX', 'aiml' => 'AI/ML'] as $val => $lbl): ?>
                <option value="<?= $val ?>" <?= ($editJob['category'] ?? 'dev') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Type</label>
            <select name="job_type">
              <option value="job" <?= ($editJob['job_type'] ?? 'job') === 'job' ? 'selected' : '' ?>>Job</option>
              <option value="internship" <?= ($editJob['job_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Internship</option>
            </select>
          </div>
        </div>
        <div class="field-full">
          <label>Short description</label>
          <textarea name="description"><?= htmlspecialchars($editJob['description'] ?? '') ?></textarea>
        </div>
        <div class="field-full">
          <label>Requirements (one per line)</label>
          <textarea name="requirements"><?= htmlspecialchars($editJob['requirements'] ?? '') ?></textarea>
        </div>
        <div class="field-full" style="max-width:200px;">
          <label>Status</label>
          <select name="status">
            <option value="active" <?= ($editJob['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="closed" <?= ($editJob['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
          </select>
        </div>
        <button class="btn" type="submit"><?= $editJob ? 'Save changes' : 'Post job' ?></button>
        <?php if ($editJob): ?><a class="btn btn-ghost" href="careers-admin.php?tab=jobs">Cancel</a><?php endif; ?>
      </form>
    </div>

    <table>
      <thead><tr><th>Title</th><th>Category</th><th>Type</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php $count = 0; while ($row = $jobsList->fetch_assoc()): $count++; ?>
          <tr>
            <td><strong><?= htmlspecialchars($row['title']) ?></strong><br><span style="color:#888;"><?= htmlspecialchars($row['meta_line']) ?></span></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['job_type']) ?></td>
            <td><span class="status status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
            <td class="links">
              <a href="careers-admin.php?tab=jobs&edit_job=<?= $row['id'] ?>">Edit</a>
              <form method="post" action="careers-backend/jobs-delete.php" style="display:inline;" onsubmit="return confirm('Delete this job posting?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" style="background:none;border:none;color:#c0392b;font-size:12px;cursor:pointer;padding:0;">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php if ($count === 0): ?><div class="empty">No job postings yet — add your first one above.</div><?php endif; ?>

  <?php elseif ($tab === 'gallery'): ?>
    <div class="panel">
      <h2>Upload a photo</h2>
      <form method="post" action="careers-backend/gallery-save.php" enctype="multipart/form-data">
        <div class="field-row">
          <div>
            <label>Title</label>
            <input type="text" name="title" required>
          </div>
          <div>
            <label>Tag (optional, e.g. "Project case")</label>
            <input type="text" name="tag">
          </div>
        </div>
        <div class="field-row">
          <div>
            <label>Image</label>
            <input type="file" name="image" required accept=".jpg,.jpeg,.png,.webp,.gif">
          </div>
          <div>
            <label>Status</label>
            <select name="status">
              <option value="published">Published</option>
              <option value="draft">Draft</option>
            </select>
          </div>
        </div>
        <button class="btn" type="submit">Upload</button>
      </form>
    </div>

    <div class="gallery-grid-admin">
      <?php $count = 0; while ($row = $galleryPhotos->fetch_assoc()): $count++; ?>
        <div class="card">
          <img src="uploads/gallery/<?= htmlspecialchars($row['image_filename']) ?>" alt="">
          <div class="card-body">
            <strong><?= htmlspecialchars($row['title']) ?></strong>
            <span class="status status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
            <form method="post" action="careers-backend/gallery-delete.php" onsubmit="return confirm('Delete this photo?');" style="margin-top:8px;">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" style="background:none;border:none;color:#c0392b;font-size:12px;cursor:pointer;padding:0;">Delete</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <?php if ($count === 0): ?><div class="empty">No photos yet — upload your first one above.</div><?php endif; ?>

  <?php elseif ($tab === 'reels'): ?>
    <div class="panel">
      <h2>Upload a Technical Reel</h2>
      <form method="post" action="careers-backend/reels-save.php" enctype="multipart/form-data">
        <div class="field-row">
          <div>
            <label>Title</label>
            <input type="text" name="title" required>
          </div>
          <div>
            <label>Tag (optional, e.g. "Core Group Ltd")</label>
            <input type="text" name="tag">
          </div>
        </div>
        <div class="field-full">
          <label>YouTube link (watch / shorts / youtu.be) or video ID</label>
          <input type="text" name="youtube_url" required placeholder="https://www.youtube.com/watch?v=...">
        </div>
        <div class="field-row">
          <div>
            <label>Poster image</label>
            <input type="file" name="image" required accept=".jpg,.jpeg,.png,.webp,.gif">
          </div>
          <div>
            <label>Status</label>
            <select name="status">
              <option value="published">Published</option>
              <option value="draft">Draft</option>
            </select>
          </div>
        </div>
        <button class="btn" type="submit">Upload</button>
      </form>
    </div>

    <div class="gallery-grid-admin">
      <?php $count = 0; if ($reelsList): while ($row = $reelsList->fetch_assoc()): $count++; ?>
        <div class="card">
          <img src="uploads/reels/<?= htmlspecialchars($row['image_filename']) ?>" alt="">
          <div class="card-body">
            <strong><?= htmlspecialchars($row['title']) ?></strong><br>
            <span style="color:#888;font-size:11px;">youtube.com/watch?v=<?= htmlspecialchars($row['youtube_id']) ?></span><br>
            <span class="status status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
            <form method="post" action="careers-backend/reels-delete.php" onsubmit="return confirm('Delete this reel?');" style="margin-top:8px;">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" style="background:none;border:none;color:#c0392b;font-size:12px;cursor:pointer;padding:0;">Delete</button>
            </form>
          </div>
        </div>
      <?php endwhile; endif; ?>
    </div>
    <?php if ($count === 0): ?><div class="empty">No reels yet — upload your first one above.</div><?php endif; ?>
  <?php endif; ?>
</main>
</body>
</html>
