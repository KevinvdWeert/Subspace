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

$current = current_user();
$currentId = $current ? (int)$current['id'] : 0;

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action = (string)($_POST['action'] ?? '');
    if ($action !== 'toggle_block') {
        http_response_code(400);
        exit('Bad Request');
    }

    $targetUserId = (int)($_POST['user_id'] ?? 0);
    if ($targetUserId <= 0) {
        redirect('/admin/users.php');
    }
    if ($targetUserId === $currentId) {
        redirect('/admin/users.php?err=self');
    }

    $stmt = $pdo->prepare(
        'SELECT id
         FROM user_blocks
         WHERE user_id = :user_id
           AND revoked_at IS NULL
           AND (blocked_until IS NULL OR blocked_until > NOW())
         ORDER BY created_at DESC
         LIMIT 1'
    );
    $stmt->execute([':user_id' => $targetUserId]);
    $blockId = $stmt->fetchColumn();

    if ($blockId) {
        $stmt = $pdo->prepare('UPDATE user_blocks SET revoked_at = :revoked_at WHERE id = :id');
        $stmt->execute([':revoked_at' => now_datetime(), ':id' => (int)$blockId]);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO user_blocks (user_id, blocked_by_admin_id, reason, blocked_until, created_at, revoked_at)
             VALUES (:user_id, :admin_id, :reason, NULL, :created_at, NULL)'
        );
        $stmt->execute([
            ':user_id' => $targetUserId,
            ':admin_id' => $currentId,
            ':reason' => 'Blocked by admin',
            ':created_at' => now_datetime(),
        ]);
    }

    redirect('/admin/users.php');
}

require_once __DIR__ . '/../includes/header.php';

if (isset($_GET['err']) && $_GET['err'] === 'self') {
    echo '<div class="error">' . e('Je kunt jezelf niet blokkeren.') . '</div>';
}

$stmt = $pdo->query(
    'SELECT id, username, email, role, created_at
     FROM users
     ORDER BY created_at DESC
     LIMIT 200'
);
$users = $stmt->fetchAll();

$activeBlocksStmt = $pdo->query(
    "SELECT user_id
     FROM user_blocks
     WHERE revoked_at IS NULL
       AND (blocked_until IS NULL OR blocked_until > NOW())"
);
$blockedUserIds = array_flip(array_map('intval', $activeBlocksStmt->fetchAll(PDO::FETCH_COLUMN)));

?>

<h1>Users — Blokkeren</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actie</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <?php
            $uid = (int)$u['id'];
            $isBlocked = isset($blockedUserIds[$uid]);
            ?>
            <tr>
                <td><?= $uid ?></td>
                <td><?= e($u['username']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['role']) ?></td>
                <td>
                    <?php if ($isBlocked): ?>
                        <span style="color: var(--reddit-orange);">Blocked</span>
                    <?php else: ?>
                        <span style="color: #46d169;">Active</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($uid === $currentId): ?>
                        <span class="text-muted">(jij)</span>
                    <?php else: ?>
                        <form method="post" action="<?= e(url('/admin/users.php')) ?>" style="display: inline;">
                            <input type="hidden" name="action" value="toggle_block">
                            <input type="hidden" name="user_id" value="<?= $uid ?>">
                            <button type="submit" class="secondary">
                                <?= $isBlocked ? 'Unblock' : 'Block' ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
