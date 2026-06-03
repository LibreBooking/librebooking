<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/SavedReport.php');

class FakeSavedReport extends SavedReport
{
    public function __construct()
    {
        parent::__construct(
            'fake',
            1,
            new Report_Usage(Report_Usage::ACCESSORIES),
            new Report_ResultSelection(Report_ResultSelection::COUNT),
            new Report_GroupBy(Report_GroupBy::NONE),
            new Report_Range(Report_Range::ALL_TIME, Date::Now(), Date::Now()),
            new Report_Filter(null, null, null, null, null, null, null, null)
        );
    }
}
