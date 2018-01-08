		<header>
			<div class="navbar-fixed">
				<nav class="<?= \Redacted\Page\UniversalFunctions::getColorClasses() ?>">
					<div class="nav-wrapper">
						<a class="brand-logo" href="<?= ROOTDIR ?>">
							<?= \Redacted\Page\Header\Header::LOGO_HTML ?> 
						</a>
						<a class="button-collapse" data-activates="mobile-menu" href="#">
							<i class="material-icons">
								menu
							</i>
						</a>
						<ul class="right hide-on-med-and-down">
<?php foreach (\Redacted\Page\Navigation\Navbar::getNavbarItems(\Redacted\User\User::getPermissionScope()) as $item): ?>
<?php switch ($item[4]): ?>
<?php case \Redacted\Page\Navigation\Navbar::NORMAL_LINK: ?>
							<li<?= ($item[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
								<a href="<?= $item[3] ?>">
									<?= $item[1] == \Redacted\Page\Navigation\Navbar::CALLABLE ? call_user_func($item[0], \Redacted\Page\Navigation\Navbar::NAVBAR) : $item[0] ?> 
								</a>
							</li>
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::DROPDOWN_PARENT: ?>
							<li<?= ($item[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
								<a class="dropdown-button valign-wrapper" href="#!" data-activates="<?= $item[5] ?>">
									<?= $item[1] == \Redacted\Page\Navigation\Navbar::CALLABLE ? call_user_func($item[0], \Redacted\Page\Navigation\Navbar::NAVBAR) : $item[0] ?> 
									<i class="material-icons right">arrow_drop_down</i>
								</a>
							</li>
							<ul id="<?= $item[5] ?>" class="dropdown-content">
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::DROPDOWN_CHILD: ?>
								<li>
									<a href="<?= $item[3] ?>">
										<?= $item[1] == \Redacted\Page\Navigation\Navbar::CALLABLE ? call_user_func($item[0], \Redacted\Page\Navigation\Navbar::NAVBAR) : $item[0] ?> 
									</a>
								</li>
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::DROPDOWN_DIVIDER: ?>
								<li class="divider"></li>
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::PSUEDO_DROPDOWN_END: ?>
							</ul>
<?php break; ?>
<?php endswitch; ?>
<?php endforeach; ?>
						</ul>
					</div>
				</nav>
			</div>
			<ul class="side-nav" id="mobile-menu">
<?php if (\Redacted\User\User::isLoggedIn()): ?>
<?= $_SESSION["user"]->getSidenavHTML() ?>
<?php endif; ?>
<?php foreach (\Redacted\Page\Navigation\Navbar::getNavbarItems(\Redacted\User\User::getPermissionScope()) as $item): ?>
<?php switch ($item[4]): ?>
<?php case \Redacted\Page\Navigation\Navbar::NORMAL_LINK: ?>
				<li<?= ($item[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
					<a href="<?= $item[3] ?>">
						<?= $item[1] == \Redacted\Page\Navigation\Navbar::CALLABLE ? call_user_func($item[0], \Redacted\Page\Navigation\Navbar::SIDENAV) : $item[0] ?> 
					</a>
				</li>
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::DROPDOWN_PARENT: ?>
				<li class="divider"></li>
				<li class="no-padding<?= ($item[2] == PAGE_KEYWORD) ? ' active' : "" ?>">
					<ul class="collapsible collapsible-accordion">
						<li>
							<a class="collapsible-header valign-wrapper" href="#!">
								<?= $item[1] == \Redacted\Page\Navigation\Navbar::CALLABLE ? call_user_func($item[0], \Redacted\Page\Navigation\Navbar::SIDENAV) : $item[0] ?> 
								<i class="material-icons right">arrow_drop_down</i>
							</a>
							<div class="collapsible-body">
								<ul>
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::DROPDOWN_CHILD: ?>
									<li>
										<a href="<?= $item[3] ?>">
											<?= $item[1] == \Redacted\Page\Navigation\Navbar::CALLABLE ? call_user_func($item[0], \Redacted\Page\Navigation\Navbar::SIDENAV) : $item[0] ?> 
										</a>
									</li>
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::DROPDOWN_DIVIDER: ?>
									<li class="divider"></li>
<?php break; ?>
<?php case \Redacted\Page\Navigation\Navbar::PSUEDO_DROPDOWN_END: ?>
								</ul>
							</div>
						</li>
					</ul>
				</li>
				<li class="divider"></li>
<?php break; ?>
<?php endswitch; ?>
<?php endforeach; ?>
			</ul>
		</header>
