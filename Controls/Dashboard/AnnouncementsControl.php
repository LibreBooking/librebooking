<?php

require_once(ROOT_DIR . 'Controls/Dashboard/DashboardItem.php');
require_once(ROOT_DIR . 'Presenters/Dashboard/AnnouncementPresenter.php');

class AnnouncementsControl extends DashboardItem implements IAnnouncementsControl
{
    private $presenter;

    public function __construct(\LibreBooking\Common\Templating\TemplateRenderer|SmartyPage $smarty)
    {
        parent::__construct($smarty);
        $this->presenter = new AnnouncementPresenter($this, new AnnouncementRepository(), PluginManager::Instance()->LoadPermission());
    }

    public function PageLoad()
    {
        $this->presenter->PageLoad();
        $this->Display('announcements.tpl');
    }

    public function SetAnnouncements($announcements)
    {
        $this->Assign('Announcements', $announcements);
    }
}

interface IAnnouncementsControl
{
    public function SetAnnouncements($announcements);
}
