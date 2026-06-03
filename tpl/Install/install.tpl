{include file='globalheader.tpl'}

<div id="page-install">
	<div class="card shadow">
		<div class="card-body">
			<h1 class="border-bottom text-center mb-3">{translate key=InstallApplication}</h1>

			{if $ShowScriptUrlWarning}
				<div class="alert alert-danger">
					{translate key=ScriptUrlWarning args="$CurrentScriptUrl,$SuggestedScriptUrl"}
				</div>
			{/if}

			<div>
				<form class="register" method="post" action="{$smarty.server.SCRIPT_NAME}" role="form">

					{if $ShowInvalidPassword}
						<div class="error alert alert-danger">{translate key=IncorrectInstallPassword}</div>
					{/if}

					{if $InstallPasswordMissing}
						<div class='error alert alert-info'>
							<div>{translate key=SetInstallPassword}</div>

							<div>
								{translate key=InstallPasswordInstructions args="$ConfigPath,$ConfigSetting,$SuggestedInstallPassword"}
							</div>
						</div>
					{/if}

					{if $ShowUpToDateMessage}
						<div class="error mb-3">
							<h3>{translate key=NoUpgradeNeeded}</h3>
						</div>
					{/if}

					{if $ShowPasswordPrompt}
						<div class="form-group">
							<div>{translate key=ProvideInstallPassword}</div>
							<div>{translate key=InstallPasswordLocation args="$ConfigPath,$ConfigSetting"}</div>
							<div>{textbox type="password" name="INSTALL_PASSWORD" size="20"}</div>
							<div>
								<button type="submit" name="" class="btn btn-outline-secondary mt-2"
									value="submit">{translate key=Next}<i
										class="bi bi-arrow-right-circle-fill ms-1"></i></button>
							</div>
						</div>
					{/if}

					{if $ShowDatabasePrompt}
						<div class="list-group-numbered">
							<li class="list-group-item mb-3">{translate key=VerifyInstallSettings args=$ConfigPath}
								<div class="ms-3">
									<div><span class="fw-bold">{translate key=DatabaseName}:</span> {$dbname}</div>
									<div><span class="fw-bold">{translate key=DatabaseUser}:</span> {$dbuser}</div>
									<div><span class="fw-bold">{translate key=DatabaseHost}:</span> {$dbhost}</div>
								</div>
							</li>
							<li class="list-group-item">{translate key=DatabaseCredentials}
								<div class="form-group ms-3">
									<label class="fw-bold" for="dbUser">{translate key=MySQLUser}</label>
									{textbox name="INSTALL_DB_USER" size="20" id=dbUser}
								</div>
								<div class="form-group ms-3 mb-3">
									<label class="fw-bold" for="dbPassword">{translate key=Password}</label>
									{textbox type="password" name="INSTALL_DB_PASSWORD" size="20" id=dbPassword}
								</div>
							</li>
							{if $ShowInstallOptions}
								<li class="list-group-item mb-3"><span
										class="fst-italic">{translate key=InstallOptionsWarning}</span>
									<div class="ms-3">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="create_database"
												name="create_database" />
											<label class="form-check-label" for="create_database">{translate key=CreateDatabase}
												({$dbname})<span
													class="text-danger fw-bold ms-1">{translate key=DataWipeWarning}</span></label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="create_user"
												name="create_user" />
											<label class="form-check-label" for="create_user">
												{translate key=CreateDatabaseUser}
												({$dbuser})</label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="create_sample_data"
												name="create_sample_data" />
											<label class="form-check-label" for="create_sample_data">
												{translate key=PopulateExampleData}</label>
										</div>
										<div class="form-check ms-4">
											<input class="form-check-input" type="checkbox" id="create_large_sample_data"
												name="create_large_sample_data" />
											<label class="form-check-label" for="create_large_sample_data">
												{translate key=PopulateLargeExampleData}</label>
										</div>
									</div>
								</li>
								<div>
									<button type="submit" name="run_install" class="btn btn-outline-secondary"
										value="submit">{translate key=RunInstallation}<i
											class="bi bi-arrow-right-circle-fill ms-1"></i>
								</div>
							{/if}
							{if $ShowUpgradeOptions}
								<li class="list-group-item">
									{translate key=UpgradeNotice args="$CurrentVersion,$TargetVersion"}
									<div class="my-3">
										<button type="submit" name="run_upgrade" class="btn btn-outline-secondary"
											value="submit">{translate key=RunUpgrade}<i
												class="bi bi-arrow-right-circle-fill ms-1"></i>
									</div>
								</li>
							{/if}
						</div>
					{/if}

					<div class="no-style">
						{foreach from=$installresults item=result}
							<div>{translate key=Executing}: {$result->taskName}</div>
							{if $result->WasSuccessful()}
								<div class="alert alert-success">{translate key=Success}</div>
							{else}
								<div class="alert alert-danger">
									{translate key=StatementFailed}
									<div class='no-style'>
										<div>{translate key=SQLStatement}
											<pre>{$result->sqlText}</pre>
										</div>
										<div>{translate key=ErrorCode}
											<pre>{$result->sqlErrorCode}</pre>
										</div>
										<div>{translate key=ErrorText}
											<pre>{$result->sqlErrorText}</pre>
										</div>
									</div>
								</div>
							{/if}
						{/foreach}
						<div>&nbsp;</div>
						<div>
							{if $InstallCompletedSuccessfully}
								{translate key=InstallationSuccess}
								<br />
								<a href="{$Path}{Pages::REGISTRATION}" class="link-primary">{translate key=Register}</a>
								{translate key=RegisterAdminUser args="$ConfigPath"}
								<br />
								<br />
								<a href="{$Path}{Pages::LOGIN}" class="link-primary">{translate key=Login}</a>
								{translate key=LoginWithSampleAccounts}
							{/if}
							{if $UpgradeCompletedSuccessfully}
								{translate key=InstalledVersion args=$TargetVersion}
								<h3><a href="configure.php" class="link-primary">{translate key=InstallUpgradeConfig}</a>
								</h3>
							{/if}
							{if $InstallFailed}
								{translate key=InstallationFailure}
							{/if}
						</div>
					</div>


				</form>
			</div>
		</div>
	</div>
</div>

{include file="javascript-includes.tpl"}
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const sampleData = document.getElementById('create_sample_data');
		const largeSampleData = document.getElementById('create_large_sample_data');
		if (sampleData && largeSampleData) {
			largeSampleData.addEventListener('change', function () {
				if (this.checked) {
					sampleData.checked = true;
				}
			});
			sampleData.addEventListener('change', function () {
				if (!this.checked) {
					largeSampleData.checked = false;
				}
			});
		}
	});
</script>
{include file='globalfooter.tpl'}