<header>
    <div class="navbar-fixed">
        <nav class="<?= \Catalyst\Page\UniversalFunctions::getColorClasses() ?>">
            <div class="nav-wrapper">
                <a class="brand-logo" href="<?= ROOTDIR ?>">
                    <?= \Catalyst\Page\Header\Header::LOGO_HTML ?>
                </a>
                <a class="button-collapse" data-activates="mobile-menu" href="#">
                    <i class="material-icons">
                        menu
                    </i>
                </a>
                <ul class="right hide-on-med-and-down">
                    <?php foreach (\Catalyst\Page\Navigation\Navbar::getNavbarItems(\Catalyst\User\User::getPermissionScope()) as $navbarItem): ?>
                        <?php switch ($navbarItem[4]): ?>
<?php case \Catalyst\Page\Navigation\Navbar::NORMAL_LINK: ?>
                                <li<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
                                    <a href="<?= $navbarItem[3] ?>">
                                        <?= $navbarItem[1] == \Catalyst\Page\Navigation\Navbar::CALLABLE ? call_user_func($navbarItem[0], \Catalyst\Page\Navigation\Navbar::NAVBAR) : $navbarItem[0] ?>
                                    </a>
                                </li>
                                <?php break; ?>
                            <?php case \Catalyst\Page\Navigation\Navbar::DROPDOWN_PARENT: ?>
                                <li<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
                                    <a class="dropdown-button valign-wrapper" href="#!"
                                       data-activates="<?= $navbarItem[5] ?>">
                                        <?= $navbarItem[1] == \Catalyst\Page\Navigation\Navbar::CALLABLE ? call_user_func($navbarItem[0], \Catalyst\Page\Navigation\Navbar::NAVBAR) : $navbarItem[0] ?>
                                        <i class="material-icons right">arrow_drop_down</i>
                                    </a>
                                </li>
                                <ul id="<?= $navbarItem[5] ?>" class="dropdown-content">
                                <?php break; ?>
                            <?php case \Catalyst\Page\Navigation\Navbar::DROPDOWN_CHILD: ?>
                                <li>
                                    <a href="<?= $navbarItem[3] ?>">
                                        <?= $navbarItem[1] == \Catalyst\Page\Navigation\Navbar::CALLABLE ? call_user_func($navbarItem[0], \Catalyst\Page\Navigation\Navbar::NAVBAR) : $navbarItem[0] ?>
                                    </a>
                                </li>
                                <?php break; ?>
                            <?php case \Catalyst\Page\Navigation\Navbar::DROPDOWN_DIVIDER: ?>
                                <li class="divider"></li>
                                <?php break; ?>
                            <?php case \Catalyst\Page\Navigation\Navbar::PSUEDO_DROPDOWN_END: ?>
                                </ul>
                                <?php break; ?>
                            <?php endswitch; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
    </div>
    <ul class="side-nav" id="mobile-menu">
        <?php if (\Catalyst\User\User::isLoggedIn()): ?>
            <?= $_SESSION["user"]->getSidenavHTML() ?>
        <?php endif; ?>
        <?php foreach (\Catalyst\Page\Navigation\Navbar::getNavbarItems(\Catalyst\User\User::getPermissionScope()) as $navbarItem): ?>
            <?php switch ($navbarItem[4]): ?>
<?php case \Catalyst\Page\Navigation\Navbar::NORMAL_LINK: ?>
                    <li<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' class="active"' : "" ?>>
                        <a href="<?= $navbarItem[3] ?>">
                            <?= $navbarItem[1] == \Catalyst\Page\Navigation\Navbar::CALLABLE ? call_user_func($navbarItem[0], \Catalyst\Page\Navigation\Navbar::SIDENAV) : $navbarItem[0] ?>
                        </a>
                    </li>
                    <?php break; ?>
                <?php case \Catalyst\Page\Navigation\Navbar::DROPDOWN_PARENT: ?>
                <!--    <li class="divider"></li> -->
                    <li class="no-padding<?= ($navbarItem[2] == PAGE_KEYWORD) ? ' active' : "" ?>">
                    <ul class="collapsible collapsible-accordion">
                    <li>
                    <a class="collapsible-header valign-wrapper" href="#!">
                        <?= $navbarItem[1] == \Catalyst\Page\Navigation\Navbar::CALLABLE ? call_user_func($navbarItem[0], \Catalyst\Page\Navigation\Navbar::SIDENAV) : $navbarItem[0] ?>
                        <i class="material-icons right">arrow_drop_down</i>
                    </a>
                    <div class="collapsible-body">
                    <ul>
                    <?php break; ?>
                <?php case \Catalyst\Page\Navigation\Navbar::DROPDOWN_CHILD: ?>
                    <li>
                        <a href="<?= $navbarItem[3] ?>">
                            <?= $navbarItem[1] == \Catalyst\Page\Navigation\Navbar::CALLABLE ? call_user_func($navbarItem[0], \Catalyst\Page\Navigation\Navbar::SIDENAV) : $navbarItem[0] ?>
                        </a>
                    </li>
                    <?php break; ?>
                <?php case \Catalyst\Page\Navigation\Navbar::DROPDOWN_DIVIDER: ?>
                  <!--  <li class="divider"></li> -->
                    <?php break; ?>
                <?php case \Catalyst\Page\Navigation\Navbar::PSUEDO_DROPDOWN_END: ?>
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
</header>
