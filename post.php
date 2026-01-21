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
    <div class="<?= e($notice['type'] === 'success' ? 'success' : 'error') ?>">
        <?= e($notice['message']) ?>
    </div>
<?php endif; ?>

<?php

$stmt = $pdo->prepare(
    'SELECT p.id, p.content, p.created_at, p.is_hidden,
            u.id AS user_id, u.username
     FROM posts p
     JOIN users u ON u.id = p.user_id
     WHERE p.id = :id
     LIMIT 1'
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

<a href="<?= e(url('/index.php')) ?>">&larr; Terug naar feed</a>

<div class="post">
    <div class="post-vote">
        <span><?= $likeCount ?></span>
    </div>
    <div class="post-content-wrapper">
        <div class="post-header">
            <div class="post-meta">
                <span><strong><?= e($post['username']) ?></strong></span>
                <span><?= e($post['created_at']) ?></span>
                <?php if ((int)$post['is_hidden'] === 1): ?>
                    <span class="error">Hidden</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="post-content"><?= nl2br(e($post['content'])) ?></div>

        <div class="post-footer">
            <form method="post" action="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>" style="display: inline;">
                <input type="hidden" name="action" value="like_toggle">
                <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                <button class="like-btn <?= $hasLiked ? 'liked' : '' ?>" type="submit" <?= $user ? '' : 'disabled' ?>>
                    <?= $likeCount ?>
                </button>
            </form>
        </div>
    </div>
</div>

<h2>Comments (<?= count($comments) ?>)</h2>

<?php if ($user): ?>
    <?php require_not_blocked(); ?>
    <form method="post" action="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">
        <input type="hidden" name="action" value="comment_create">
        <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
        <textarea name="content" rows="3" required placeholder="Plaats een reactie..."></textarea>
        <button type="submit">Plaats reactie</button>
    </form>
<?php else: ?>
    <div class="message">Login om te reageren.</div>
<?php endif; ?>

<?php foreach ($comments as $comment): ?>
    <div class="card">
        <div class="post-meta">
            <strong><?= e($comment['username']) ?></strong>
            <span class="text-muted"><?= e($comment['created_at']) ?></span>
            <?php if ((int)$comment['is_hidden'] === 1): ?>
                <span class="error">Hidden</span>
            <?php endif; ?>
        </div>
        <p><?= nl2br(e($comment['content'])) ?></p>
    </div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
