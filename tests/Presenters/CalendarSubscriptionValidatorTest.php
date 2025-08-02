<?php

require_once(ROOT_DIR . 'Pages/Export/CalendarSubscriptionPage.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class CalendarSubscriptionValidatorTest extends TestBase
{
    /**
     * @var ICalendarSubscriptionPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var CalendarSubscriptionValidator
     */
    private $validator;

    /**
     * @var ICalendarSubscriptionService|PHPUnit\Framework\MockObject\MockObject
     */
    private $subscriptionService;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('ICalendarSubscriptionPage');
        $this->subscriptionService = $this->createMock('ICalendarSubscriptionService');
        $this->validator = new CalendarSubscriptionValidator($this->page, $this->subscriptionService);
    }

    public function testIsNotValidWhenTurnedOffForResource()
    {
        $resource = new FakeBookableResource(12);
        $resource->DisableSubscription();

        $publicId = uniqid();

        $this->page->expects($this->once())
                ->method('GetResourceId')
                ->willReturn($publicId);

        $this->subscriptionService->expects($this->once())
                ->method('GetResource')
                ->with($this->equalTo($publicId))
                ->willReturn($resource);

        $this->StubSubscriptionKey();

        $isValid = $this->validator->IsValid();

        $this->assertFalse($isValid);
    }

    public function testIsNotValidWhenTurnedOffForSchedule()
    {
        $schedule = new FakeSchedule(12);
        $schedule->DisableSubscription();

        $publicId = uniqid();

        $this->page->expects($this->once())
                ->method('GetScheduleId')
                ->willReturn($publicId);

        $this->subscriptionService->expects($this->once())
                ->method('GetSchedule')
                ->with($this->equalTo($publicId))
                ->willReturn($schedule);

        $this->StubSubscriptionKey();

        $isValid = $this->validator->IsValid();

        $this->assertFalse($isValid);
    }

    public function testIsNotValidWhenTurnedOffForUser()
    {
        $user = new FakeUser();
        $user->DisableSubscription();

        $publicId = uniqid();

        $this->page->expects($this->once())
                ->method('GetUserId')
                ->willReturn($publicId);

        $this->subscriptionService->expects($this->once())
                ->method('GetUser')
                ->with($this->equalTo($publicId))
                ->willReturn($user);

        $this->StubSubscriptionKey();

        $isValid = $this->validator->IsValid();

        $this->assertFalse($isValid);
    }

    public function testIsNotValidWhenSubscriptionKeyDoesNotMatch()
    {
        $this->page->expects($this->once())
            ->method('GetSubscriptionKey')
            ->willReturn('12');

        $this->fakeConfig->SetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY, '123');

        $isValid = $this->validator->IsValid();

        $this->assertFalse($isValid);
    }

    public function testIsNotValidWhenSubscriptionKeyIsNotConfigured()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY, '');

        $isValid = $this->validator->IsValid();

        $this->assertFalse($isValid);
    }

    private function StubSubscriptionKey()
    {
        $this->page->expects($this->once())
            ->method('GetSubscriptionKey')
            ->willReturn('123');

        $this->fakeConfig->SetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY, '123');
    }
}
