<?php

require_once(ROOT_DIR . 'lib/Application/Reporting/namespace.php');
require_once(ROOT_DIR . 'lib/Email/namespace.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReportEmailMessage.php');
require_once(ROOT_DIR . 'Domain/Access/ReportingRepository.php');

interface IReportingService
{
    /**
     * @param Report_Usage $usage
     * @param Report_ResultSelection $selection
     * @param Report_GroupBy $groupBy
     * @param Report_Range $range
     * @param Report_Filter $filter
     * @param string $timezone
     * @return IReport
     */
    public function GenerateCustomReport(Report_Usage $usage, Report_ResultSelection $selection, Report_GroupBy $groupBy, Report_Range $range, Report_Filter $filter, $timezone);

    /**
     * @param string $reportName
     * @param int $userId
     * @param Report_Usage $usage
     * @param Report_ResultSelection $selection
     * @param Report_GroupBy $groupBy
     * @param Report_Range $range
     * @param Report_Filter $filter
     */
    public function Save($reportName, $userId, Report_Usage $usage, Report_ResultSelection $selection, Report_GroupBy $groupBy, Report_Range $range, Report_Filter $filter);

    /**
     * @param int $userId
     * @return array|SavedReport[]
     */
    public function GetSavedReports($userId);

    /**
     * @param int $reportId
     * @param int $userId
     * @param string $timezone
     * @return IGeneratedSavedReport|null null when the user has no saved report with that ID
     */
    public function GenerateSavedReport($reportId, $userId, $timezone);

    /**
     * @param IGeneratedSavedReport $report
     * @param IReportDefinition $definition
     * @param string $toAddress
     * @param UserSession $reportUser
     * @param string $selectedColumns
     */
    public function SendReport($report, $definition, $toAddress, $reportUser, $selectedColumns);

    /**
     * @param int $reportId
     * @param int $userId
     */
    public function DeleteSavedReport($reportId, $userId);

    /**
     * @param ICannedReport $cannedReport
     * @return IReport
     */
    public function GenerateCommonReport(ICannedReport $cannedReport);
}


class ReportingService implements IReportingService
{
    /**
     * @var IReportingRepository
     */
    private $repository;

    /**
     * @var IAttributeRepository
     */
    private $attributeRepository;

    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @param IReportingRepository $repository
     * @param IAttributeRepository|null $attributeRepository
     * @param IScheduleRepository|null $scheduleRepository
     */
    public function __construct(IReportingRepository $repository, $attributeRepository = null, $scheduleRepository = null)
    {
        $this->repository = $repository;

        $this->attributeRepository = $attributeRepository;
        if ($attributeRepository == null) {
            $this->attributeRepository = new AttributeRepository();
        }

        $this->scheduleRepository = $scheduleRepository;
        if ($scheduleRepository == null) {
            $this->scheduleRepository = new ScheduleRepository();
        }
    }

    public function GenerateCustomReport(Report_Usage $usage, Report_ResultSelection $selection, Report_GroupBy $groupBy, Report_Range $range, Report_Filter $filter, $timezone)
    {
        $builder = new ReportCommandBuilder();

        $selection->Add($builder);
        if ($selection->Equals(Report_ResultSelection::FULL_LIST)) {
            $usage->Add($builder);
        }
        $groupBy->Add($builder);
        $range->Add($builder);
        $filter->Add($builder);

        $data = $this->repository->GetCustomReport($builder);

        if ($selection->Equals(Report_ResultSelection::UTILIZATION)) {
            $utilization = new ReportUtilizationData($data, $this->scheduleRepository, $range, $timezone);
            $data = $utilization->Rows();
        }
        return new CustomReport($data, $this->attributeRepository);
    }

    public function Save($reportName, $userId, Report_Usage $usage, Report_ResultSelection $selection, Report_GroupBy $groupBy, Report_Range $range, Report_Filter $filter)
    {
        $report = new SavedReport($reportName, $userId, $usage, $selection, $groupBy, $range, $filter);
        $this->repository->SaveCustomReport($report);
    }

    public function GetSavedReports($userId)
    {
        return $this->repository->LoadSavedReportsForUser($userId);
    }

    public function GenerateSavedReport($reportId, $userId, $timezone): ?IGeneratedSavedReport
    {
        $savedReport = $this->repository->LoadSavedReportForUser($reportId, $userId);

        if ($savedReport == null) {
            return null;
        }

        $report = $this->GenerateCustomReport($savedReport->Usage(), $savedReport->Selection(), $savedReport->GroupBy(), $savedReport->Range(), $savedReport->Filter(), $timezone);

        return new GeneratedSavedReport($savedReport, $report);
    }

    public function SendReport($report, $definition, $toAddress, $reportUser, $selectedColumns)
    {
        $message = new ReportEmailMessage($report, $definition, $toAddress, $reportUser, $selectedColumns);
        ServiceLocator::GetEmailService()->Send($message);
    }

    public function DeleteSavedReport($reportId, $userId)
    {
        $this->repository->DeleteSavedReport($reportId, $userId);
    }

    public function GenerateCommonReport(ICannedReport $cannedReport)
    {
        $data = $this->repository->GetCustomReport($cannedReport->GetBuilder());
        return new CustomReport($data, $this->attributeRepository);
    }
}
