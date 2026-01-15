<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
require_admin();

require_once __DIR__ . '/../includes/header.php';

$pdo = Db::pdo();

$users = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$posts = (int)$pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
$comments = (int)$pdo->query('SELECT COUNT(*) FROM post_comments')->fetchColumn();
$likes = (int)$pdo->query('SELECT COUNT(*) FROM post_likes')->fetchColumn();

$blocked = (int)$pdo->query(
    "SELECT COUNT(DISTINCT user_id)
     FROM user_blocks
     WHERE revoked_at IS NULL
       AND (blocked_until IS NULL OR blocked_until > NOW())"
)->fetchColumn();
?>

<h1>Admin dashboard</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
    <div class="card">
        <p class="text-muted">Users</p>
        <h3><?= $users ?></h3>
    </div>
    <div class="card">
        <p class="text-muted">Posts</p>
        <h3><?= $posts ?></h3>
    </div>
    <div class="card">
        <p class="text-muted">Comments</p>
        <h3><?= $comments ?></h3>
    </div>
    <div class="card">
        <p class="text-muted">Likes</p>
        <h3><?= $likes ?></h3>
    </div>
</div>

<div class="card">
    <p class="text-muted">Blocked users</p>
    <h3><?= $blocked ?></h3>
</div>

<div style="margin-top: 20px;">
    <a href="<?= e(url('/admin/posts.php')) ?>">Moderatie: posts</a>
    <a href="<?= e(url('/admin/users.php')) ?>">Users: blokkeren</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
