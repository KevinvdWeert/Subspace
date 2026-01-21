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

$hasSpaceId = db_has_column('posts', 'space_id');

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_login();
    require_not_blocked();

    $action = (string)($_POST['action'] ?? '');

    if ($action === 'post_create') {
        $content = trim((string)($_POST['content'] ?? ''));
        if ($content === '' || strlen($content) > 2000) {
            redirect('/index.php?err=post');
        }

        $mediaUrl = null;
        try {
            // Prefer uploaded file if present, otherwise fall back to a remote URL.
            $mediaUrl = save_uploaded_image('image');
            if ($mediaUrl === null) {
                $mediaUrl = normalize_media_url($_POST['media_url'] ?? null);
            }
        } catch (RuntimeException $e) {
            redirect('/index.php?err=upload');
        }

        $spaceId = 0;
        if ($hasSpaceId) {
            $spaceId = (int)($_POST['space_id'] ?? 0);
            if ($spaceId < 0) {
                $spaceId = 0;
            }

            if ($spaceId > 0) {
                $stmt = $pdo->prepare('SELECT id, is_hidden FROM spaces WHERE id = :id');
                $stmt->execute([':id' => $spaceId]);
                $spaceRow = $stmt->fetch();

                if (!$spaceRow) {
                    redirect('/index.php?err=space');
                }

                if ((int)$spaceRow['is_hidden'] === 1 && !is_admin()) {
                    redirect('/index.php?err=space');
                }
            }
        }

        if ($hasSpaceId) {
            $stmt = $pdo->prepare(
                'INSERT INTO posts (user_id, space_id, content, link_url, media_url, is_hidden, created_at, updated_at)
                 VALUES (:user_id, :space_id, :content, NULL, :media_url, 0, :created_at, :updated_at)'
            );
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO posts (user_id, content, link_url, media_url, is_hidden, created_at, updated_at)
                 VALUES (:user_id, :content, NULL, :media_url, 0, :created_at, :updated_at)'
            );
        }

        $now = now_datetime();
        $params = [
            ':user_id' => (int)$user['id'],
            ':content' => $content,
            ':media_url' => $mediaUrl,
            ':created_at' => $now,
            ':updated_at' => $now,
        ];
        if ($hasSpaceId) {
            $params[':space_id'] = $spaceId > 0 ? $spaceId : null;
        }
        $stmt->execute($params);

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

// Test for notices

