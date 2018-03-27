<?php
use \Catalyst\Page\Navigation\Navbar;
use \Catalyst\User\User;
$navbarItems = Navbar::getNavbarItems(User::getPermissionScope());
?>
<header>
	<?php foreach ($navbarItems as $navbarItem): ?>
		<?php switch ($navbarItem[4]): ?><?php case Navbar::DROPDOWN_PARENT: ?>
				<ul id="<?= $navbarItem[5] ?>" class="dropdown-content">
				<?php break; ?>
			<?php case Navbar::DROPDOWN_CHILD: ?>
					<li>
						<a href="<?= $navbarItem[3] ?>">
							<?= Navbar::getNavbarItemLabel(Navbar::NAVBAR, $navbarItem) ?>
						</a>
					</li>
				<?php break; ?>
			<?php case Navbar::DROPDOWN_DIVIDER: ?>
					<li class="divider"></li>
				<?php break; ?>
			<?php case Navbar::PSUEDO_DROPDOWN_END: ?>
				</ul>
				<?php break; ?>
			<?php default: ?>
				<?php break; ?>
		<?php endswitch; ?>
	<?php endforeach; ?>
	<div class="navbar-fixed">
		<nav>
			<div class="nav-wrapper">
				<a class="brand-logo" href="<?= ROOTDIR ?>">
					<?= Navbar::LOGO_HTML ?>
				</a>
				<ul class="right hide-on-med-and-down">
					<?php foreach ($navbarItems as $navbarItem): ?>
						<?php switch ($navbarItem[4]): ?><?php case Navbar::NORMAL_LINK: ?>
								<li<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
									<a href="<?= $navbarItem[3] ?>">
										<?= Navbar::getNavbarItemLabel(Navbar::NAVBAR, $navbarItem) ?>
									</a>
								</li>
								<?php break; ?>
							<?php case Navbar::DROPDOWN_PARENT: ?>
								<li<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
									<a class="dropdown-trigger valign-wrapper" href="#!" data-target="<?= $navbarItem[5] ?>">
										<?= Navbar::getNavbarItemLabel(Navbar::NAVBAR, $navbarItem) ?>
										<i class="material-icons right">arrow_drop_down</i>
									</a>
								</li>
								<?php break; ?>
							<?php default: ?>
								<?php break; ?>
						<?php endswitch; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</nav>
	</div>
	<ul class="side-nav sidenav" id="mobile-menu">
		<?php if (User::isLoggedIn()): ?>
			<?= $_SESSION["user"]->getSidenavHTML() ?>
		<?php endif; ?>
		<?php foreach ($navbarItems as $navbarItem): ?>
			<?php switch ($navbarItem[4]): ?><?php case Navbar::NORMAL_LINK: ?>
					<li<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
						<a href="<?= $navbarItem[3] ?>">
							<?= Navbar::getNavbarItemLabel(Navbar::SIDENAV, $navbarItem) ?>
						</a>
					</li>
					<?php break; ?>
				<?php case Navbar::DROPDOWN_PARENT: ?>
				<!--    <li class="divider"></li> -->
					<li class="no-padding<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' active' : "" ?>">
						<ul class="collapsible collapsible-accordion">
							<li>
								<a class="collapsible-header valign-wrapper" href="#!">
									<?= Navbar::getNavbarItemLabel(Navbar::SIDENAV, $navbarItem) ?>
									<i class="material-icons right">arrow_drop_down</i>
								</a>
								<div class="collapsible-body">
									<ul>
					<?php break; ?>
				<?php case Navbar::DROPDOWN_CHILD: ?>
										<li>
											<a href="<?= $navbarItem[3] ?>">
												<?= Navbar::getNavbarItemLabel(Navbar::SIDENAV, $navbarItem) ?>
											</a>
										</li>
					<?php break; ?>
				<?php case Navbar::DROPDOWN_DIVIDER: ?>
				  <!--  <li class="divider"></li> -->
					<?php break; ?>
				<?php case Navbar::PSUEDO_DROPDOWN_END: ?>
									</ul>
								</div>
							</li>
						</ul>
					</li>
				<!--    <li class="divider"></li> -->
					<?php break; ?>
			<?php endswitch; ?>
		<?php endforeach; ?>
	</ul>
	<a class="button-collapse sidenav-trigger hide-on-large-only red-text" data-activates="mobile-menu" data-target="mobile-menu" href="#">
		<i class="material-icons">
			menu
		</i>
	</a>
</header>
