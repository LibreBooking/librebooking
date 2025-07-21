<?php

class AccountActivationTest extends TestBase
{
    /**
     * @var IAccountActivationRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $activationRepo;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepo;

    /**
     * @var AccountActivation
     */
    private $activation;

    public function setUp(): void
    {
        parent::setup();

        $this->activationRepo = $this->createMock('IAccountActivationRepository');
        $this->userRepo = $this->createMock('IUserRepository');
        $this->activation = new AccountActivation($this->activationRepo, $this->userRepo);
    }

    public function testGetsActivationCodeAndSendsEmail()
    {
        $user = new FakeUser(100);

        $this->activationRepo->expects($this->once())
                ->method('AddActivation')
                ->with($this->equalTo($user), $this->anything());
        $this->activation->Notify($user);

        $this->assertInstanceOf('AccountActivationEmail', $this->fakeEmailService->_LastMessage);
    }

    public function testWhenActivationCodeIsValid()
    {
        $userId = 11;
        $homepage = Pages::CALENDAR;
        $user = new FakeUser($userId);
        $user->ChangeDefaultHomePage($homepage);
        $user->SetStatus(AccountStatus::AWAITING_ACTIVATION);

        $activationCode = uniqid();

        $this->activationRepo->expects($this->once())
                ->method('FindUserIdByCode')
                ->with($this->equalTo($activationCode))
                ->willReturn($userId);

        $this->activationRepo->expects($this->once())
                ->method('DeleteActivation')
                ->with($this->equalTo($activationCode));

        $this->userRepo->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($userId))
                ->willReturn($user);

        $this->userRepo->expects($this->once())
                ->method('Update')
                ->with($this->equalTo($user));

        $result = $this->activation->Activate($activationCode);

        $this->assertTrue($result->Activated());
        $this->assertEquals($user, $result->User());
        $this->assertEquals(AccountStatus::ACTIVE, $user->StatusId());
    }

    public function testWhenActivationCodeIsInvalid()
    {
        $activationCode = uniqid();

        $this->activationRepo->expects($this->once())
                ->method('FindUserIdByCode')
                ->with($this->equalTo($activationCode))
                ->willReturn(null);

        $result = $this->activation->Activate($activationCode);

        $this->assertFalse($result->Activated());
    }
}
