<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (current_user()) {
    redirect('index.php');
}

$errors = [];
$old = ['login' => ''];

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $login = trim((string)($_POST['login'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    $old['login'] = $login;

    if ($login === '') {
        $errors['login'] = 'Vul je username of e-mail in.';
    }
    if ($password === '') {
        $errors['password'] = 'Vul je wachtwoord in.';
    }

    if (!$errors) {
        $pdo = Db::pdo();

        $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = :login_username OR email = :login_email LIMIT 1');
        $stmt->execute([':login_username' => $login, ':login_email' => $login]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, (string)$user['password_hash'])) {
            $errors['login'] = 'Onjuiste inloggegevens.';
        } else {
            $userId = (int)$user['id'];
            $block = active_user_block($userId);
            if ($block) {
                $errors['login'] = 'Je account is geblokkeerd.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $userId;
                redirect('index.php');
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Login</h1>

<form method="post" action="<?= e(url('login.php')) ?>" class="card card-body">
    <div class="form-group">
        <label for="login">Username of e-mail</label>
        <input id="login" name="login" class="form-control" required value="<?= e($old['login'] ?? '') ?>">
        <?php if (!empty($errors['login'])): ?><small class="text-danger"><?= e($errors['login']) ?></small><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input id="password" name="password" type="password" class="form-control" required>
        <?php if (!empty($errors['password'])): ?><small class="text-danger"><?= e($errors['password']) ?></small><?php endif; ?>
    </div>

    <button class="btn btn-primary" type="submit">Inloggen</button>

    <p class="mt-3 mb-0">
        Nog geen account? <a href="<?= e(url('register.php')) ?>">Registreren</a>
    </p>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
