<label class="kt-sidebar-label">Navigation</label>
		<ul class="nav kt-sideleft-menu">

            <li class="nav-item">
				<a href="/dashboard" class="nav-link">
					<i class="fa fa-dashboard"></i>
					<span>Dashboard</span>
				</a>
			</li>

			<li class="nav-item">
				<a href="/Hierarchy" class="nav-link 
					<?php echo (strtolower(($this->uri->segment(1)) == "hierarchy" or strtolower($this->uri->segment(1)) == "accessorder" or strtolower($this->uri->segment(1)) == "userorder")  ? "active":"")?>">
					<i class="fa fa-globe"></i>
					<span>Manage company</span>
				</a>
			</li>

			<li class="nav-item">
				<a href="/orders" class="nav-link <?php echo (strtolower($this->uri->segment(1)) == "orders" ? "active":"")?>">
					<i class="fa fa-history"></i>
					<span>Order history</span>
				</a>
			</li>

			<li class="nav-item">
				<a href="/case" class="nav-link <?php echo (strtolower($this->uri->segment(1)) == "case" ? "active":"")?>">
					<i class="fa fa-life-ring"></i>
					<span>Support</span>
				</a>
			</li>

			<!-- nav-item -->
			<li class="nav-item">
				<a href="#" class="nav-link with-sub <?php echo ($this->uri->segment(1) == 'settings'? 'active':'' )?>">
					<i class="fa fa-cog"></i>
					<span>
						<?php echo $this->lang->line('system_settings') ?>
					</span>
				</a>
				<ul class="nav-sub" style="display:<?php echo ($this->uri->segment(1) == 'settings'? 'block':'none' )?>">


					<li class="nav-item">
						<a href="/settings/translation" class="nav-link <?php echo ($this->uri->segment(2) == 'translation'? 'active':'' )?>">
							<?php echo $this->lang->line('translations') ?>
						</a>
					</li>

					<li class="nav-item">
						<a href="/settings/language" class="nav-link <?php echo ($this->uri->segment(2) == 'language'? 'active':'' )?>">
							<?php echo $this->lang->line('languages') ?>
						</a>
					</li>

					<li class="nav-item">
						<a href="/settings/user" class="nav-link <?php echo ($this->uri->segment(2) == 'user'? 'active':'' )?>">
							<?php echo $this->lang->line('admin_users') ?>
						</a>
					</li>

				</ul>
			</li>
			<!-- nav-item -->
			<!--<li class="nav-item">
				<a href="./blank.html" class="nav-link with-sub">
					<i class="icon ion-ios-filing-outline"></i>
					<span>UI Elements</span>
				</a>
				<ul class="nav-sub">
					<li class="nav-item">
						<a href="./accordion.html" class="nav-link">Accordion</a>
					</li>
					<li class="nav-item">
						<a href="./alerts.html" class="nav-link">Alerts</a>
					</li>
					<li class="nav-item">
						<a href="./buttons.html" class="nav-link">Buttons</a>
					</li>
					<li class="nav-item">
						<a href="./cards.html" class="nav-link">Cards</a>
					</li>
					<li class="nav-item">
						<a href="./icons.html" class="nav-link">Icons</a>
					</li>
					<li class="nav-item">
						<a href="./modal.html" class="nav-link">Modal</a>
					</li>
					<li class="nav-item">
						<a href="./navigation.html" class="nav-link">Navigation</a>
					</li>
					<li class="nav-item">
						<a href="./pagination.html" class="nav-link">Pagination</a>
					</li>
					<li class="nav-item">
						<a href="./popups.html" class="nav-link">Tooltip &amp; Popover</a>
					</li>
					<li class="nav-item">
						<a href="./progress.html" class="nav-link">Progress</a>
					</li>
					<li class="nav-item">
						<a href="./spinners.html" class="nav-link">Spinners</a>
					</li>
					<li class="nav-item">
						<a href="./typography.html" class="nav-link">Typography</a>
					</li>
				</ul>
			</li>-->
			<!-- nav-item -->
			<!--<li class="nav-item">
				<a href="./blank.html" class="nav-link with-sub">
					<i class="icon ion-ios-analytics-outline"></i>
					<span>Charts</span>
				</a>
				<ul class="nav-sub">
					<li class="nav-item">
						<a href="./chart-morris.html" class="nav-link">Morris Charts</a>
					</li>
					<li class="nav-item">
						<a href="./chart-flot.html" class="nav-link">Flot Charts</a>
					</li>
					<li class="nav-item">
						<a href="./chart-chartjs.html" class="nav-link">Chart JS</a>
					</li>
					<li class="nav-item">
						<a href="./chart-rickshaw.html" class="nav-link">Rickshaw</a>
					</li>
					<li class="nav-item">
						<a href="./chart-sparkline.html" class="nav-link">Sparkline</a>
					</li>
				</ul>
			</li>-->
			<!-- nav-item -->
			<!---<li class="nav-item">
				<a href="./blank.html" class="nav-link with-sub">
					<i class="icon ion-ios-navigate-outline"></i>
					<span>Maps</span>
				</a>
				<ul class="nav-sub">
					<li class="nav-item">
						<a href="./map-google.html" class="nav-link">Google Maps</a>
					</li>
					<li class="nav-item">
						<a href="./map-vector.html" class="nav-link">Vector Maps</a>
					</li>
				</ul>
			</li>-->
			<!-- nav-item -->
		<!--	<li class="nav-item">
				<a href="./blank.html" class="nav-link with-sub">
					<i class="icon ion-ios-list-outline"></i>
					<span>Tables</span>
				</a>
				<ul class="nav-sub">
					<li class="nav-item">
						<a href="./table-basic.html" class="nav-link">Basic Table</a>
					</li>
					<li class="nav-item">
						<a href="./table-datatable.html" class="nav-link">Data Table</a>
					</li>
				</ul>
			</li>-->
			<!-- nav-item -->
			<!--<li class="nav-item">
				<a href="./blank.html" class="nav-link with-sub">
					<i class="icon ion-ios-bookmarks-outline"></i>
					<span>Pages</span>
				</a>
				<ul class="nav-sub">
					<li class="nav-item">
						<a href="./blank.html" class="nav-link">Blank Page</a>
					</li>
					<li class="nav-item">
						<a href="./mailbox.html" class="nav-link">Mailbox</a>
					</li>
					<li class="nav-item">
						<a href="./chat.html" class="nav-link">Chat Page</a>
					</li>
					<li class="nav-item">
						<a href="./calendar.html" class="nav-link">Calendar</a>
					</li>
					<li class="nav-item">
						<a href="./edit-profile.html" class="nav-link">Edit Profile</a>
					</li>
					<li class="nav-item">
						<a href="./file-manager.html" class="nav-link">File Manager</a>
					</li>
					<li class="nav-item">
						<a href="./page-signin.html" class="nav-link">Signin Page</a>
					</li>
					<li class="nav-item">
						<a href="./page-signup.html" class="nav-link">Signup Page</a>
					</li>
					<li class="nav-item">
						<a href="./page-notfound.html" class="nav-link">404 Page Not Found</a>
					</li>
				</ul>
			</li>-->
			<!-- nav-item -->
			<!--<li class="nav-item">
				<a href="./widgets.html" class="nav-link">
					<i class="icon ion-ios-briefcase-outline"></i>
					<span>Widgets</span>
				</a>
			</li>-->
			<!-- nav-item -->
		</ul>