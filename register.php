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
$old = [
    'username' => '',
    'email' => '',
];

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $passwordConfirm = (string)($_POST['password_confirm'] ?? '');

    $old['username'] = $username;
    $old['email'] = $email;

    if ($username === '' || strlen($username) < 3) {
        $errors['username'] = 'Username moet minimaal 3 tekens zijn.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Vul een geldig e-mailadres in.';
    }
    if (strlen($password) < 8) {
        $errors['password'] = 'Wachtwoord moet minimaal 8 tekens zijn.';
    }
    if ($password !== $passwordConfirm) {
        $errors['password_confirm'] = 'Wachtwoorden komen niet overeen.';
    }

    if (!$errors) {
        $pdo = Db::pdo();

        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetch()) {
            $errors['username'] = 'Username of e-mail is al in gebruik.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $now = now_datetime();

            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO users (username, email, password_hash, role, created_at, updated_at)
                     VALUES (:username, :email, :password_hash, :role, :created_at, :updated_at)'
                );
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password_hash' => $passwordHash,
                    ':role' => 'user',
                    ':created_at' => $now,
                    ':updated_at' => $now,
                ]);

                $userId = (int)$pdo->lastInsertId();

                $stmt = $pdo->prepare('INSERT INTO profiles (user_id, display_name, bio, avatar_url, updated_at) VALUES (:user_id, NULL, NULL, NULL, :updated_at)');
                $stmt->execute([':user_id' => $userId, ':updated_at' => $now]);

                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }

            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;
            redirect('/index.php');
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Registreren</h1>

<form method="post" action="<?= e(url('/register.php')) ?>" class="card card-body">
    <div class="form-group">
        <label for="username">Username</label>
        <input id="username" name="username" class="form-control" required value="<?= e($old['username'] ?? '') ?>">
        <?php if (!empty($errors['username'])): ?><small class="text-danger"><?= e($errors['username']) ?></small><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" class="form-control" required value="<?= e($old['email'] ?? '') ?>">
        <?php if (!empty($errors['email'])): ?><small class="text-danger"><?= e($errors['email']) ?></small><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input id="password" name="password" type="password" class="form-control" required>
        <?php if (!empty($errors['password'])): ?><small class="text-danger"><?= e($errors['password']) ?></small><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="password_confirm">Wachtwoord herhalen</label>
        <input id="password_confirm" name="password_confirm" type="password" class="form-control" required>
        <?php if (!empty($errors['password_confirm'])): ?><small class="text-danger"><?= e($errors['password_confirm']) ?></small><?php endif; ?>
    </div>

    <button class="btn btn-primary" type="submit">Account maken</button>

    <p class="mt-3 mb-0">
        Heb je al een account? <a href="<?= e(url('/login.php')) ?>">Login</a>
    </p>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
