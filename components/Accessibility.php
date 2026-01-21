<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Accessibility</h1>

<p class="text-muted">Last updated: <?= e(date('Y-m-d')) ?></p>

<div class="card">
	<p>
		We want Subspace to be usable for everyone. If you run into an accessibility issue, please let us know via the
		<a href="<?= e(url('/components/contact.php')) ?>">contact page</a>.
	</p>

	<h2>What we aim for</h2>
	<ul>
		<li>Keyboard-friendly navigation</li>
		<li>Readable contrast and typography</li>
		<li>Clear form labels and error messages</li>
		<li>Semantic HTML structure where possible</li>
	</ul>

	<h2>Need help?</h2>
	<p>
		If you need content in a different format or you have trouble accessing a feature, send us a message and include:
		what page you were on, what you tried to do, and what device/browser you use.
	</p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>