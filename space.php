<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pdo = Db::pdo();
$user = current_user();

// Bepaal of we de overzichtspagina of detailpagina tonen
$spaceId = (int)($_GET['id'] ?? 0);
$action = (string)($_GET['action'] ?? 'overview');

// POST actions
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $postAction = (string)($_POST['action'] ?? '');
    
    if ($postAction === 'create_space') {
        require_login();
        require_not_blocked();
        
        $title = trim((string)($_POST['title'] ?? ''));
        $subject = trim((string)($_POST['subject'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        
        if (empty($title) || empty($subject)) {
            redirect('/space.php?err=validation');
        }
        
        if (strlen($title) > 100 || strlen($subject) > 255) {
            redirect('/space.php?err=toolong');
        }
        
        try {
            $newSpaceId = create_space(
                (int)$user['id'],
                $title,
                $subject,
                empty($description) ? null : $description
            );
            redirect('/space.php?id=' . $newSpaceId . '&ok=created');
        } catch (Exception $e) {
            redirect('/space.php?err=database');
        }
    }
    
    if ($postAction === 'create_post' && $spaceId > 0) {
        require_login();
        require_not_blocked();
        
        // Verify space exists
        $stmt = $pdo->prepare('SELECT id FROM spaces WHERE id = :id');
        $stmt->execute([':id' => $spaceId]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            exit('Space not found');
        }
        
        $content = trim((string)($_POST['content'] ?? ''));
        if (empty($content) || strlen($content) > 5000) {
            redirect('/space.php?id=' . $spaceId . '&err=post_validation');
        }
        
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO posts (user_id, space_id, content, is_hidden, created_at)
                 VALUES (:user_id, :space_id, :content, 0, :created_at)'
            );
            $stmt->execute([
                ':user_id' => (int)$user['id'],
                ':space_id' => $spaceId,
                ':content' => $content,
                ':created_at' => now_datetime(),
            ]);
            redirect('/space.php?id=' . $spaceId . '&ok=post_created');
        } catch (Exception $e) {
            redirect('/space.php?id=' . $spaceId . '&err=post_database');
        }
    }
    
    http_response_code(400);
    exit('Bad Request');
}

require_once __DIR__ . '/includes/header.php';

