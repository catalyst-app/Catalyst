<?php
use \Catalyst\Controller;
use \Catalyst\Page\UniversalFunctions;
?>
		</div>
		<footer class="page-footer <?= UniversalFunctions::getColorClasses() ?>">
			<div class="container white-text">
				<p>
					Website &copy;<?php echo date("Y"); ?> Catalyst, All rights reserved.
				</p>
				<p>
					<?php chdir(realpath(REAL_ROOTDIR)); ?>
					Version: <?= Controller::VERSION ?>, <?= `git log -1 --pretty="(%h) %B by %cN %cr"` ?>
				</p>
				<p>
					Hosted artwork copyright their respective owners unless otherwise stated.
				</p>
				<br>
			</div>
		</footer>
	</body>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112460506-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-112460506-1');
	</script>
</html>
