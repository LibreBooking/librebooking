<?php

require_once(ROOT_DIR . 'Presenters/Admin/ManageReservationsPresenter.php');

class ManageReservationsPresenterTest extends TestBase
{
    /**
     * @var ManageReservationsPresenter
     */
    private $presenter;

    /**
     * @var IManageReservationsPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IManageReservationsService|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationsService;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduleRepository;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepository;

    /**
     * @var IAttributeService|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeService;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepository;

    /**
     * @var ITermsOfServiceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $termsOfServiceRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IManageReservationsPage');
        $this->reservationsService = $this->createMock('IManageReservationsService');
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->attributeService = $this->createMock('IAttributeService');
        $this->userRepository = $this->createMock('IUserRepository');
        $this->termsOfServiceRepository = $this->createMock('ITermsOfServiceRepository');

        $this->presenter = new ManageReservationsPresenter(
            $this->page,
            $this->reservationsService,
            $this->scheduleRepository,
            $this->resourceRepository,
            $this->attributeService,
            $this->userRepository,
            $this->termsOfServiceRepository
        );

        $this->userRepository->expects($this->any())
            ->method('LoadById')
            ->with($this->anything())
            ->willReturn(new FakeUser());
    }

    public function testUsesTwoWeekSpanWhenNoDateFilterProvided()
    {
        $userTimezone = 'America/Chicago';
        $defaultStart = Date::Now()->AddDays(-7)->ToTimezone($userTimezone)->GetDate();
        $defaultEnd = Date::Now()->AddDays(7)->ToTimezone($userTimezone)->GetDate();
        $searchedScheduleId = 15;
        $searchedResourceId = 105;
        $searchedStatusId = ReservationStatus::Pending;
        $searchedUserId = 111;
        $searchedReferenceNumber = 'abc123';
        $searchedUserName = 'some user';
        $searchedResourceStatusId = 292;
        $searchedResourceStatusReasonId = 4292;
        /** @var TestCustomAttribute[] $customAttributes */
        $customAttributes = [new TestCustomAttribute(1, 'something')];
        /** @var Attribute[] $attributes */
        $attributes = [new LBAttribute($customAttributes[0], 'value')];

        $this->resourceRepository->expects($this->once())
            ->method('GetStatusReasons')
            ->willReturn([]);

        $this->page->expects($this->any())
            ->method('FilterButtonPressed')
            ->willReturn(true);

        $this->page->expects($this->atLeastOnce())
            ->method('GetStartDate')
            ->willReturn(null);

        $this->page->expects($this->atLeastOnce())
            ->method('GetEndDate')
            ->willReturn(null);

        $this->page->expects($this->atLeastOnce())
            ->method('GetScheduleId')
            ->willReturn($searchedScheduleId);

        $this->page->expects($this->atLeastOnce())
            ->method('GetResourceId')
            ->willReturn($searchedResourceId);

        $this->page->expects($this->atLeastOnce())
            ->method('GetReservationStatusId')
            ->willReturn($searchedStatusId);

        $this->page->expects($this->atLeastOnce())
            ->method('GetUserId')
            ->willReturn($searchedUserId);

        $this->page->expects($this->atLeastOnce())
            ->method('GetUserName')
            ->willReturn($searchedUserName);

        $this->page->expects($this->atLeastOnce())
            ->method('GetReferenceNumber')
            ->willReturn($searchedReferenceNumber);

        $this->page->expects($this->atLeastOnce())
            ->method('GetResourceStatusFilterId')
            ->willReturn($searchedResourceStatusId);

        $this->page->expects($this->atLeastOnce())
            ->method('GetResourceStatusReasonFilterId')
            ->willReturn($searchedResourceStatusReasonId);

        $this->page->expects($this->atLeastOnce())
            ->method('GetAttributeFilters')
            ->willReturn([new AttributeFormElement($customAttributes[0]->Id(), 'value')]);

