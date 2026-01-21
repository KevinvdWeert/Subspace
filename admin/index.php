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

<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-secondary small">Users</div>
                <div class="h4 mb-0"><?= $users ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-secondary small">Posts</div>
                <div class="h4 mb-0"><?= $posts ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-secondary small">Comments</div>
                <div class="h4 mb-0"><?= $comments ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-secondary small">Likes</div>
                <div class="h4 mb-0"><?= $likes ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="text-secondary small">Blocked users</div>
        <div class="h4 mb-0"><?= $blocked ?></div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2">
    <a class="btn btn-outline-secondary" href="<?= e(url('/admin/posts.php')) ?>">Moderatie: posts</a>
    <a class="btn btn-outline-secondary" href="<?= e(url('/admin/users.php')) ?>">Users: blokkeren</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