$notice = null;
if (isset($_GET['ok']) && $_GET['ok'] === 'post') {
    $notice = ['type' => 'success', 'message' => 'Post geplaatst.'];
} elseif (isset($_GET['err']) && $_GET['err'] === 'post') {
    $notice = ['type' => 'danger', 'message' => 'Post is leeg of te lang (max 2000).'];
} elseif (isset($_GET['err']) && $_GET['err'] === 'space') {
    $notice = ['type' => 'danger', 'message' => 'Ongeldige space geselecteerd.'];
} elseif (isset($_GET['err']) && $_GET['err'] === 'upload') {
    $notice = ['type' => 'danger', 'message' => 'Upload/URL failed. Upload a JPG/PNG/WebP/GIF up to 5MB, or paste a valid http(s) image URL.'];
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($notice): ?>
    <div class="alert alert-<?= e($notice['type']) ?>" role="alert">
        <?= e($notice['message']) ?>
    </div>
<?php endif; ?>

<h1 class="h3 mb-3">Feed</h1>

<?php
$isAdmin = is_admin() ? 1 : 0;

if ($hasSpaceId) {
    $stmt = $pdo->prepare(
    'SELECT p.id, p.content, p.media_url, p.created_at, p.space_id, u.username,
                s.title AS space_title,
                (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.id) AS like_count,
                (SELECT COUNT(*) FROM post_comments pc WHERE pc.post_id = p.id AND pc.is_hidden = 0) AS comment_count
         FROM posts p
         JOIN users u ON u.id = p.user_id
         LEFT JOIN spaces s ON s.id = p.space_id
         WHERE p.is_hidden = 0
           AND (p.space_id IS NULL OR s.id IS NOT NULL)
           AND (p.space_id IS NULL OR s.is_hidden = 0 OR :is_admin = 1)
         ORDER BY p.created_at DESC
         LIMIT 25'
    );
    $stmt->execute([':is_admin' => $isAdmin]);
} else {
    $stmt = $pdo->query(
    'SELECT p.id, p.content, p.media_url, p.created_at, u.username,
                (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.id) AS like_count,
                (SELECT COUNT(*) FROM post_comments pc WHERE pc.post_id = p.id AND pc.is_hidden = 0) AS comment_count
         FROM posts p
         JOIN users u ON u.id = p.user_id
         WHERE p.is_hidden = 0
         ORDER BY p.created_at DESC
         LIMIT 25'
    );
}

$posts = $stmt->fetchAll();
?>
    <?php require_not_blocked(); ?>
    <form class="card p-3 mb-3" method="post" action="<?= e(url('/index.php')) ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="post_create">
        <div class="mb-3">
            <label class="form-label" for="content">Create post</label>
            <textarea class="form-control" id="content" name="content" rows="3" required maxlength="2000" placeholder="Wat wil je delen?"></textarea>
        </div>
        <?php if ($hasSpaceId): ?>
            <?php
            $spacesForSelect = get_spaces(100, 0, is_admin());
            ?>
            <div class="mb-3">
                <label class="form-label" for="space_id">Post to</label>
                <select class="form-select" id="space_id" name="space_id">
                    <option value="0">Public feed (no Space)</option>
                    <?php foreach ($spacesForSelect as $space): ?>
                        <option value="<?= (int)$space['id'] ?>">
                            <?= e($space['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label class="form-label" for="image">Image (optional)</label>
            <input class="form-control" id="image" name="image" type="file" accept="image/*">
            <div class="form-text">JPG/PNG/WebP/GIF, max 5MB. Stored in assets/uploads.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="media_url">Or image URL (optional)</label>
            <input class="form-control" id="media_url" name="media_url" type="url" placeholder="Place a direct image URL here">
            <div class="form-text">Paste a direct http(s) image URL. If you upload a file, it wins over this URL.</div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Post</button>
            <a class="btn btn-outline-secondary" href="<?= e(url('/space.php')) ?>">Browse Spaces</a>
        </div>
    </form>

<?php if (!$posts): ?>
    <p class="text-muted">Nog geen posts.</p>
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
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-3">
                <div class="text-center subspace-vote-col">
                    <form method="post" action="<?= e(url('/index.php')) ?>" class="d-grid gap-1">
                        <input type="hidden" name="action" value="like_toggle">
                        <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                        <button class="btn btn-sm <?= $hasLiked ? 'btn-primary' : 'btn-outline-secondary' ?>" type="submit" <?= $user ? '' : 'disabled' ?>>
                            ▲
                        </button>
                    </form>
                    <div class="small fw-semibold mt-1"><?= (int)$post['like_count'] ?></div>
                    <button class="btn btn-sm btn-outline-secondary mt-1" disabled>▼</button>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center gap-2 small text-secondary mb-2">
                        <a class="fw-semibold text-decoration-none" href="<?= e(url('/profile.php?u=' . urlencode((string)$post['username']))) ?>">
                            <?= e($post['username']) ?>
                        </a>
                        <span>·</span>
                        <span><?= e($post['created_at']) ?></span>
                        <?php if ($hasSpaceId && (int)($post['space_id'] ?? 0) > 0 && !empty($post['space_title'])): ?>
                            <span>·</span>
                            <span>
                                in <a class="link-secondary" href="<?= e(url('/space.php?id=' . (int)$post['space_id'])) ?>"><?= e($post['space_title']) ?></a>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-2"><?= nl2br(e($post['content'])) ?></div>

                    <?php if (!empty($post['media_url'])): ?>
                        <img class="post-image img-fluid rounded border" src="<?= e(url((string)$post['media_url'])) ?>" alt="" loading="lazy">
                    <?php endif; ?>

                    <a class="btn btn-sm btn-link px-0" href="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">
                        💬 <?= (int)$post['comment_count'] ?> Comments
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>