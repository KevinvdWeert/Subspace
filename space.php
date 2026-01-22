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

// Behandel AJAX verzoeken voor oneindige scroll
$isAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

if ($isAjax && $spaceId === 0) {
    // Retourneer alleen de space cards voor oneindige scroll
    $spaces = get_spaces($perPage, $offset, is_admin());
    
    if (empty($spaces)) {
        exit(''); // Geen resultaten meer
    }
    
    foreach ($spaces as $space): ?>
        <div class="col">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h3 class="h6 mb-0">
                            <a class="text-decoration-none" href="<?= e(url('/space.php?id=' . (int)$space['id'])) ?>"><?= e($space['title']) ?></a>
                        </h3>
                        <?php if ((int)$space['is_hidden'] === 1): ?>
                            <span class="badge text-bg-danger">Hidden</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-secondary small mt-1 mb-2"><?= e($space['subject']) ?></div>
                    <?php if (!empty($space['description'])): ?>
                        <div class="small mb-2"><?= e(substr((string)$space['description'], 0, 150)) ?><?= strlen((string)$space['description']) > 150 ? '...' : '' ?></div>
                    <?php endif; ?>
                    <div class="small text-secondary">Door: <?= e($space['username']) ?> · <?= (int)$space['post_count'] ?> posts · <?= e($space['created_at']) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach;
    exit;
}
$action = (string)($_GET['action'] ?? 'overview');

// POST acties
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
        
        if (!db_has_column('posts', 'space_id')) {
            redirect('/space.php?id=' . $spaceId . '&err=space_schema');
        }

        // Controleer of space bestaat
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

        $hasMediaUrl = db_has_column('posts', 'media_url');
        $mediaUrl = null;
        if ($hasMediaUrl) {
            try {
                $mediaUrl = save_uploaded_image('image');
                if ($mediaUrl === null) {
                    $mediaUrl = normalize_media_url($_POST['media_url'] ?? null);
                }
            } catch (RuntimeException $e) {
                redirect('/space.php?id=' . $spaceId . '&err=upload');
            }
        }
        
        try {
            if ($hasMediaUrl) {
                $stmt = $pdo->prepare(
                    'INSERT INTO posts (user_id, space_id, content, link_url, media_url, is_hidden, created_at, updated_at)
                     VALUES (:user_id, :space_id, :content, NULL, :media_url, 0, :created_at, :updated_at)'
                );
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO posts (user_id, space_id, content, is_hidden, created_at)
                     VALUES (:user_id, :space_id, :content, 0, :created_at)'
                );
            }

            $now = now_datetime();
            $params = [
                ':user_id' => (int)$user['id'],
                ':space_id' => $spaceId,
                ':content' => $content,
                ':created_at' => $now,
            ];
            if ($hasMediaUrl) {
                $params[':media_url'] = $mediaUrl;
                $params[':updated_at'] = $now;
            }

            $stmt->execute($params);
            redirect('/space.php?id=' . $spaceId . '&ok=post_created');
        } catch (Exception $e) {
            redirect('/space.php?id=' . $spaceId . '&err=post_database');
        }
    }
    
    http_response_code(400);
    exit('Bad Request');
}

require_once __DIR__ . '/includes/header.php';