// Show space detail page
if ($spaceId > 0) {
    $space = get_space($spaceId);
    
    if (!$space) {
        http_response_code(404);
        exit('Space not found');
    }
    
    if ((int)$space['is_hidden'] === 1 && !is_admin() && ((int)($user['id'] ?? 0) !== (int)$space['user_id'])) {
        http_response_code(403);
        exit('Forbidden');
    }
    
    $notice = null;
    if (isset($_GET['ok']) && $_GET['ok'] === 'created') {
        $notice = ['type' => 'success', 'message' => 'Space aangemaakt.'];
    } elseif (isset($_GET['ok']) && $_GET['ok'] === 'post_created') {
        $notice = ['type' => 'success', 'message' => 'Post toegevoegd aan space.'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'post_validation') {
        $notice = ['type' => 'danger', 'message' => 'Post is leeg of te lang (max 5000).'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'post_database') {
        $notice = ['type' => 'danger', 'message' => 'Database error bij post aanmaken.'];
    }
    ?>

    <?php if ($notice): ?>
        <div class="<?= e($notice['type'] === 'success' ? 'success' : 'error') ?>">
            <?= e($notice['message']) ?>
        </div>
    <?php endif; ?>

    <a href="<?= e(url('/space.php')) ?>">&larr; Terug naar spaces</a>

    <div class="space-detail">
        <div class="space-header">
            <h1><?= e($space['title']) ?></h1>
            <p class="space-subject"><?= e($space['subject']) ?></p>
            <?php if (!empty($space['description'])): ?>
                <p class="space-description"><?= nl2br(e($space['description'])) ?></p>
            <?php endif; ?>
            <div class="space-meta">
                <span>Gemaakt door: <strong><?= e($space['username']) ?></strong></span>
                <span><?= e($space['created_at']) ?></span>
                <?php if ((int)$space['is_hidden'] === 1): ?>
                    <span class="error">Hidden</span>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($user): ?>
            <?php require_not_blocked(); ?>
            <div class="post-form-container">
                <h3>Post toevoegen aan <?= e($space['title']) ?></h3>
                <form method="post" action="<?= e(url('/space.php?id=' . $spaceId)) ?>">
                    <input type="hidden" name="action" value="create_post">
                    <textarea name="content" rows="4" required placeholder="Wat wil je delen in deze space?"></textarea>
                    <button type="submit">Post plaatsen</button>
                </form>
            </div>
        <?php else: ?>
            <div class="message">Login om posts toe te voegen.</div>
        <?php endif; ?>

        <h2>Posts in deze space</h2>
        <?php
        $posts = get_space_posts($spaceId);
        
        if (empty($posts)): ?>
            <div class="message">Geen posts in deze space.</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-content-wrapper">
                        <div class="post-header">
                            <div class="post-meta">
                                <span><strong><?= e($post['username']) ?></strong></span>
                                <span><?= e($post['created_at']) ?></span>
                            </div>
                        </div>

                        <div class="post-content"><?= nl2br(e($post['content'])) ?></div>

                        <div class="post-footer">
                            <a href="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">Details &rarr;</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php } else {
    // Show spaces overview
    $notice = null;
    if (isset($_GET['ok']) && $_GET['ok'] === 'created') {
        $notice = ['type' => 'success', 'message' => 'Space aangemaakt.'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'validation') {
        $notice = ['type' => 'danger', 'message' => 'Titel en onderwerp zijn verplicht.'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'toolong') {
        $notice = ['type' => 'danger', 'message' => 'Titel (max 100) of onderwerp (max 255) is te lang.'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'database') {
        $notice = ['type' => 'danger', 'message' => 'Database error bij space aanmaken.'];
    }
    ?>

    <?php if ($notice): ?>
        <div class="<?= e($notice['type'] === 'success' ? 'success' : 'error') ?>">
            <?= e($notice['message']) ?>
        </div>
    <?php endif; ?>

    <div class="spaces-overview">
        <h1>Spaces</h1>
        
        <?php if ($user): ?>
            <?php require_not_blocked(); ?>
            <div class="space-form-container">
                <h2>Nieuwe space aanmaken</h2>
                <form method="post" action="<?= e(url('/space.php')) ?>">
                    <input type="hidden" name="action" value="create_space">
                    <div class="form-group">
                        <label for="title">Titel:</label>
                        <input type="text" id="title" name="title" maxlength="100" required placeholder="Titel van de space">
                    </div>
                    <div class="form-group">
                        <label for="subject">Onderwerp:</label>
                        <input type="text" id="subject" name="subject" maxlength="255" required placeholder="Waar gaat deze space over?">
                    </div>
                    <div class="form-group">
                        <label for="description">Beschrijving (optioneel):</label>
                        <textarea id="description" name="description" rows="3" placeholder="Meer details over deze space..."></textarea>
                    </div>
                    <button type="submit">Space aanmaken</button>
                </form>
            </div>
        <?php else: ?>
            <div class="message">Login om een space aan te maken.</div>
        <?php endif; ?>

        <h2>Alle spaces</h2>
        <?php
        $spaces = get_spaces(20, 0, is_admin());
        
        if (empty($spaces)): ?>
            <div class="message">Geen spaces beschikbaar.</div>
        <?php else: ?>
            <div class="spaces-grid">
                <?php foreach ($spaces as $space): ?>
                    <div class="space-card">
                        <h3>
                            <a href="<?= e(url('/space.php?id=' . (int)$space['id'])) ?>">
                                <?= e($space['title']) ?>
                            </a>
                        </h3>
                        <p class="space-subject"><?= e($space['subject']) ?></p>
                        <?php if (!empty($space['description'])): ?>
                            <p class="space-description"><?= e(substr($space['description'], 0, 150)) ?><?= strlen($space['description']) > 150 ? '...' : '' ?></p>
                        <?php endif; ?>
                        <div class="space-stats">
                            <span>Door: <?= e($space['username']) ?></span>
                            <span><?= (int)$space['post_count'] ?> posts</span>
                            <span><?= e($space['created_at']) ?></span>
                        </div>
                        <?php if ((int)$space['is_hidden'] === 1): ?>
                            <span class="error">Hidden</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php }

require_once __DIR__ . '/includes/footer.php';
?>
