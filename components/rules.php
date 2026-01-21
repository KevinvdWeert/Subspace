<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Rules</h1>

<div class="card">
	<p>To keep Subspace friendly and useful, follow these rules:</p>

	<h2>Be respectful</h2>
	<ul>
		<li>No harassment or hate speech</li>
		<li>No bullying or targeted abuse</li>
	</ul>

	<h2>Keep it legal</h2>
	<ul>
		<li>No illegal content</li>
		<li>No doxxing or sharing private information</li>
	</ul>

	<h2>No spam</h2>
	<ul>
		<li>No repetitive promotional posts</li>
		<li>No misleading links</li>
	</ul>

	<h2>Moderation</h2>
	<p>
		Admins may hide posts or block accounts that violate the rules.
		More details are in the <a href="<?= e(url('/components/user_agreement.php')) ?>">User Agreement</a>.
	</p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>