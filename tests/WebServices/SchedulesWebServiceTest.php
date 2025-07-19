<?php

use function PHPUnit\Framework\matches;

require_once(ROOT_DIR . 'WebServices/SchedulesWebService.php');

class SchedulesWebServiceTest extends TestBase
{
    /**
     * @var SchedulesWebService
     */
    private $service;

    /**
     * @var FakeRestServer
     */
    private $server;

    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var IPrivacyFilter
     */
    private $privacyFilter;

    public function setUp(): void
    {
        parent::setup();

        $this->server = new FakeRestServer();
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->privacyFilter = $this->createMock('IPrivacyFilter');

        $this->service = new SchedulesWebService($this->server, $this->scheduleRepository, $this->privacyFilter);
    }

    public function testGetsAllSchedules()
    {
        $schedules = [new FakeSchedule()];

        $this->scheduleRepository->expects($this->once())
                ->method('GetAll')
                ->willReturn($schedules);

        $this->service->GetSchedules();

        $expectedResponse = new SchedulesResponse($this->server, $schedules);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
    }

    public function testGetsScheduleById()
    {
        $scheduleId = 89181;

        $schedule = new FakeSchedule($scheduleId);
        $layout = $this->createMock('IScheduleLayout');

        $layout->expects($this->any())
                ->method('GetLayout')
                ->with($this->anything())
                ->willReturn([]);

        $this->scheduleRepository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($scheduleId))
                ->willReturn($schedule);

        $this->scheduleRepository->expects($this->once())
                ->method('GetLayout')
                ->with(
                    $this->equalTo($scheduleId),
                    $this->equalTo(new ScheduleLayoutFactory($this->server->GetSession()->Timezone))
                )
                ->willReturn($layout);

        $this->service->GetSchedule($scheduleId);

        $expectedResponse = new ScheduleResponse($this->server, $schedule, $layout);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
    }

    public function testWhenScheduleNotFound()
    {
        $scheduleId = 89181;
        $this->scheduleRepository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($scheduleId))
                ->willReturn(null);

        $this->service->GetSchedule($scheduleId);

        $this->assertEquals(RestResponse::NotFound(), $this->server->_LastResponse);
    }

    public function testScheduleResponseReturnsLayoutForEachDayOfWeek()
    {
        $schedule = new FakeSchedule();
        $layout = $this->createMock('IScheduleLayout');
        $timezone = $this->server->GetSession()->Timezone;

        $date1 = Date::Now()->ToTimezone($timezone);
        $date2 = $date1->AddDays(1);
        $date3 = $date1->AddDays(2);
        $date4 = $date1->AddDays(3);
        $date5 = $date1->AddDays(4);
        $date6 = $date1->AddDays(5);
        $date7 = $date1->AddDays(6);

        $periods1 = [new SchedulePeriod($date1, $date1)];
        $periods2 = [new SchedulePeriod($date2, $date2)];
        $periods3 = [new SchedulePeriod($date3, $date3)];
        $periods4 = [new SchedulePeriod($date4, $date4)];
        $periods5 = [new SchedulePeriod($date5, $date5)];
        $periods6 = [new SchedulePeriod($date6, $date6)];
        $periods7 = [new SchedulePeriod($date7, $date7)];

        $matcher = $this->exactly(7);
        $layout->expects($matcher)
                ->method('GetLayout')
                ->willReturnCallback(function($date) use (
                        $periods1, $periods2, $periods3, $periods4, $periods5,
                        $periods6, $periods7,
                        $date1, $date2, $date3, $date4, $date5, $date6, $date7) {
                    return match (true) {
                        $this->equalTo($date1)->evaluate($date, returnResult: true) => $periods1,
                        $this->equalTo($date2)->evaluate($date, returnResult: true) => $periods2,
                        $this->equalTo($date3)->evaluate($date, returnResult: true) => $periods3,
                        $this->equalTo($date4)->evaluate($date, returnResult: true) => $periods4,
                        $this->equalTo($date5)->evaluate($date, returnResult: true) => $periods5,
                        $this->equalTo($date6)->evaluate($date, returnResult: true) => $periods6,
                        $this->equalTo($date7)->evaluate($date, returnResult: true) => $periods7,
                        default => throw new Exception('Unexpected date')
                    };
                });

        $response = new ScheduleResponse($this->server, $schedule, $layout);

        $this->assertEquals([new SchedulePeriodResponse($periods1[0])], $response->periods[$date1->Weekday()]);
        $this->assertEquals([new SchedulePeriodResponse($periods2[0])], $response->periods[$date2->Weekday()]);
        $this->assertEquals([new SchedulePeriodResponse($periods3[0])], $response->periods[$date3->Weekday()]);
        $this->assertEquals([new SchedulePeriodResponse($periods4[0])], $response->periods[$date4->Weekday()]);
        $this->assertEquals([new SchedulePeriodResponse($periods5[0])], $response->periods[$date5->Weekday()]);
        $this->assertEquals([new SchedulePeriodResponse($periods6[0])], $response->periods[$date6->Weekday()]);
        $this->assertEquals([new SchedulePeriodResponse($periods7[0])], $response->periods[$date7->Weekday()]);
    }
}