// Toon space detailpagina
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
    } elseif (isset($_GET['err']) && $_GET['err'] === 'space_schema') {
        $notice = ['type' => 'danger', 'message' => 'Database schema mist posts.space_id. Voer de schema-migratie uit en probeer opnieuw.'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'post_validation') {
        $notice = ['type' => 'danger', 'message' => 'Post is leeg of te lang (max 5000).'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'upload') {
        $notice = ['type' => 'danger', 'message' => 'Upload/URL failed. Upload a JPG/PNG/WebP/GIF up to 5MB, or paste a valid http(s) image URL.'];
    } elseif (isset($_GET['err']) && $_GET['err'] === 'post_database') {
        $notice = ['type' => 'danger', 'message' => 'Database error bij post aanmaken.'];
    }
    ?>

    <?php if ($notice): ?>
        <div class="alert alert-<?= e($notice['type']) ?>" role="alert"><?= e($notice['message']) ?></div>
    <?php endif; ?>

    <a class="btn btn-sm btn-link px-0 mb-2" href="<?= e(url('/space.php')) ?>">&larr; Terug naar spaces</a>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <h1 class="h4 mb-0"><?= e($space['title']) ?></h1>
                <?php if ((int)$space['is_hidden'] === 1): ?>
                    <span class="badge text-bg-danger">Hidden</span>
                <?php endif; ?>
            </div>
            <div class="text-secondary mb-2"><?= e($space['subject']) ?></div>
            <?php if (!empty($space['description'])): ?>
                <div class="mb-2"><?= nl2br(e($space['description'])) ?></div>
            <?php endif; ?>
            <div class="small text-secondary">
                Gemaakt door:
                <a class="text-decoration-none" href="<?= e(url('/profile.php?u=' . urlencode((string)$space['username']))) ?>"><?= e($space['username']) ?></a>
                · <?= e($space['created_at']) ?>
            </div>
        </div>
    </div>

        <?php if ($user): ?>
            <?php require_not_blocked(); ?>
            <form class="card shadow-sm mb-3" method="post" action="<?= e(url('/space.php?id=' . $spaceId)) ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create_post">
                <div class="card-body">
                    <h2 class="h6 mb-3">Post toevoegen aan <?= e($space['title']) ?></h2>
                    <div class="mb-3">
                        <label class="form-label" for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required maxlength="5000" placeholder="Wat wil je delen in deze space?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="image">Image (optional)</label>
                        <input class="form-control" id="image" name="image" type="file" accept="image/*">
                        <div class="form-text">JPG/PNG/WebP/GIF, max 5MB. Stored in assets/uploads.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="media_url">Or image URL (optional)</label>
                        <input class="form-control" id="media_url" name="media_url" type="url" placeholder="https://images.unsplash.com/...">
                    </div>
                    <button class="btn btn-primary" type="submit">Post plaatsen</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-secondary" role="alert">Login om posts toe te voegen.</div>
        <?php endif; ?>

        <h2 class="h5 mt-4">Posts in deze space</h2>
        <?php
        $posts = get_space_posts($spaceId);
        
        if (empty($posts)): ?>
            <div class="alert alert-secondary" role="alert">Geen posts in deze space.</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card shadow-sm mb-2">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center gap-2 small text-secondary mb-2">
                            <a class="fw-semibold text-decoration-none" href="<?= e(url('/profile.php?u=' . urlencode((string)$post['username']))) ?>"><?= e($post['username']) ?></a>
                            <span>·</span>
                            <span><?= e($post['created_at']) ?></span>
                        </div>

                        <div class="mb-2"><?= nl2br(e($post['content'])) ?></div>

                        <?php if (!empty($post['media_url'])): ?>
                            <img class="post-image img-fluid rounded border" src="<?= e(url((string)$post['media_url'])) ?>" alt="" loading="lazy">
                        <?php endif; ?>

                        <a class="btn btn-sm btn-link px-0" href="<?= e(url('/post.php?id=' . (int)$post['id'])) ?>">Details &rarr;</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    

<?php } else {
    // Toon spaces overzicht
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
        <div class="alert alert-<?= e($notice['type']) ?>" role="alert"><?= e($notice['message']) ?></div>
    <?php endif; ?>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h3 mb-0">Spaces</h1>
    </div>
        
        <?php if ($user): ?>
            <?php require_not_blocked(); ?>
            <div class="card shadow-sm mb-4" id="new-space">
                <div class="card-body">
                    <h2 class="h5">Nieuwe space aanmaken</h2>
                    <form method="post" action="<?= e(url('/space.php')) ?>">
                        <input type="hidden" name="action" value="create_space">
                        <div class="mb-3">
                            <label class="form-label" for="title">Titel</label>
                            <input class="form-control" type="text" id="title" name="title" maxlength="100" required placeholder="Titel van de space">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="subject">Onderwerp</label>
                            <input class="form-control" type="text" id="subject" name="subject" maxlength="255" required placeholder="Waar gaat deze space over?">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Beschrijving (optioneel)</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Meer details over deze space..."></textarea>
                        </div>
                        <button class="btn btn-primary" type="submit">Space aanmaken</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary" role="alert">Login om een space aan te maken.</div>
        <?php endif; ?>

        <h2 class="h5">Alle spaces</h2>
        <?php
        $spaces = get_spaces(20, 0, is_admin());
        
        if (empty($spaces)): ?>
            <div class="alert alert-secondary" role="alert">Geen spaces beschikbaar.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" data-infinite-scroll data-load-more-url="<?= e(url('/space.php')) ?>">
                <?php foreach ($spaces as $space): ?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <h3 class="h6 mb-0">
                                        <a class="text-decoration-none" href="<?= e(url('/space.php?id=' . (int)$space['id'])) ?>"><?= e($space['title']) ?></a>
                                    </h3>
                                    <?php if ((int)$space['is_hidden'] === 1): ?>
                                        <span class="badge text-bg-danger">Hidden</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-secondary small mt-1 mb-2"><?= e($space['subject']) ?></div>
                                <?php if (!empty($space['description'])): ?>
                                    <div class="small mb-2"><?= e(substr((string)$space['description'], 0, 150)) ?><?= strlen((string)$space['description']) > 150 ? '...' : '' ?></div>
                                <?php endif; ?>
                                <div class="small text-secondary">Door: <?= e($space['username']) ?> · <?= (int)$space['post_count'] ?> posts · <?= e($space['created_at']) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

<?php }

require_once __DIR__ . '/includes/footer.php';
?>
