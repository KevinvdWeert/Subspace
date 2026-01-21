<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Rules</h1>

<div class="container subspace-content">
	<div class="card border-0 shadow-sm">
		<div class="card-body">
			<p class="text-muted">To keep Subspace friendly and useful, follow these rules:</p>

			<h2 class="h5 mt-4">Be respectful</h2>
			<ul class="mb-0">
				<li>No harassment or hate speech</li>
				<li>No bullying or targeted abuse</li>
			</ul>

			<h2 class="h5 mt-4">Keep it legal</h2>
			<ul class="mb-0">
				<li>No illegal content</li>
				<li>No doxxing or sharing private information</li>
			</ul>

			<h2 class="h5 mt-4">No spam</h2>
			<ul class="mb-0">
				<li>No repetitive promotional posts</li>
				<li>No misleading links</li>
			</ul>

			<h2 class="h5 mt-4">Moderation</h2>
			<p class="mb-0">
				Admins may hide posts or block accounts that violate the rules.
				More details are in the <a href="<?= e(url('/components/user_agreement.php')) ?>">User Agreement</a>.
			</p>
		</div>
	</div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>