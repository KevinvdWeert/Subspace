<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pdo = Db::pdo();
$viewer = current_user();

$requestedId = (int)($_GET['id'] ?? 0);
$requestedUsername = trim((string)($_GET['u'] ?? ''));

$targetId = $requestedId;
$targetUsername = $requestedUsername;

// Default route: /profile.php is "my profile" (requires login).
if ($targetId <= 0 && $targetUsername === '') {
    require_login();
    require_not_blocked();
    $viewer = current_user();
    $targetId = (int)($viewer['id'] ?? 0);
}

$errors = [];

// Allow edits only on your own profile.
$isEditing = false;
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_login();
    require_not_blocked();

    $viewer = current_user();
    $targetId = (int)($viewer['id'] ?? 0);
    $targetUsername = '';
    $isEditing = true;

    $displayName = trim((string)($_POST['display_name'] ?? ''));
    $bio = trim((string)($_POST['bio'] ?? ''));
    $avatarUrl = trim((string)($_POST['avatar_url'] ?? ''));

    if (strlen($displayName) > 100) {
        $errors['display_name'] = 'Display name is te lang (max 100).';
    }
    if (strlen($bio) > 1000) {
        $errors['bio'] = 'Bio is te lang (max 1000).';
    }
    if ($avatarUrl !== '' && !filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
        $errors['avatar_url'] = 'Avatar URL is ongeldig.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO profiles (user_id, display_name, bio, avatar_url, updated_at)
             VALUES (:user_id, :display_name, :bio, :avatar_url, :updated_at)
             ON DUPLICATE KEY UPDATE
                display_name = VALUES(display_name),
                bio = VALUES(bio),
                avatar_url = VALUES(avatar_url),
                updated_at = VALUES(updated_at)'
        );
        $stmt->execute([
            ':user_id' => $targetId,
            ':display_name' => ($displayName === '' ? null : $displayName),
            ':bio' => ($bio === '' ? null : $bio),
            ':avatar_url' => ($avatarUrl === '' ? null : $avatarUrl),
            ':updated_at' => now_datetime(),
        ]);

        redirect('/profile.php?ok=1');
    }
}

// Load target profile
if ($targetId > 0) {
    $stmt = $pdo->prepare(
        'SELECT u.id, u.username, u.email, u.role, u.created_at,
                p.display_name, p.bio, p.avatar_url
         FROM users u
         LEFT JOIN profiles p ON p.user_id = u.id
         WHERE u.id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $targetId]);
} else {
    $stmt = $pdo->prepare(
        'SELECT u.id, u.username, u.email, u.role, u.created_at,
                p.display_name, p.bio, p.avatar_url
         FROM users u
         LEFT JOIN profiles p ON p.user_id = u.id
         WHERE u.username = :username
         LIMIT 1'
    );
    $stmt->execute([':username' => $targetUsername]);
}

$profile = $stmt->fetch();
if (!$profile) {
    http_response_code(404);
    exit('User not found');
}

$isSelf = $viewer && (int)($viewer['id'] ?? 0) === (int)$profile['id'];
$canSeeHidden = $isSelf || is_admin();

$hasSpaceId = db_has_column('posts', 'space_id');

// Stats
$stmt = $pdo->prepare(
    'SELECT COUNT(*)
     FROM spaces
     WHERE user_id = :user_id
       AND (:can_see_hidden = 1 OR is_hidden = 0)'
);
$stmt->execute([
    ':user_id' => (int)$profile['id'],
    ':can_see_hidden' => $canSeeHidden ? 1 : 0,
]);
$spaceCount = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare(
    'SELECT COUNT(*)
     FROM posts
     WHERE user_id = :user_id
       AND (:can_see_hidden = 1 OR is_hidden = 0)'
);
$stmt->execute([
    ':user_id' => (int)$profile['id'],
    ':can_see_hidden' => $canSeeHidden ? 1 : 0,
]);
$postCount = (int)$stmt->fetchColumn();

$spacePostCountSelect = $hasSpaceId
        ? '(SELECT COUNT(*) FROM posts p WHERE p.space_id = s.id AND p.is_hidden = 0) AS post_count'
        : '0 AS post_count';

$stmt = $pdo->prepare(
        "SELECT s.id, s.title, s.subject, s.created_at, s.is_hidden,
                        {$spacePostCountSelect}
         FROM spaces s
         WHERE s.user_id = :user_id
             AND (:can_see_hidden = 1 OR s.is_hidden = 0)
         ORDER BY s.created_at DESC
         LIMIT 10"
);
$stmt->execute([
    ':user_id' => (int)$profile['id'],
    ':can_see_hidden' => $canSeeHidden ? 1 : 0,
]);
$spaces = $stmt->fetchAll();

$spaceSelect = $hasSpaceId ? 'p.space_id, s.title AS space_title' : 'NULL AS space_id, NULL AS space_title';
$joinSpace = $hasSpaceId ? 'LEFT JOIN spaces s ON s.id = p.space_id' : '';
$spaceVisibility = $hasSpaceId ? 'AND (p.space_id IS NULL OR s.id IS NOT NULL) AND (p.space_id IS NULL OR s.is_hidden = 0 OR :can_see_hidden = 1)' : '';

