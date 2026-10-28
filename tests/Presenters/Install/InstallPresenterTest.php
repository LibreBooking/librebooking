<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Install/InstallPresenter.php');
require_once(ROOT_DIR . 'Pages/Install/InstallPage.php');

class InstallPresenterTest extends TestBase
{
    public function testUnauthenticatedRequestNeverRunsInstallOrUpgrade()
    {
        $page = $this->createMock(IInstallPage::class);
        $page->method('RunningInstall')->willReturn(true);
        $page->method('RunningUpgrade')->willReturn(true);
        $page->expects($this->never())->method('SetInstallResults');
        $page->expects($this->never())->method('SetUpgradeResults');
        $page->expects($this->once())->method('SetShowPasswordPrompt')->with(true);

        $guard = $this->createMock(InstallSecurityGuard::class);
        $guard->method('IsAuthenticated')->willReturn(false);

        $this->loadPage(new InstallPresenter($page, $guard));
    }

    public function testWrongPasswordNeverRunsInstallOrUpgrade()
    {
        $page = $this->createMock(IInstallPage::class);
        $page->method('RunningInstall')->willReturn(true);
        $page->method('GetInstallPassword')->willReturn('wrong');
        $page->expects($this->never())->method('SetInstallResults');
        $page->expects($this->never())->method('SetUpgradeResults');
        $page->expects($this->once())->method('SetShowInvalidPassword')->with(true);

        $guard = $this->createMock(InstallSecurityGuard::class);
        $guard->method('IsAuthenticated')->willReturn(false);
        $guard->method('ValidatePassword')->with('wrong')->willReturn(false);

        $this->loadPage(new InstallPresenter($page, $guard));
    }

    /**
     * PageLoad() ends by asking a real Installer for the schema version. The
     * fake config has no database settings, so the connection fails; that is
     * after the gate and not what these tests are about.
     */
    private function loadPage(InstallPresenter $presenter): void
    {
        try {
            $presenter->PageLoad();
        } catch (mysqli_sql_exception) {
        }
    }
}
