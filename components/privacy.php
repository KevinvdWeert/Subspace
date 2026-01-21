<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Privacy Policy</h1>

<p class="text-muted">Last updated: <?= e(date('Y-m-d')) ?></p>

<div class="card">
	<p>
		This Privacy Policy explains what data Subspace processes and why. Because this is a demo project,
		keep in mind that the implementation may be minimal.
	</p>

	<h2>Data we store</h2>
	<ul>
		<li>Account details (username, e-mail)</li>
		<li>Password hash (we never store your plain password)</li>
		<li>Posts, comments, likes</li>
		<li>Profile data you choose to add (display name, bio, avatar URL)</li>
	</ul>

	<h2>Why we process data</h2>
	<ul>
		<li>To create and manage accounts</li>
		<li>To show your content and interactions</li>
		<li>To keep the platform safe (moderation, blocking)</li>
	</ul>

	<h2>Sharing</h2>
	<p>We don’t sell personal data. Data is only shared when required for hosting/operations or by law.</p>

	<h2>Questions</h2>
	<p>Contact us via <a href="<?= e(url('/components/contact.php')) ?>">Contact</a>.</p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>