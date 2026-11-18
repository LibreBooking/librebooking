<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class SwitchBackPage extends SecurePage
{
    private IImpersonationService $impersonationService;

    public function __construct()
    {
        parent::__construct('', 0);

        $this->impersonationService = new ImpersonationService(PluginManager::Instance()->LoadAuthorization(), new UserRepository());
    }

    public function PageLoad(): void
    {
        // EnforceCSRFCheck() only validates POSTs, so reject everything else.
        if (!$this->IsPostBack()) {
            http_response_code(405);
            return;
        }

        $this->EnforceCSRFCheck();

        $this->impersonationService->StopImpersonation();

        $this->SetJson(['success' => true]);
    }
}
