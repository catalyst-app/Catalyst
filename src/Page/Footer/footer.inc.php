<?php
use \Catalyst\Controller;
use \Catalyst\Database\Database;
use \Catalyst\Database\Query\AbstractQuery;
use \Catalyst\Page\{Resource, UniversalFunctions};
?>
		</div>
		<footer class="page-footer">
			<div class="container white-text">
				<p>
					Website &copy;2017-<?php echo date("Y"); ?> Catalyst, All rights reserved.
				</p>
				<p>
					View our <a href="<?= ROOTDIR ?>Help/ToS">Terms of Service</a> or <a href="<?= ROOTDIR ?>Help/Privacy">Privacy Policy</a>
				</p>
				<p>
					Version: <?= Controller::getVersion() ?> (<?= Controller::getCommit() ?>)
					<?php if (Controller::isDevelMode()): ?>
						<?php chdir(realpath(REAL_ROOTDIR)); // reset dir for proper git usage ?>
						<?= htmlspecialchars(`git log -1 --pretty="%B by %cN %cr"`) ?>
					<?php endif; ?>
				</p>
				<?php if (Controller::isDevelMode()): ?>
					<p>
						<strong>Debug information:</strong> Page generated in <?= microtime(true)-EXEC_START_TIME ?>s, requiring <?= AbstractQuery::getTotalQueries() ?> database queries (which took <?= Database::getTotalQueryTime() ?>s) and <?= UniversalFunctions::humanize(memory_get_peak_usage()) ?> of memory.
					</p>
				<?php endif; ?>
				<p>
					Hosted artwork copyright their respective owners unless otherwise stated.
				</p>
				<br>
			</div>
		</footer>
		<?php foreach (Resource::getScripts() as $script): ?>
			<?= $script->getTag() ?>
		<?php endforeach; ?>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-112460506-1');
		</script>
	</body>
</html>
