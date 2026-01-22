<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pdo = Db::pdo();
$user = current_user();

$query = trim((string)($_GET['q'] ?? ''));
$type = trim((string)($_GET['type'] ?? 'all')); // alles, gebruikers, spaces, posts

$results = [
    'users' => [],
    'spaces' => [],
    'posts' => []
];

if ($query !== '' && strlen($query) >= 2) {
    $searchPattern = '%' . $query . '%';
    
    // Zoek gebruikers
    if ($type === 'all' || $type === 'users') {
        $stmt = $pdo->prepare(
            'SELECT u.id, u.username, u.created_at, p.display_name, p.avatar_url
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE u.username LIKE :query1 OR p.display_name LIKE :query2
             ORDER BY u.username ASC
             LIMIT 20'
        );
        $stmt->execute([
            ':query1' => $searchPattern,
            ':query2' => $searchPattern
        ]);
        $results['users'] = $stmt->fetchAll();
    }
    
    // Zoek spaces
    if ($type === 'all' || $type === 'spaces') {
        $hasSpaceId = db_has_column('posts', 'space_id');
        $postCountSelect = $hasSpaceId
            ? '(SELECT COUNT(*) FROM posts p WHERE p.space_id = s.id AND p.is_hidden = 0) AS post_count'
            : '0 AS post_count';
            
        $stmt = $pdo->prepare(
            "SELECT s.id, s.title, s.subject, s.description, s.created_at, s.is_hidden,
                    u.username, {$postCountSelect}
             FROM spaces s
             JOIN users u ON u.id = s.user_id
             WHERE (s.title LIKE :query1 OR s.subject LIKE :query2 OR s.description LIKE :query3)
               AND (s.is_hidden = 0 OR :is_admin = 1)
             ORDER BY s.created_at DESC
             LIMIT 20"
        );
        $stmt->execute([
            ':query1' => $searchPattern,
            ':query2' => $searchPattern,
            ':query3' => $searchPattern,
            ':is_admin' => is_admin() ? 1 : 0
        ]);
        $results['spaces'] = $stmt->fetchAll();
    }
    
    // Zoek posts
    if ($type === 'all' || $type === 'posts') {
        $hasSpaceId = db_has_column('posts', 'space_id');
        $spaceJoin = $hasSpaceId ? 'LEFT JOIN spaces sp ON sp.id = p.space_id' : '';
        $spaceSelect = $hasSpaceId ? 'p.space_id, sp.title AS space_title' : 'NULL AS space_id, NULL AS space_title';
        
        $isAdmin = is_admin() ? 1 : 0;
        $stmt = $pdo->prepare(
            "SELECT p.id, p.content, p.media_url, p.created_at, p.is_hidden,
                    u.username, {$spaceSelect},
                    (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.id) AS like_count,
                    (SELECT COUNT(*) FROM post_comments pc WHERE pc.post_id = p.id AND pc.is_hidden = 0) AS comment_count
             FROM posts p
             JOIN users u ON u.id = p.user_id
             {$spaceJoin}
             WHERE p.content LIKE :query
               AND (p.is_hidden = 0 OR {$isAdmin} = 1)
             ORDER BY p.created_at DESC
             LIMIT 20"
        );
        $stmt->execute([':query' => $searchPattern]);
        $results['posts'] = $stmt->fetchAll();
    }
}

// Bereken totaal aantal resultaten
$totalResults = count($results['users']) + count($results['spaces']) + count($results['posts']);

require_once __DIR__ . '/includes/header.php';
?>

<h1 class="h3 mb-3">Search</h1>

<form method="get" action="<?= e(url('/search.php')) ?>" class="card shadow-sm p-3 mb-4">
    <div class="row g-2">
        <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="q" value="<?= e($query) ?>" placeholder="Search users, spaces, posts..." required minlength="2">
        </div>
        <div class="col-12 col-md-4">
            <div class="d-flex gap-2">
                <select class="form-select" name="type">
                    <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="users" <?= $type === 'users' ? 'selected' : '' ?>>Users</option>
                    <option value="spaces" <?= $type === 'spaces' ? 'selected' : '' ?>>Spaces</option>
                    <option value="posts" <?= $type === 'posts' ? 'selected' : '' ?>>Posts</option>
                </select>
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </div>
</form>