$stmt = $pdo->prepare(
        "SELECT p.id, p.content, p.media_url, p.created_at, p.is_hidden, {$spaceSelect}
     FROM posts p
     {$joinSpace}
     WHERE p.user_id = :user_id
       AND (:can_see_hidden = 1 OR p.is_hidden = 0)
       {$spaceVisibility}
     ORDER BY p.created_at DESC
     LIMIT 10"
);
$stmt->execute([
    ':user_id' => (int)$profile['id'],
    ':can_see_hidden' => $canSeeHidden ? 1 : 0,
]);
$posts = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<?php if (isset($_GET['ok']) && $_GET['ok'] === '1'): ?>
    <div class="alert alert-success" role="alert"><?= e('Profiel bijgewerkt.') ?></div>
<?php endif; ?>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <?php if (!empty($profile['avatar_url'])): ?>
                <img class="rounded-circle border subspace-avatar-img" src="<?= e($profile['avatar_url']) ?>" alt="Avatar of <?= e($profile['username']) ?>">
            <?php else: ?>
                <?php
                $initial = strtoupper(substr((string)($profile['display_name'] ?: $profile['username']), 0, 1));
                ?>
                <div class="subspace-avatar-fallback" aria-hidden="true"><?= e($initial) ?></div>
            <?php endif; ?>

            <div class="flex-grow-1">
                <h1 class="h4 mb-1"><?= e($profile['display_name'] ?: $profile['username']) ?></h1>
                <div class="text-secondary small">@<?= e($profile['username']) ?> · Member since <?= e(substr((string)$profile['created_at'], 0, 10)) ?></div>
                <?php if (!empty($profile['bio'])): ?>
                    <div class="mt-2"><?= nl2br(e($profile['bio'])) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-secondary small">Spaces</div>
                <div class="h4 mb-0"><?= $spaceCount ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-secondary small">Posts</div>
                <div class="h4 mb-0"><?= $postCount ?></div>
            </div>
        </div>
    </div>
</div>

<h2 class="h5">Spaces by <?= e($profile['username']) ?></h2>
<?php if (!$spaces): ?>
    <div class="alert alert-secondary" role="alert">No spaces yet.</div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4">
        <?php foreach ($spaces as $space): ?>
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h3 class="h6 mb-0">
                                <a class="text-decoration-none" href="<?= e(url('/space.php?id=' . (int)$space['id'])) ?>"><?= e($space['title']) ?></a>
                            </h3>
                            <?php if ($canSeeHidden && (int)$space['is_hidden'] === 1): ?>
                                <span class="badge text-bg-danger">Hidden</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-secondary small mt-1 mb-2"><?= e($space['subject']) ?></div>
                        <div class="small text-secondary"><?= (int)$space['post_count'] ?> posts · <?= e($space['created_at']) ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h2 class="h5">Recent activity</h2>
<?php if (!$posts): ?>
    <div class="alert alert-secondary" role="alert">No recent posts.</div>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="card shadow-sm mb-2">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center gap-2 small text-secondary mb-2">
                    <span><?= e($post['created_at']) ?></span>
                    <?php if ($hasSpaceId && (int)($post['space_id'] ?? 0) > 0 && !empty($post['space_title'])): ?>
                        <span>·</span>
                        <span>in <a class="link-secondary" href="<?= e(url('/space.php?id=' . (int)$post['space_id'])) ?>"><?= e($post['space_title']) ?></a></span>
                    <?php endif; ?>
                    <?php if ($canSeeHidden && (int)$post['is_hidden'] === 1): ?>
                        <span class="badge text-bg-danger">Hidden</span>
                    <?php endif; ?>
                </div>
                <div class="mb-2"><?= nl2br(e(substr((string)$post['content'], 0, 240))) ?><?= strlen((string)$post['content']) > 240 ? '…' : '' ?></div>
                <?php if (!empty($post['media_url'])): ?>
                    <img class="post-image img-fluid rounded border" src="<?= e(url((string)$post['media_url'])) ?>" alt="" loading="lazy">
                <?php endif; ?>
                <a class="btn btn-sm btn-link px-0" href="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">Open post →</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($isSelf): ?>
    <h2 class="h5 mt-4">Edit profile</h2>
    <form class="card shadow-sm" method="post" action="<?= e(url('/profile.php')) ?>" novalidate>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label" for="display_name">Display name</label>
                <input class="form-control <?= !empty($errors['display_name']) ? 'is-invalid' : '' ?>" id="display_name" name="display_name" type="text" maxlength="100" value="<?= e((string)($profile['display_name'] ?? '')) ?>">
                <?php if (!empty($errors['display_name'])): ?><div class="invalid-feedback"><?= e($errors['display_name']) ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label" for="bio">Bio</label>
                <textarea class="form-control <?= !empty($errors['bio']) ? 'is-invalid' : '' ?>" id="bio" name="bio" rows="4" maxlength="1000"><?= e((string)($profile['bio'] ?? '')) ?></textarea>
                <?php if (!empty($errors['bio'])): ?><div class="invalid-feedback"><?= e($errors['bio']) ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label" for="avatar_url">Avatar URL (optional)</label>
                <input class="form-control <?= !empty($errors['avatar_url']) ? 'is-invalid' : '' ?>" id="avatar_url" name="avatar_url" type="url" value="<?= e((string)($profile['avatar_url'] ?? '')) ?>" placeholder="https://...">
                <?php if (!empty($errors['avatar_url'])): ?><div class="invalid-feedback"><?= e($errors['avatar_url']) ?></div><?php endif; ?>
            </div>

            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
