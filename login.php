<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (current_user()) {
    redirect('/index.php');
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
                redirect('/index.php');
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Login</h1>

                <?php if (!empty($errors['login']) && empty($errors['password'])): ?>
                    <div class="alert alert-danger" role="alert"><?= e($errors['login']) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= e(url('/login.php')) ?>" novalidate>
                    <div class="mb-3">
                        <label class="form-label" for="login">Username of e-mail</label>
                        <input class="form-control <?= !empty($errors['login']) ? 'is-invalid' : '' ?>" id="login" name="login" type="text" required value="<?= e($old['login'] ?? '') ?>">
                        <?php if (!empty($errors['login'])): ?><div class="invalid-feedback"><?= e($errors['login']) ?></div><?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Wachtwoord</label>
                        <input class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" type="password" required>
                        <?php if (!empty($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password']) ?></div><?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Inloggen</button>
                        <a class="btn btn-outline-secondary" href="<?= e(url('/register.php')) ?>">Registreren</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
