<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageBlackoutsPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageBlackoutsPresenter.php');

class ManageBlackoutsPageTest extends TestBase
{
    public function testDeletedBlackoutIdsIsEmptyWhenNothingIsSubmitted(): void
    {
        $this->assertSame([], $this->CreatePage()->GetDeletedBlackoutIds());
    }

    public function testDeletedBlackoutIdsWrapsASingleId(): void
    {
        $this->fakeServer->SetForm(FormKeys::BLACKOUT_INSTANCE_ID, '5');

        $this->assertSame([5], $this->CreatePage()->GetDeletedBlackoutIds());
    }

    public function testDeletedBlackoutIdsParsesSubmittedList(): void
    {
        $this->fakeServer->SetForm(FormKeys::BLACKOUT_INSTANCE_ID, ['5', '6']);

        $this->assertSame([5, 6], $this->CreatePage()->GetDeletedBlackoutIds());
    }

    public function testBlackoutResourceIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::RESOURCE_ID, ['3', '7']);

        $this->assertSame([3, 7], $this->CreatePage()->GetBlackoutResourceIds());
    }

    public function testBlackoutResourceIdsIsEmptyWhenNothingIsSelected(): void
    {
        $this->assertSame([], $this->CreatePage()->GetBlackoutResourceIds());
    }

    public function testBlackoutIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetQuerystring(QueryStringKeys::BLACKOUT_ID, '12');
        $this->fakeServer->SetForm(FormKeys::BLACKOUT_INSTANCE_ID, '34');
        $page = $this->CreatePage();

        $this->assertSame(12, $page->GetBlackoutId());
        $this->assertSame(34, $page->GetUpdateBlackoutId());
        $this->assertNull($page->GetScheduleId());
    }

    /**
     * @return array<string, array{mixed, string|null}>
     */
    public static function seriesUpdateScopeProvider(): array
    {
        return [
            'this instance' => [SeriesUpdateScope::ThisInstance, SeriesUpdateScope::ThisInstance],
            'full series' => [SeriesUpdateScope::FullSeries, SeriesUpdateScope::FullSeries],
            'omitted' => [null, SeriesUpdateScope::FullSeries],
            'blank' => ['', SeriesUpdateScope::FullSeries],
            // Blackouts have no future-instances scope.
            'future instances' => [SeriesUpdateScope::FutureInstances, null],
            'unknown' => ['garbage', null],
            // Not blank: empty() would take this for an omitted field and
            // widen it to the full series.
            'zero' => ['0', null],
            'a list' => [[SeriesUpdateScope::ThisInstance], null],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('seriesUpdateScopeProvider')]
    public function testSeriesUpdateScopeIsASupportedScopeOrNull(mixed $submitted, ?string $expected): void
    {
        if ($submitted !== null) {
            $this->fakeServer->SetForm(name: FormKeys::SERIES_UPDATE_SCOPE, value: $submitted);
        }

        $this->assertSame($expected, $this->CreatePage()->GetSeriesUpdateScope());
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function unsupportedSeriesUpdateScopeProvider(): array
    {
        return [
            'future instances' => [SeriesUpdateScope::FutureInstances],
            'unknown' => ['garbage'],
            'zero' => ['0'],
            'a list' => [[SeriesUpdateScope::FullSeries]],
        ];
    }

    /**
     * The presenter tests mock the page, so they cannot show what the
     * presenter receives from a real request.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('unsupportedSeriesUpdateScopeProvider')]
    public function testBlackoutIsNotDeletedOrUpdatedWithAnUnsupportedScope(mixed $scope): void
    {
        $this->fakeServer->SetQuerystring(name: QueryStringKeys::BLACKOUT_ID, value: '5');
        $this->fakeServer->SetForm(name: FormKeys::BLACKOUT_INSTANCE_ID, value: '5');
        $this->fakeServer->SetForm(name: FormKeys::SERIES_UPDATE_SCOPE, value: $scope);

        $blackoutsService = $this->createMock(IManageBlackoutsService::class);
        $blackoutsService->expects($this->never())->method('Delete');
        $blackoutsService->expects($this->never())->method('Update');

        $page = $this->CreatePage();
        $presenter = new ManageBlackoutsPresenter(
            page: $page,
            manageBlackoutsService: $blackoutsService,
            scheduleRepository: $this->createMock(IScheduleRepository::class),
            resourceRepository: $this->createMock(IResourceRepository::class),
        );

        $presenter->DeleteBlackout();
        $presenter->UpdateBlackout();

        // A successful delete has an empty response, so the rejected delete
        // has to be told apart by its status. The rejected update is reported
        // to the page as a failure.
        $this->assertSame(1, $page->deletesRejected);
        $this->assertSame([false], $page->updateResults);
    }

    public function testRejectedDeleteIsABadRequest(): void
    {
        $statusBefore = http_response_code();

        try {
            $page = new class () extends ManageBlackoutsPage {
                public function __construct()
                {
                    $this->server = ServiceLocator::GetServer();
                }
            };
            $page->ShowDeleteRejected();

            $this->assertSame(400, http_response_code());
        } finally {
            // No status is set yet when the tests run from the command line.
            http_response_code(is_int($statusBefore) ? $statusBefore : 200);
        }
    }

    public function testBlackoutIsDeletedWithASupportedScope(): void
    {
        $this->fakeServer->SetQuerystring(name: QueryStringKeys::BLACKOUT_ID, value: '5');
        $this->fakeServer->SetForm(name: FormKeys::SERIES_UPDATE_SCOPE, value: SeriesUpdateScope::ThisInstance);

        $blackoutsService = $this->createMock(IManageBlackoutsService::class);
        $blackoutsService->expects($this->once())
            ->method('Delete')
            ->with(5, SeriesUpdateScope::ThisInstance);

        $presenter = new ManageBlackoutsPresenter(
            page: $this->CreatePage(),
            manageBlackoutsService: $blackoutsService,
            scheduleRepository: $this->createMock(IScheduleRepository::class),
            resourceRepository: $this->createMock(IResourceRepository::class),
        );

        $presenter->DeleteBlackout();
    }

    private function CreatePage(): RecordingManageBlackoutsPage
    {
        return new RecordingManageBlackoutsPage();
    }
}

/**
 * The real page, reading from the fake server, with its responses recorded
 * instead of sent: the template engine is not set up in these tests, and the
 * response status is shared by the whole test run.
 */
class RecordingManageBlackoutsPage extends ManageBlackoutsPage
{
    /** @var bool[] the result of each update shown, in order */
    public array $updateResults = [];

    public int $deletesRejected = 0;

    public function __construct()
    {
        $this->server = ServiceLocator::GetServer();
    }

    public function ShowUpdateResult(
        $wasAddedSuccessfully,
        $displayMessage,
        $conflictingReservations,
        $conflictingBlackouts,
        $timezone
    ) {
        $this->updateResults[] = $wasAddedSuccessfully;
    }

    public function ShowDeleteRejected(): void
    {
        $this->deletesRejected++;
    }
}
