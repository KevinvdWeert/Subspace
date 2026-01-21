<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$postId = (int)($_GET['id'] ?? 0);
if ($postId <= 0) {
    http_response_code(404);
    exit('Not Found');
}

$pdo = Db::pdo();

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_login();
    require_not_blocked();

    $user = current_user();
    $action = (string)($_POST['action'] ?? '');

    $stmt = $pdo->prepare('SELECT is_hidden FROM posts WHERE id = :id');
    $stmt->execute([':id' => $postId]);
    $row = $stmt->fetch();
    if (!$row) {
        redirect('/index.php');
    }
    if ((int)$row['is_hidden'] === 1 && !is_admin()) {
        http_response_code(403);
        exit('Forbidden');
    }

    if ($action === 'like_toggle') {
        $stmt = $pdo->prepare('SELECT 1 FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->execute([':post_id' => $postId, ':user_id' => (int)$user['id']]);
        $exists = (bool)$stmt->fetchColumn();

        if ($exists) {
            $stmt = $pdo->prepare('DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
            $stmt->execute([':post_id' => $postId, ':user_id' => (int)$user['id']]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO post_likes (post_id, user_id, created_at) VALUES (:post_id, :user_id, :created_at)');
            $stmt->execute([':post_id' => $postId, ':user_id' => (int)$user['id'], ':created_at' => now_datetime()]);
        }

        redirect('/post.php?id=' . $postId);
    }

    if ($action === 'comment_create') {
        $content = trim((string)($_POST['content'] ?? ''));
        if ($content === '' || strlen($content) > 2000) {
            redirect('/post.php?id=' . $postId . '&err=comment');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO post_comments (post_id, user_id, content, is_hidden, created_at)
             VALUES (:post_id, :user_id, :content, 0, :created_at)'
        );
        $stmt->execute([
            ':post_id' => $postId,
            ':user_id' => (int)$user['id'],
            ':content' => $content,
            ':created_at' => now_datetime(),
        ]);

        redirect('/post.php?id=' . $postId . '&ok=comment');
    }

    http_response_code(400);
    exit('Bad Request');
}

$notice = null;
if (isset($_GET['ok']) && $_GET['ok'] === 'comment') {
    $notice = ['type' => 'success', 'message' => 'Reactie geplaatst.'];
} elseif (isset($_GET['err']) && $_GET['err'] === 'comment') {
    $notice = ['type' => 'danger', 'message' => 'Comment is leeg of te lang (max 2000).'];
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($notice): ?>
    <div class="alert alert-<?= e($notice['type']) ?>" role="alert"><?= e($notice['message']) ?></div>
<?php endif; ?>

<?php

$hasSpaceId = db_has_column('posts', 'space_id');
$spaceIdSelect = $hasSpaceId ? 'p.space_id' : 'NULL AS space_id';

$stmt = $pdo->prepare(
    "SELECT p.id, p.content, p.media_url, p.created_at, p.is_hidden, {$spaceIdSelect},
            u.id AS user_id, u.username
     FROM posts p
     JOIN users u ON u.id = p.user_id
     WHERE p.id = :id
     LIMIT 1"
);
$stmt->execute([':id' => $postId]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    exit('Not Found');
}

if ((int)$post['is_hidden'] === 1 && !is_admin()) {
    http_response_code(404);
    exit('Not Found');
}

$stmt = $pdo->prepare(
    'SELECT c.id, c.content, c.created_at, c.is_hidden, u.username
     FROM post_comments c
     JOIN users u ON u.id = c.user_id
     WHERE c.post_id = :post_id
       AND (c.is_hidden = 0 OR :is_admin = 1)
     ORDER BY c.created_at ASC'
);
$stmt->execute([
    ':post_id' => $postId,
    ':is_admin' => is_admin() ? 1 : 0,
]);
$comments = $stmt->fetchAll();

$user = current_user();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM post_likes WHERE post_id = :post_id');
$stmt->execute([':post_id' => $postId]);
$likeCount = (int)$stmt->fetchColumn();

$hasLiked = false;
if ($user) {
    $stmt = $pdo->prepare('SELECT 1 FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
    $stmt->execute([':post_id' => $postId, ':user_id' => (int)$user['id']]);
    $hasLiked = (bool)$stmt->fetchColumn();
}
?>

<a class="btn btn-sm btn-link px-0 mb-2" href="<?= e(url('/index.php')) ?>">&larr; Terug naar feed</a>

<?php if ((int)($post['space_id'] ?? 0) > 0): ?>
    <?php 
    $spaceStmt = $pdo->prepare('SELECT title FROM spaces WHERE id = :id');
    $spaceStmt->execute([':id' => (int)$post['space_id']]);
    $space = $spaceStmt->fetch();
    ?>
    <?php if ($space): ?>
        <div class="alert alert-secondary py-2" role="alert">
            <a class="text-decoration-none" href="<?= e(url('/space.php?id=' . (int)$post['space_id'])) ?>">
                ← Terug naar <?= e($space['title']) ?>
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center gap-2 small text-secondary mb-2">
            <a class="fw-semibold text-decoration-none" href="<?= e(url('/profile.php?u=' . urlencode((string)$post['username']))) ?>"><?= e($post['username']) ?></a>
            <span>·</span>
            <span><?= e($post['created_at']) ?></span>
            <?php if ((int)$post['is_hidden'] === 1): ?>
                <span class="badge text-bg-danger">Hidden</span>
            <?php endif; ?>
        </div>

        <div class="mb-2"><?= nl2br(e($post['content'])) ?></div>

        <?php if (!empty($post['media_url'])): ?>
            <img class="post-image img-fluid rounded border" src="<?= e(url((string)$post['media_url'])) ?>" alt="" loading="lazy">
        <?php endif; ?>
    </div>
    <div class="card-footer bg-transparent d-flex align-items-center gap-2">
        <form method="post" action="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>" class="m-0">
            <input type="hidden" name="action" value="like_toggle">
            <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
            <button class="btn btn-sm <?= $hasLiked ? 'btn-primary' : 'btn-outline-secondary' ?>" type="submit" <?= $user ? '' : 'disabled' ?>>
                ▲ Like (<?= $likeCount ?>)
            </button>
        </form>
        <?php if (!$user): ?>
            <span class="small text-secondary">Login to like.</span>
        <?php endif; ?>
    </div>
</div>

<h2 class="h5 mb-3">Comments (<?= count($comments) ?>)</h2>

<?php if ($user): ?>
    <?php require_not_blocked(); ?>
    <form class="card shadow-sm mb-3" method="post" action="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">
        <input type="hidden" name="action" value="comment_create">
        <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
        <div class="card-body">
            <label class="form-label" for="content">Plaats een reactie</label>
            <textarea class="form-control" id="content" name="content" rows="3" required maxlength="2000" placeholder="Plaats een reactie..."></textarea>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-primary" type="submit">Plaats reactie</button>
            </div>
        </div>
    </form>
<?php else: ?>
    <div class="alert alert-secondary" role="alert">Login om te reageren.</div>
<?php endif; ?>

<?php foreach ($comments as $comment): ?>
    <div class="card shadow-sm mb-2">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 small text-secondary mb-2">
                <a class="fw-semibold text-decoration-none" href="<?= e(url('/profile.php?u=' . urlencode((string)$comment['username']))) ?>"><?= e($comment['username']) ?></a>
                <span>·</span>
                <span><?= e($comment['created_at']) ?></span>
                <?php if ((int)$comment['is_hidden'] === 1): ?>
                    <span class="badge text-bg-danger">Hidden</span>
                <?php endif; ?>
            </div>
            <div><?= nl2br(e($comment['content'])) ?></div>
        </div>
    </div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
