<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Privacy Policy</h1>

<p class="text-muted">Last updated: <?= e(date('Y-m-d')) ?></p>

<div class="container subspace-content">
	<div class="card border-0 shadow-sm">
		<div class="card-body">
			<p>
				This Privacy Policy explains what data Subspace processes and why. Because this is a demo project,
				keep in mind that the implementation may be minimal.
			</p>

			<h2 class="h5 mt-4">Data we store</h2>
			<ul class="mb-0">
				<li>Account details (username, e-mail)</li>
				<li>Password hash (we never store your plain password)</li>
				<li>Posts, comments, likes</li>
				<li>Profile data you choose to add (display name, bio, avatar URL)</li>
			</ul>

			<h2 class="h5 mt-4">Why we process data</h2>
			<ul class="mb-0">
				<li>To create and manage accounts</li>
				<li>To show your content and interactions</li>
				<li>To keep the platform safe (moderation, blocking)</li>
			</ul>

			<h2 class="h5 mt-4">Sharing</h2>
			<p class="mb-0">We don’t sell personal data. Data is only shared when required for hosting/operations or by law.</p>

			<h2 class="h5 mt-4">Questions</h2>
			<p class="mb-0">Contact us via <a href="<?= e(url('/components/contact.php')) ?>">Contact</a>.</p>
		</div>
	</div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>