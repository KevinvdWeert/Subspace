<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Terms</h1>

<p class="text-muted">Last updated: <?= e(date('Y-m-d')) ?></p>

<div class="container subspace-content">
	<div class="card border-0 shadow-sm">
		<div class="card-body">
			<p>
				By using Subspace, you agree to these Terms. If you don’t agree, please don’t use the site.
			</p>

			<h2 class="h5 mt-4">Availability</h2>
			<p class="mb-0">We may change or discontinue features at any time.</p>

			<h2 class="h5 mt-4">Content</h2>
			<p class="mb-0">
				You are responsible for what you post. You must follow our <a href="<?= e(url('/components/rules.php')) ?>">Rules</a>.
			</p>

			<h2 class="h5 mt-4">Accounts</h2>
			<p class="mb-0">You are responsible for securing your account and password.</p>

			<h2 class="h5 mt-4">Contact</h2>
			<p class="mb-0">Questions? Use <a href="<?= e(url('/components/contact.php')) ?>">Contact</a>.</p>
		</div>
	</div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>