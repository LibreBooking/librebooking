{include file='globalheader.tpl' Title='Booked Scheduler v2 Migration'}

<div id="page-migrate">
	<div class="card shadow">
		<div class="card-body">
			<h1 class="border-bottom text-center mb-3">Migrate phpScheduleIt v1.2 to Booked Scheduler v2.x (MySQL only)
			</h1>

			<div class="migratingElements" style="display:none;">Migrating <span class="elementType"></span>
				<div class="spinner-border spinner-border-sm" role="status"></div>
			</div>
			<div class="migratedElements" style="display:none;">
				Migrated <span class="migratedCount">-</span> out of <span class="legacyCount">-</span>
				<span class="elementType"></span> (<span class="percentComplete">-</span>%)
			</div>

			<div id="migrationResults">

			</div>

			<div id="done" style="display:none;">
				Done!
			</div>

			<div id="errorMessage" class="error" style="display:none;">There was an error running the migration. See
				details
				below.</div>
			<div id="errorContents" style="width:100%; display:none;"></div>

			{include file="javascript-includes.tpl"}
			<div>
				{if $StartMigration}
					<script type="text/javascript">
						function Migration() {
							var migratingElements = $('.migratingElements');
							var migratedElements = $('.migratedElements');
							var migrationResults = $('#migrationResults');

							var startMigration = function(migrateParams) {
								var elementType = migrateParams.elementType;
								var migrating = $('#migrating-' + elementType);
								var migrated = $('#migrated-' + elementType);
								if (migrationResults.find(migrating).length <= 0) {
									migrating = migratingElements.clone();
									migrating.attr('id', 'migrating-' + elementType);
									migrating.appendTo(migrationResults);
								}
								if (migrationResults.find(migrated).length <= 0) {
									migrated = migratedElements.clone();
									migrated.attr('id', 'migrated-' + elementType);
									migrated.appendTo(migrationResults);
								}

								migrating.find('.elementType').text(elementType);
								migrated.find('.elementType').text(elementType);
								migrating.show();

								$.ajax({
									url: "migrate.php?start=" + elementType,
									type: "GET",
									success: function(data) {
										migrated.find('.migratedCount').text(data.MigratedCount);
										migrated.find('.legacyCount').text(data.LegacyCount);
										migrated.find('.percentComplete').text(data.PercentComplete);
										migrated.show();
										//console.log('Migrating data ' + elementType);
										if (data.RemainingCount > 0) {
											migrateParams.current();
										} else {
											migrating.hide();
											if (migrateParams.next != null) {
												migrateParams.next();
											} else {
												$('#done').show();
											}
										}
									},
									error: function(data) {
										migrating.hide();
										migrated.hide();
										$('#errorMessage').show();
										$('#errorContents').text(JSON.stringify(data)).show();
									},
									dataType: "json"
								});
							};

							var startSchedules = function() {
								startMigration({
									elementType: 'schedules',
									current: startSchedules,
									next: startResources
								});
							};

							var startResources = function() {
								startMigration({
									elementType: 'resources',
									current: startResources,
									next: startAccessories
								});
							};

							var startAccessories = function() {
								startMigration({
									elementType: 'accessories',
									current: startAccessories,
									next: startGroups
								});
							};

							var startGroups = function() {
								startMigration({
									elementType: 'groups',
									current: startGroups,
									next: startUsers
								});
							};

							var startUsers = function() {
								startMigration({
									elementType: 'users',
									current: startUsers,
									next: startReservations
								});
							};

							var startReservations = function() {
								startMigration({
									elementType: 'reservations',
									current: startReservations,
									next: null
								});
							};

							this.run = function() {
								startSchedules();
							};
						}

						var migration = new Migration();
						migration.run();
					</script>
				{else}
					<h3>This will copy all data from your phpScheduleIt v1.2 installation into 2.x. Due to changes in 2.x,
						this
						process will
						not be perfect. This process only migrates data which exists in your 1.2 installation but not in
						your 2.x
						installation.
						Running this multiple times will not insert duplicate data. This will not affect your 1.2
						installation.</h3>
					<br />
					<h3>There is no automated way to undo this process. Please check all migrated data for accuracy after
						the import
						completes.</h3>
					<div class="error">
						<h3>Known Issues</h3>
						<ul style="margin-left: 30px;">
							<li>Recurring reservations will appear as single instances</li>
							<li>Application admin designations will not be migrated, only the user accounts</li>
							<li>Group admin designations will not be migrated, only the user accounts</li>
							<li>Open reservation invitations will be removed</li>
	<li>User timezones will all be set to the server's timezone</li>
						<li>At the time of writing, Booked Scheduler 2 is not available in all of the same languages
							that 1.2
							was.
							User language preferences will be migrated but may have no immediate effect
						</li>
						<li>User email preferences will not be migrated</li>
					</ul>
				</div>
				<form class="register" method="post" action="{$smarty.server.SCRIPT_NAME}">
					{if $LegacyConnectionFailed}
					<div class="alert alert-danger">
						Could not connect to 1.2 database. Please confirm the settings below and try again.
					</div>
					{/if}

					{if $InstallPasswordFailed}
					<div class="alert alert-info">
						Your installation password was incorrect. Please confirm this config setting in
						$conf['settings']['install.password']
					</div>
					{/if}

					<h3>phpScheduleIt 1.2 database settings</h3>
					<br />

					<ul class="list-unstyled">
						<li class="mb-3">
							<label class="form-label">Install Password:</label>
							<input type="password" class="form-control" name="installPassword"
								placeholder="Found in 2.x config.php" />
						</li>
						<li class="mb-3">
							<label class="form-label">User:</label>
							<input type="text" class="form-control" name="legacyUser" />
						</li>
						<li class="mb-3">
							<label class="form-label">Password:</label>
							<input type="password" class="form-control" name="legacyPassword" />
						</li>
						<li class="mb-3">
							<label class="form-label">Hostspec:</label>
							<input type="text" class="form-control" name="legacyHostSpec" />
						</li>
						<li class="mb-3">
							<label class="form-label">Database Name:</label>
							<input type="text" class="form-control" name="legacyDatabaseName" />
						</li>
					</ul>
					<br />
					<input type="submit" name="run" value="Run Migration" class="btn btn-outline-secondary mt-2" />
				</form>
				{/if}
			</div>
		</div>
	</div>
</div>
{include file='globalfooter.tpl'}