        $filter = $this->GetExpectedFilter(
            $defaultStart,
            $defaultEnd,
            $searchedReferenceNumber,
            $searchedScheduleId,
            $searchedResourceId,
            $searchedUserId,
            $searchedStatusId,
            $searchedResourceStatusId,
            $searchedResourceStatusReasonId,
            $attributes
        );

        $data = new PageableData($this->getReservations());
        $this->reservationsService->expects($this->once())
            ->method('LoadFiltered')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->equalTo($filter),
                $this->equalTo($this->fakeUser)
            )
            ->willReturn($data);

        $this->attributeService->expects($this->once())
            ->method('GetByCategory')
            ->with($this->equalTo(CustomAttributeCategory::RESERVATION))
            ->willReturn($customAttributes);

        $this->page->expects($this->once())
            ->method('SetAttributeFilters')
            ->with($attributes);

        $this->page->expects($this->once())
            ->method('SetStartDate')
            ->with($this->equalTo($defaultStart));

        $this->page->expects($this->once())
            ->method('SetEndDate')
            ->with($this->equalTo($defaultEnd));

        $this->page->expects($this->once())
            ->method('SetReferenceNumber')
            ->with($this->equalTo($searchedReferenceNumber));

        $this->page->expects($this->once())
            ->method('SetScheduleId')
            ->with($this->equalTo($searchedScheduleId));

        $this->page->expects($this->once())
            ->method('SetResourceId')
            ->with($this->equalTo($searchedResourceId));

        $this->page->expects($this->once())
            ->method('SetReservationStatusId')
            ->with($this->equalTo($searchedStatusId));

        $this->page->expects($this->once())
            ->method('SetUserId')
            ->with($this->equalTo($searchedUserId));

        $this->page->expects($this->once())
            ->method('SetUserName')
            ->with($this->equalTo($searchedUserName));

        $this->page->expects($this->once())
            ->method('SetUserName')
            ->with($this->equalTo($searchedUserName));

        $this->page->expects($this->once())
            ->method('SetResourceStatusFilterId')
            ->with($this->equalTo($searchedResourceStatusId));

        $this->page->expects($this->once())
            ->method('SetResourceStatusReasonFilterId')
            ->with($this->equalTo($searchedResourceStatusReasonId));

        $this->presenter->PageLoad($userTimezone);
    }

    public function testUpdatesSingleResourceStatus()
    {
        $resourceId = 19191;
        $resourceStatusId = ResourceStatus::HIDDEN;
        $resourceStatusReasonId = 111;
        $referenceNumber = 'abc123';

        $resource = new FakeBookableResource($resourceId);
        $resource->ChangeStatus(ResourceStatus::AVAILABLE, null);

        $this->page->expects($this->once())
            ->method('CanUpdateResourceStatuses')
            ->willReturn(true);

        $this->page->expects($this->once())
            ->method('GetResourceStatus')
            ->willReturn($resourceStatusId);

        $this->page->expects($this->once())
            ->method('GetUpdateScope')
            ->willReturn('');

        $this->page->expects($this->once())
            ->method('GetResourceStatusReason')
            ->willReturn($resourceStatusReasonId);

        $this->page->expects($this->once())
            ->method('GetUpdateResourceId')
            ->willReturn($resourceId);

        $this->page->expects($this->once())
            ->method('GetResourceStatusReferenceNumber')
            ->willReturn($referenceNumber);

        $this->resourceRepository->expects($this->once())
            ->method('LoadById')
            ->with($resourceId)
            ->willReturn($resource);

        $this->resourceRepository->expects($this->once())
            ->method('Update')
            ->with($this->anything());

        $this->presenter->UpdateResourceStatus();

        $this->assertEquals($resourceStatusId, $resource->GetStatusId());
        $this->assertEquals($resourceStatusReasonId, $resource->GetStatusReasonId());
    }

    public function testUpdatesReservationResourceStatuses()
    {
        $resourceStatusId = ResourceStatus::HIDDEN;
        $resourceStatusReasonId = 111;
        $referenceNumber = 'abc123';

        $reservations = [new TestReservationItemView(1, Date::Now(), Date::Now(), 1), new TestReservationItemView(1, Date::Now(), Date::Now(), 2)];
        $pageableReservations = new PageableData($reservations);

        $resource1 = new FakeBookableResource(1);
        $resource1->ChangeStatus(ResourceStatus::AVAILABLE, null);

        $resource2 = new FakeBookableResource(2);
        $resource2->ChangeStatus(ResourceStatus::AVAILABLE, null);

        $this->page->expects($this->once())
            ->method('CanUpdateResourceStatuses')
            ->willReturn(true);

        $this->page->expects($this->once())
            ->method('GetResourceStatus')
            ->willReturn($resourceStatusId);

        $this->page->expects($this->once())
            ->method('GetUpdateScope')
            ->willReturn('all');

        $this->page->expects($this->once())
            ->method('GetResourceStatusReason')
            ->willReturn($resourceStatusReasonId);

        $this->page->expects($this->once())
            ->method('GetResourceStatusReferenceNumber')
            ->willReturn($referenceNumber);

        $this->reservationsService->expects($this->once())
            ->method('LoadFiltered')
            ->with(
                $this->isNull(),
                $this->isNull(),
                $this->anything(),
                $this->anything(),
                $this->equalTo(new ReservationFilter(null, null, $referenceNumber, null, null, null, null, null)),
                $this->equalTo($this->fakeUser)
            )
            ->willReturn($pageableReservations);

        $this->resourceRepository->expects($this->exactly(2))
            ->method('LoadById')
            ->willReturnMap(
            [
                [1, $resource1],
                [2, $resource2]
            ]);

        $this->resourceRepository->expects($this->exactly(2))
            ->method('Update')
            ->with($this->anything());

        $this->presenter->UpdateResourceStatus();

        $this->assertEquals($resourceStatusId, $resource1->GetStatusId());
        $this->assertEquals($resourceStatusReasonId, $resource1->GetStatusReasonId());
    }

    public function testLoadsReservation()
    {
        $referenceNumber = 'rn';

        $reservationView = new ReservationView();

        $this->page->expects($this->once())
            ->method('GetReferenceNumber')
            ->willReturn($referenceNumber);

        $this->reservationsService->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber), $this->equalTo($this->fakeUser))
            ->willReturn($reservationView);

        $this->page->expects($this->once())
            ->method('SetReservationJson')
            ->with($this->equalTo($reservationView));

        $this->presenter->ProcessDataRequest('load');
    }

    public function testUpdatesReservationAttribute()
    {
        $referenceNumber = 'rn';
        $attrId = 'attid';
        $attrValue = 'val';

        $this->page->expects($this->once())
            ->method('GetReferenceNumber')
            ->willReturn($referenceNumber);

        $this->page->expects($this->once())
            ->method('GetName')
            ->willReturn($attrId);

        $this->page->expects($this->once())
            ->method('GetValue')
            ->willReturn($attrValue);

        $this->reservationsService->expects($this->once())
            ->method('UpdateAttribute')
            ->with($this->equalTo($referenceNumber), $this->equalTo($attrId), $this->equalTo($attrValue), $this->equalTo($this->fakeUser))
            ->willReturn([]);

        $this->presenter->UpdateAttribute();
    }

    public function testImportsReservations()
    {
        ReferenceNumberGenerator::$__referenceNumber = 'test';
        $this->fakeUser->IsAdmin = true;

        $importFile = new FakeUploadedFile();
        $importFile->Contents = "email,resource names,title,description,begin,end,att1,att2\n" .
            "u@e.com,\"r1,r2\",title,description,1/2/17 8:30 am,1/2/17 9:30 am,a1value,a2value\n" .
            "u2@e.com,r2,title2,description2,1/4/17 8:30 pm,1/4/17 22:00,,\n" .
            "madeupuser,r2,title2,description2,1/4/17 8:30 pm,1/4/17 22:00,,\n" .
            "u@e.com,makeupresource,title2,description2,1/4/17 8:30 pm,1/4/17 22:00,,\n" .
            "u@e.com,r2,title2,description2,unparseabledate,1/4/17 22:00,,";

        $attributes = [new TestCustomAttribute(1, 'att1'), new TestCustomAttribute(2, 'att2'), new TestCustomAttribute(3, 'att3')];

        $resources = [new FakeBookableResource(1, 'r1'), new FakeBookableResource(2, 'r2')];

        $users = [new FakeUser(1, 'u@e.com'), new FakeUser(2, 'u2@e.com')];

        $res1 = ReservationSeries::Create(1, $resources[0], 'title', 'description', DateRange::Create('2017-1-2 8:30', '2017-1-2 9:30', $this->fakeUser->Timezone), new RepeatNone(), $this->fakeUser);
        $res1->AddResource($resources[1]);
        $res1->AddAttributeValue(new AttributeValue(1, 'a1value'));
        $res1->AddAttributeValue(new AttributeValue(2, 'a2value'));

        $res2 = ReservationSeries::Create(2, $resources[1], 'title2', 'description2', DateRange::Create('2017-1-4 20:30', '2017-1-4 22:00', $this->fakeUser->Timezone), new RepeatNone(), $this->fakeUser);

        $this->page->expects($this->once())
            ->method('GetImportFile')
            ->willReturn($importFile);

        $this->attributeService->expects($this->once())
            ->method('GetByCategory')
            ->with($this->equalTo(CustomAttributeCategory::RESERVATION))
            ->willReturn($attributes);

        $this->resourceRepository->expects($this->once())
            ->method('GetResourceList')
            ->willReturn($resources);

        $this->userRepository->expects($this->once())
            ->method('GetAll')
            ->willReturn($users);

        $matcher = $this->exactly(2);
        $this->reservationsService->expects($matcher)
            ->method('UnsafeAdd')
            ->willReturnCallback(function ($res) use ($matcher, $res1, $res2)
            {
                match ($matcher->numberOfInvocations())
                {
                    1 => $this->assertEquals($res, $res1),
                    2 => $this->assertEquals($res, $res2)
                };
            });

        $this->page->expects($this->once())
            ->method('SetImportResult')
            ->with($this->equalTo(new CsvImportResult(2, [], ['Invalid data in row 3. Ensure the user and resource in this row exist.', 'Invalid data in row 4. Ensure the user and resource in this row exist.', 'Invalid data in row 5'])));

        $this->presenter->ImportReservations();
    }

    public function testDeletesMultiple()
    {
        $ids = [1, 2];

        $this->page->expects($this->once())
            ->method('GetDeletedReservationIds')
            ->willReturn($ids);

        $matcher = $this->exactly(2);
        $this->reservationsService->expects($matcher)
            ->method('UnsafeDelete')
            ->willReturnCallback(function(int $id, UserSession $session) use ($matcher)
            {
                $this->assertEquals($this->fakeUser, $session);
                match ($matcher->numberOfInvocations())
                {
                    1 => $this->assertEquals(1, $id),
                    2 => $this->assertEquals(2, $id)
                };
            });

        $this->presenter->DeleteMultiple();
    }

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @param string $referenceNumber
     * @param int $scheduleId
     * @param int $resourceId
     * @param int $userId
     * @param int $statusId
     * @param null $resourceStatusId
     * @param null $resourceStatusReasonId
     * @param Attribute[] $attributes
     * @return ReservationFilter
     */
    private function GetExpectedFilter(
        $startDate = null,
        $endDate = null,
        $referenceNumber = null,
        $scheduleId = null,
        $resourceId = null,
        $userId = null,
        $statusId = null,
        $resourceStatusId = null,
        $resourceStatusReasonId = null,
        $attributes = null
    ) {
        return new ReservationFilter($startDate, $endDate, $referenceNumber, $scheduleId, $resourceId, $userId, $statusId, $resourceStatusId, $resourceStatusReasonId, $attributes);
    }

    private function getReservations()
    {
        $reservations = [];
        for ($i = 1; $i <= 10; $i++) {
            $r1 = new ReservationItemView();
            $r1->SeriesId = $i;
            $reservations[] = $r1;
        }

        return $reservations;
    }
}
