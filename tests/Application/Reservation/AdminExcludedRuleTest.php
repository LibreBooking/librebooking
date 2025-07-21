<?php

class AdminExcludedRuleTest extends TestBase
{
    /**
     * @var AdminExcludedRule
     */
    private $rule;

    /**
     * @var IReservationValidationRule|PHPUnit\Framework\MockObject\MockObject
     */
    private $baseRule;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepository;

    /**
     * @var User|PHPUnit\Framework\MockObject\MockObject
     */
    private $user;

    /**
     * @var TestReservationSeries
     */
    private $reservationSeries;

    private $resource1;
    private $resource2;

    public function setUp(): void
    {
        parent::setup();

        $this->userRepository = $this->createMock('IUserRepository');
        $this->baseRule = $this->createMock('IReservationValidationRule');
        $this->user =  $this->createMock('User');

        $this->rule = new AdminExcludedRule($this->baseRule, $this->fakeUser, $this->userRepository);

        $this->reservationSeries = new TestReservationSeries();
        $this->resource1 = new FakeBookableResource(1);
        $this->resource2 = new FakeBookableResource(2);

        $this->reservationSeries->WithResource($this->resource1);
        $this->reservationSeries->AddResource($this->resource2);
    }

    public function testIfUserIsApplicationAdmin_ReturnTrue()
    {
        $this->fakeUser->IsAdmin = true;

        $result = $this->rule->Validate($this->reservationSeries, null);

        $this->assertTrue($result->IsValid());
    }

    public function testIfUserIsAdminForAllResources_ReturnTrue()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsScheduleAdmin = true;

        $this->userRepository->expects($this->once())
                    ->method('LoadById')
                    ->with($this->equalTo($this->fakeUser->UserId))
                    ->willReturn($this->user);

        $this->user->expects($this->exactly(2))
                    ->method('IsResourceAdminFor')
                    ->willReturn(true);

        $result = $this->rule->Validate($this->reservationSeries, null);

        $this->assertTrue($result->IsValid());
    }

    public function testIfUserIsNotAdminForAtLeastOneResource_InvokeBaseRule()
    {
        $expectedResult = new ReservationValidationResult(false, ['some error']);
        $this->fakeUser->IsAdmin = false;

        $this->userRepository->expects($this->once())
                    ->method('LoadById')
                    ->with($this->equalTo($this->fakeUser->UserId))
                    ->willReturn($this->user);

        $this->user->expects($this->exactly(2))
                    ->method('IsResourceAdminFor')
                    ->willReturnCallback(function ($resource) {
                        return $this->equalTo($this->resource1)->evaluate($resource, '', true);
                    });

        $this->baseRule->expects($this->once())
                    ->method('Validate')
                    ->with($this->equalTo($this->reservationSeries))
                    ->willReturn($expectedResult);

        $result = $this->rule->Validate($this->reservationSeries, null);

        $this->assertEquals($expectedResult, $result);
    }

    public function testIfUserIsAdminForReservationUserReturnTrue()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;

        $adminUser =  $this->createMock('User');
        $reservationUser =  $this->createMock('User');

        $this->userRepository->expects($this->exactly(2))
                            ->method('LoadById')
                            ->willReturnMap([
                                [$this->fakeUser->UserId, $adminUser],
                                [$this->reservationSeries->UserId(), $reservationUser]
                            ]);

        $adminUser->expects($this->once())
                    ->method('IsAdminFor')
                    ->with($this->equalTo($reservationUser))
                    ->willReturn(true);

        $result = $this->rule->Validate($this->reservationSeries, null);

        $this->assertTrue($result->IsValid());
    }
}
