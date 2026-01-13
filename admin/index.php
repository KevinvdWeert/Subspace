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

<h1 class="mb-4">Admin dashboard</h1>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card"><div class="card-body"><div class="text-muted">Users</div><div class="h3 mb-0"><?= $users ?></div></div></div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card"><div class="card-body"><div class="text-muted">Posts</div><div class="h3 mb-0"><?= $posts ?></div></div></div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card"><div class="card-body"><div class="text-muted">Comments</div><div class="h3 mb-0"><?= $comments ?></div></div></div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card"><div class="card-body"><div class="text-muted">Likes</div><div class="h3 mb-0"><?= $likes ?></div></div></div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card"><div class="card-body"><div class="text-muted">Blocked users</div><div class="h3 mb-0"><?= $blocked ?></div></div></div>
    </div>
</div>

<div class="mt-4">
    <a class="btn btn-outline-primary" href="<?= e(url('admin/posts.php')) ?>">Moderatie: posts</a>
    <a class="btn btn-outline-primary" href="<?= e(url('admin/users.php')) ?>">Users: blokkeren</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
