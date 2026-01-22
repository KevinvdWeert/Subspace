<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect als al ingelogd
if (current_user()) {
    redirect('/index.php');
}

$errors = [];
$old = [
    'username' => '',
    'email' => '',
];

// Behandel registratie formulier
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $passwordConfirm = (string)($_POST['password_confirm'] ?? '');

    $old['username'] = $username;
    $old['email'] = $email;

    // Valideer invoer
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

        // Controleer of username of email al in gebruik is
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetch()) {
            $errors['username'] = 'Username of e-mail is al in gebruik.';
        } else {
            // Maak nieuw account aan
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $now = now_datetime();

            // Begin transactie voor atomaire operatie
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

                // Maak leeg profiel aan voor nieuwe gebruiker
                $stmt = $pdo->prepare('INSERT INTO profiles (user_id, display_name, bio, avatar_url, updated_at) VALUES (:user_id, NULL, NULL, NULL, :updated_at)');
                $stmt->execute([':user_id' => $userId, ':updated_at' => $now]);

                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }

            // Log gebruiker automatisch in na registratie
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;
            redirect('/index.php');
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Registreren</h1>

                <form method="post" action="<?= e(url('/register.php')) ?>" novalidate>
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" type="text" required value="<?= e($old['username'] ?? '') ?>">
                        <?php if (!empty($errors['username'])): ?><div class="invalid-feedback"><?= e($errors['username']) ?></div><?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">E-mail</label>
                        <input class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" required value="<?= e($old['email'] ?? '') ?>">
                        <?php if (!empty($errors['email'])): ?><div class="invalid-feedback"><?= e($errors['email']) ?></div><?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Wachtwoord</label>
                        <input class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" type="password" required>
                        <?php if (!empty($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password']) ?></div><?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password_confirm">Wachtwoord herhalen</label>
                        <input class="form-control <?= !empty($errors['password_confirm']) ? 'is-invalid' : '' ?>" id="password_confirm" name="password_confirm" type="password" required>
                        <?php if (!empty($errors['password_confirm'])): ?><div class="invalid-feedback"><?= e($errors['password_confirm']) ?></div><?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Account maken</button>
                        <a class="btn btn-outline-secondary" href="<?= e(url('/login.php')) ?>">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
