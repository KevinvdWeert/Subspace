<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
require_admin();

$pdo = Db::pdo();

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action = (string)($_POST['action'] ?? '');
    if ($action !== 'toggle_hide') {
        http_response_code(400);
        exit('Bad Request');
    }

    $postId = (int)($_POST['post_id'] ?? 0);
    if ($postId <= 0) {
        redirect('admin/posts.php');
    }

    $stmt = $pdo->prepare('UPDATE posts SET is_hidden = 1 - is_hidden, updated_at = :updated_at WHERE id = :id');
    $stmt->execute([':updated_at' => now_datetime(), ':id' => $postId]);
    redirect('admin/posts.php');
}

require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query(
    'SELECT p.id, p.content, p.created_at, p.is_hidden, u.username
     FROM posts p
     JOIN users u ON u.id = p.user_id
     ORDER BY p.created_at DESC
     LIMIT 100'
);
$posts = $stmt->fetchAll();
?>

<h1 class="mb-4">Moderatie — Posts</h1>

<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Content</th>
            <th>Status</th>
            <th>Actie</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= (int)$post['id'] ?></td>
                <td><?= e($post['username']) ?></td>
                <td><?= e(mb_strimwidth((string)$post['content'], 0, 80, '…', 'UTF-8')) ?></td>
                <td>
                    <?php if ((int)$post['is_hidden'] === 1): ?>
                        <span class="badge badge-warning">Hidden</span>
                    <?php else: ?>
                        <span class="badge badge-success">Visible</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a class="btn btn-sm btn-link" href="<?= e(url('post.php?id=' . (int)$post['id'])) ?>">Open</a>
                    <form method="post" action="<?= e(url('admin/posts.php')) ?>" class="d-inline">
                        <input type="hidden" name="action" value="toggle_hide">
                        <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                        <button class="btn btn-sm btn-outline-secondary" type="submit">
                            <?= ((int)$post['is_hidden'] === 1) ? 'Unhide' : 'Hide' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
