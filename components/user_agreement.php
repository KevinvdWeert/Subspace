<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>User Agreement</h1>

<p class="text-muted">Last updated: <?= e(date('Y-m-d')) ?></p>

<div class="card">
	<p>
		This agreement describes how you may use Subspace and what we expect from users.
	</p>

	<h2>Your responsibilities</h2>
	<ul>
		<li>Follow the <a href="<?= e(url('/components/rules.php')) ?>">Rules</a></li>
		<li>Don’t attempt to break, abuse, or reverse engineer the site</li>
		<li>Don’t impersonate others</li>
	</ul>

	<h2>Enforcement</h2>
	<p>
		We may remove content, hide posts, or block accounts to enforce the rules.
		Admin actions are intended to keep the platform safe.
	</p>

	<h2>Privacy</h2>
	<p>Read our <a href="<?= e(url('/components/privacy.php')) ?>">Privacy Policy</a> for details about data processing.</p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>