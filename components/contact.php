<?php
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Contact</h1>

<div class="container subspace-content">
	<div class="card border-0 shadow-sm">
		<div class="card-body">
			<p>
				Questions, feedback, or a report? Use the info below. (This is a school/demo project, so responses may be limited.)
			</p>

			<h2 class="h5 mt-4">What to include</h2>
			<ul class="mb-0">
				<li>The page URL and what you clicked</li>
				<li>A screenshot (optional)</li>
				<li>Your browser/device (e.g. Chrome on Windows)</li>
			</ul>

			<h2 class="h5 mt-4">Topics</h2>
			<p class="mb-0">
				For rules and moderation questions, see <a href="<?= e(url('/components/rules.php')) ?>">Rules</a>.
				For privacy questions, see <a href="<?= e(url('/components/privacy.php')) ?>">Privacy</a>.
			</p>

			<p class="text-muted mt-3 mb-0">
				Contact method: add your preferred contact channel here (e-mail, form endpoint, etc.).
			</p>
		</div>
	</div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>