<?php if ($query === ''): ?>
    <div class="alert alert-secondary" role="alert">
        Enter a search term (minimum 2 characters) to find users, spaces, or posts.
    </div>
<?php elseif ($totalResults === 0): ?>
    <div class="alert alert-secondary" role="alert">
        No results found for "<?= e($query) ?>".
    </div>
<?php else: ?>
    <div class="mb-3">
        <span class="text-secondary">Found <?= $totalResults ?> result<?= $totalResults !== 1 ? 's' : '' ?> for "<?= e($query) ?>"</span>
    </div>

    <?php if (!empty($results['users'])): ?>
        <h2 class="h5 mt-4 mb-3">Users (<?= count($results['users']) ?>)</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4">
            <?php foreach ($results['users'] as $searchUser): ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <?php if (!empty($searchUser['avatar_url'])): ?>
                                    <img class="rounded-circle border" src="<?= e($searchUser['avatar_url']) ?>" alt="" style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <?php $initial = strtoupper(substr((string)($searchUser['display_name'] ?: $searchUser['username']), 0, 1)); ?>
                                    <div class="rounded-circle border bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                        <?= e($initial) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold">
                                        <a class="text-decoration-none" href="<?= e(url('/profile.php?u=' . urlencode((string)$searchUser['username']))) ?>">
                                            <?= e($searchUser['display_name'] ?: $searchUser['username']) ?>
                                        </a>
                                    </div>
                                    <div class="small text-secondary">@<?= e($searchUser['username']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($results['spaces'])): ?>
        <h2 class="h5 mt-4 mb-3">Spaces (<?= count($results['spaces']) ?>)</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4">
            <?php foreach ($results['spaces'] as $space): ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h3 class="h6 mb-0">
                                    <a class="text-decoration-none" href="<?= e(url('/space.php?id=' . (int)$space['id'])) ?>">
                                        <?= e($space['title']) ?>
                                    </a>
                                </h3>
                                <?php if ((int)$space['is_hidden'] === 1): ?>
                                    <span class="badge text-bg-danger">Hidden</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-secondary small mt-1 mb-2"><?= e($space['subject']) ?></div>
                            <?php if (!empty($space['description'])): ?>
                                <div class="small mb-2"><?= e(substr((string)$space['description'], 0, 100)) ?><?= strlen((string)$space['description']) > 100 ? '...' : '' ?></div>
                            <?php endif; ?>
                            <div class="small text-secondary">
                                By <?= e($space['username']) ?> · <?= (int)$space['post_count'] ?> posts
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($results['posts'])): ?>
        <h2 class="h5 mt-4 mb-3">Posts (<?= count($results['posts']) ?>)</h2>
        <?php foreach ($results['posts'] as $post): ?>
            <div class="card shadow-sm mb-2">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-2 small text-secondary mb-2">
                        <a class="fw-semibold text-decoration-none" href="<?= e(url('/profile.php?u=' . urlencode((string)$post['username']))) ?>">
                            <?= e($post['username']) ?>
                        </a>
                        <span>·</span>
                        <span><?= e($post['created_at']) ?></span>
                        <?php if ((int)($post['space_id'] ?? 0) > 0 && !empty($post['space_title'])): ?>
                            <span>·</span>
                            <span>in <a class="link-secondary" href="<?= e(url('/space.php?id=' . (int)$post['space_id'])) ?>"><?= e($post['space_title']) ?></a></span>
                        <?php endif; ?>
                        <?php if ((int)$post['is_hidden'] === 1): ?>
                            <span class="badge text-bg-danger">Hidden</span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-2"><?= nl2br(e(substr((string)$post['content'], 0, 300))) ?><?= strlen((string)$post['content']) > 300 ? '…' : '' ?></div>
                    <?php if (!empty($post['media_url'])): ?>
                        <img class="post-image img-fluid rounded border" src="<?= e(url((string)$post['media_url'])) ?>" alt="" loading="lazy" style="max-height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="d-flex gap-3 mt-2">
                        <span class="small text-secondary"><i class="bi bi-heart"></i> <?= (int)$post['like_count'] ?></span>
                        <span class="small text-secondary"><i class="bi bi-chat-dots"></i> <?= (int)$post['comment_count'] ?></span>
                        <a class="btn btn-sm btn-link px-0" href="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">View post →</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
