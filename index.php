<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<?php
$user = current_user();
$pdo = Db::pdo();

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_login();
    require_not_blocked();

    $action = (string)($_POST['action'] ?? '');

    if ($action === 'post_create') {
        $content = trim((string)($_POST['content'] ?? ''));
        if ($content === '' || strlen($content) > 2000) {
            redirect('/index.php?err=post');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO posts (user_id, content, link_url, media_url, is_hidden, created_at, updated_at)
             VALUES (:user_id, :content, NULL, NULL, 0, :created_at, :updated_at)'
        );

        $now = now_datetime();
        $stmt->execute([
            ':user_id' => (int)$user['id'],
            ':content' => $content,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        redirect('/index.php?ok=post');
    }

    if ($action === 'like_toggle') {
        $postId = (int)($_POST['post_id'] ?? 0);
        if ($postId <= 0) {
            redirect('/index.php');
        }

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

        redirect('/index.php');
    }

    http_response_code(400);
    exit('Bad Request');
}

$notice = null;
if (isset($_GET['ok']) && $_GET['ok'] === 'post') {
    $notice = ['type' => 'success', 'message' => 'Post geplaatst.'];
} elseif (isset($_GET['err']) && $_GET['err'] === 'post') {
    $notice = ['type' => 'danger', 'message' => 'Post is leeg of te lang (max 2000).'];
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($notice): ?>
    <div class="alert alert-<?= e($notice['type']) ?>" role="alert">
        <?= e($notice['message']) ?>
    </div>
<?php endif; ?>

<h1 class="mb-4 bg-dark text-white py-3">Feed</h1>

<?php
$stmt = $pdo->query(
    'SELECT p.id, p.content, p.created_at, u.username,
            (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.id) AS like_count,
            (SELECT COUNT(*) FROM post_comments pc WHERE pc.post_id = p.id AND pc.is_hidden = 0) AS comment_count
     FROM posts p
     JOIN users u ON u.id = p.user_id
     WHERE p.is_hidden = 0
     ORDER BY p.created_at DESC
     LIMIT 25'
);
$posts = $stmt->fetchAll();
?>

<?php if (!$user): ?>
    <div class="alert alert-info bg-dark text-white py-3">
        Je bent niet ingelogd. <a href="<?= e(url('/login.php')) ?>">Login</a> of <a href="<?= e(url('/register.php')) ?>">registreer</a> om te posten.
    </div>
<?php else: ?>
    <?php require_not_blocked(); ?>
    <form method="post" action="<?= e(url('/index.php')) ?>" class="card card-body mb-4">
        <input type="hidden" name="action" value="post_create">
        <div class="form-group mb-2">
            <textarea name="content" class="form-control" rows="3" required placeholder="Wat wil je delen?"></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Post</button>
    </form>
<?php endif; ?>

<?php if (!$posts): ?>
    <p class="text-muted bg-dark text-white py-3">Nog geen posts.</p>
<?php endif; ?>

<?php foreach ($posts as $post): ?>
    <?php
    $hasLiked = false;
    if ($user) {
        $stmt = $pdo->prepare('SELECT 1 FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->execute([':post_id' => (int)$post['id'], ':user_id' => (int)$user['id']]);
        $hasLiked = (bool)$stmt->fetchColumn();
    }
    ?>
    <div class="card mb-3 bg-dark text-white py-3">
        <div class="card-body bg-dark text-white py-3">
            <div class="d-flex justify-content-between">
                <div>
                    <strong><?= e($post['username']) ?></strong>
                    <span class="text-muted">• <?= e($post['created_at']) ?></span>
                </div>
                <a class="btn btn-sm btn-link" href="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">Open</a>
            </div>

            <p class="mt-3 mb-3"><?= nl2br(e($post['content'])) ?></p>

            <div class="d-flex align-items-center">
                <form method="post" action="<?= e(url('/index.php')) ?>" class="mr-2">
                    <input type="hidden" name="action" value="like_toggle">
                    <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                    <button class="btn btn-sm <?= $hasLiked ? 'btn-secondary' : 'btn-outline-secondary' ?>" type="submit" <?= $user ? '' : 'disabled' ?>>
                        <?= $hasLiked ? 'Unlike' : 'Like' ?> (<?= (int)$post['like_count'] ?>)
                    </button>
                </form>

                <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">
                    Comments (<?= (int)$post['comment_count'] ?>)
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>