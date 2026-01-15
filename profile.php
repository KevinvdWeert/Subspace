<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_login();
require_not_blocked();

$user = current_user();
$pdo = Db::pdo();

$errors = [];

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
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
            'UPDATE profiles
             SET display_name = :display_name,
                 bio = :bio,
                 avatar_url = :avatar_url,
                 updated_at = :updated_at
             WHERE user_id = :user_id'
        );
        $stmt->execute([
            ':display_name' => ($displayName === '' ? null : $displayName),
            ':bio' => ($bio === '' ? null : $bio),
            ':avatar_url' => ($avatarUrl === '' ? null : $avatarUrl),
            ':updated_at' => now_datetime(),
            ':user_id' => (int)$user['id'],
        ]);

        redirect('/profile.php?ok=1');
    }
}

require_once __DIR__ . '/includes/header.php';

if (isset($_GET['ok']) && $_GET['ok'] === '1') {
    echo '<div class="success">' . e('Profiel bijgewerkt.') . '</div>';
}

$stmt = $pdo->prepare(
    'SELECT u.id, u.username, u.email, u.role, u.created_at,
            p.display_name, p.bio, p.avatar_url
     FROM users u
     LEFT JOIN profiles p ON p.user_id = u.id
     WHERE u.id = :id'
);
$stmt->execute([':id' => (int)$user['id']]);
$profile = $stmt->fetch();

?>

<h1>Mijn profiel</h1>

<div class="profile-header">
    <h2><?= e($profile['display_name'] ?: $profile['username']) ?></h2>
    <p class="text-muted">@<?= e($profile['username']) ?></p>
    <p><?= nl2br(e($profile['bio'] ?? '')) ?></p>
</div>

<h2>Profiel bewerken</h2>
<form method="post" action="<?= e(url('/profile.php')) ?>">
    <div>
        <label for="display_name">Display name</label>
        <input id="display_name" name="display_name" type="text" value="<?= e($profile['display_name'] ?? '') ?>">
        <?php if (!empty($errors['display_name'])): ?><small class="error"><?= e($errors['display_name']) ?></small><?php endif; ?>
    </div>

    <div>
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" rows="4"><?= e($profile['bio'] ?? '') ?></textarea>
        <?php if (!empty($errors['bio'])): ?><small class="error"><?= e($errors['bio']) ?></small><?php endif; ?>
    </div>

    <div>
        <label for="avatar_url">Avatar URL (optioneel)</label>
        <input id="avatar_url" name="avatar_url" type="text" value="<?= e($profile['avatar_url'] ?? '') ?>">
        <?php if (!empty($errors['avatar_url'])): ?><small class="error"><?= e($errors['avatar_url']) ?></small><?php endif; ?>
    </div>

    <button type="submit">Opslaan</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
