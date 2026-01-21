<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Terms</h1>

<p class="text-muted">Last updated: <?= e(date('Y-m-d')) ?></p>

<div class="card">
	<p>
		By using Subspace, you agree to these Terms. If you don’t agree, please don’t use the site.
	</p>

	<h2>Availability</h2>
	<p>We may change or discontinue features at any time.</p>

	<h2>Content</h2>
	<p>
		You are responsible for what you post. You must follow our <a href="<?= e(url('/components/rules.php')) ?>">Rules</a>.
	</p>

	<h2>Accounts</h2>
	<p>You are responsible for securing your account and password.</p>

	<h2>Contact</h2>
	<p>Questions? Use <a href="<?= e(url('/components/contact.php')) ?>">Contact</a>.</p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>