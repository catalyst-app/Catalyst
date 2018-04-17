<?php
use \Catalyst\Controller;
use \Catalyst\Database\Database;
use \Catalyst\Database\Query\AbstractQuery;
use \Catalyst\Page\UniversalFunctions;
?>
		</div>
		<footer class="page-footer">
			<div class="container white-text">
				<p>
					Website &copy;2017-<?php echo date("Y"); ?> Catalyst, All rights reserved.
				</p>
				<p>
					Version: <?= Controller::getVersion() ?>
					<?php if (Controller::isDevelMode()): ?>
						<?php chdir(realpath(REAL_ROOTDIR)); // reset dir for proper git usage ?>
						<?= `git log -1 --pretty="(%h) %B by %cN %cr"` ?>
					<?php endif; ?>
				</p>
				<?php if (Controller::isDevelMode()): ?>
<?php
$stmt = Database::getDbh()->query("show profiles");
$rows = $stmt->fetchAll();
?>
					<p>
						<strong>Debug information:</strong> Page generated in <?= microtime(true)-EXEC_START_TIME ?>s, requiring <?= AbstractQuery::getTotalQueries() ?> database queries (which took <?= array_sum(array_column($rows, "Duration")) ?>s).
					</p>
				<?php endif; ?>
				<p>
					Hosted artwork copyright their respective owners unless otherwise stated.
				</p>
				<br>
			</div>
		</footer>
	</body>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script src="https://googletagmanager.com/gtag/js?id=UA-112460506-1" async defer></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-112460506-1');
	</script>
</html>
