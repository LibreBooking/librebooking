<?php

class ReservationHandlerTest extends TestBase
{
    public function testHandlingReservationCreationDelegatesToServicesForValidationAndPersistenceAndNotification()
    {
        $persistenceService = $this->createMock('IReservationPersistenceService');
        $validationService = $this->createMock('IReservationValidationService');
        $notificationService = $this->createMock('IReservationNotificationService');
        $page = $this->createMock('IReservationSaveResultsView');
        $retryOptions = new FakeReservationRetryOptions();

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();
        $instance = new Reservation($series, NullDateRange::Instance());
        $series->WithCurrentInstance($instance);

        $validationResult = new ReservationValidationResult();

        $validationService->expects($this->once())
                          ->method('Validate')
                          ->with($this->equalTo($series))
                          ->willReturn($validationResult);

        $persistenceService->expects($this->once())
                           ->method('Persist')
                           ->with($this->equalTo($series));

        $notificationService->expects($this->once())
                            ->method('Notify')
                            ->with($this->equalTo($series));

        $page->expects($this->once())
             ->method('SetSaveSuccessfulMessage')
             ->with($this->equalTo(true));

        $page->expects($this->once())
             ->method('SetWarnings')
             ->with($this->equalTo($validationResult->GetWarnings()));


        $handler = new ReservationHandler($persistenceService, $validationService, $notificationService, $retryOptions);
        $handler->Handle($series, $page);

        $this->assertTrue($retryOptions->_AdjustReservationCalled);
    }

    public function testPreventsPersistenceAndNotificationAndShowsFailedMessageWhenValidationFails()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_ALLOW_WAITLIST, true);
        $persistenceService = $this->createMock('IReservationPersistenceService');
        $validationService = $this->createMock('IReservationValidationService');
        $notificationService = $this->createMock('IReservationNotificationService');
        $page = $this->createMock('IReservationSaveResultsView');

        $errorMessage1 = 'e1';
        $errorMessage2 = 'e2';
        $errors = [$errorMessage1, $errorMessage2];

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();

        $validationResult = new ReservationValidationResult(false, $errors, null, false, [], [], true);

        $validationService->expects($this->once())
                          ->method('Validate')
                          ->with($this->equalTo($series))
                          ->willReturn($validationResult);

        $persistenceService->expects($this->never())
                           ->method('Persist');

        $notificationService->expects($this->never())
                            ->method('Notify');

        $page->expects($this->once())
             ->method('SetSaveSuccessfulMessage')
             ->with($this->equalTo(false));

        $page->expects($this->once())
             ->method('SetErrors')
             ->with($this->equalTo($errors));

        $page->expects($this->once())
             ->method('SetCanJoinWaitList')
             ->with($this->equalTo(true));

        $handler = new ReservationHandler($persistenceService, $validationService, $notificationService, new FakeReservationRetryOptions());
        $handler->Handle($series, $page);
    }

    public function testPreventsPersistenceAndNotificationAndShowsRetryMessageIfCanBeRetried()
    {
        $persistenceService = $this->createMock('IReservationPersistenceService');
        $validationService = $this->createMock('IReservationValidationService');
        $notificationService = $this->createMock('IReservationNotificationService');
        $page = new FakeReservationSavePage();
        $page->retryParameters = [];

        $errorMessage1 = 'e1';
        $errorMessage2 = 'e2';
        $errors = [$errorMessage1, $errorMessage2];

        $retryMessages = ['m1', 'm2'];

        $retryParams = [new ReservationRetryParameter('name', 'value')];

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();

        $validationResult = new ReservationValidationResult(false, $errors, null, true, $retryParams, $retryMessages);

        $validationService->expects($this->once())
                          ->method('Validate')
                          ->with($this->equalTo($series), $this->equalTo($page->retryParameters))
                          ->willReturn($validationResult);

        $persistenceService->expects($this->never())
                           ->method('Persist');

        $notificationService->expects($this->never())
                            ->method('Notify');

        $handler = new ReservationHandler($persistenceService, $validationService, $notificationService, new FakeReservationRetryOptions());
        $handler->Handle($series, $page);

        $this->assertFalse($page->saveSuccessful);
        $this->assertEquals($errors, $page->errors);
        $this->assertTrue($page->canBeRetried);
        $this->assertEquals($retryParams, $page->retryParameters);
        $this->assertEquals($retryMessages, $page->retryMessages);
    }

    public function testCanJoinWaitListIfTurnedOnAndNotRecurringSeriesAndOnlyErrorIsAvailability()
    {
        $persistenceService = $this->createMock('IReservationPersistenceService');
        $validationService = $this->createMock('IReservationValidationService');
        $notificationService = $this->createMock('IReservationNotificationService');
        $page = $this->createMock('IReservationSaveResultsView');

        $errorMessage1 = 'e1';
        $errorMessage2 = 'e2';
        $errors = [$errorMessage1, $errorMessage2];

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();

        $validationResult = new ReservationValidationResult(false, $errors, null, false, [], [], true);

        $validationService->expects($this->once())
            ->method('Validate')
            ->with($this->equalTo($series))
            ->willReturn($validationResult);

        $persistenceService->expects($this->never())
            ->method('Persist');

        $notificationService->expects($this->never())
            ->method('Notify');

        $page->expects($this->once())
            ->method('SetSaveSuccessfulMessage')
            ->with($this->equalTo(false));

        $page->expects($this->once())
            ->method('SetErrors')
            ->with($this->equalTo($errors));

        $handler = new ReservationHandler($persistenceService, $validationService, $notificationService, new FakeReservationRetryOptions());
        $handler->Handle($series, $page);
    }
}

class FakeReservationRetryOptions implements IReservationRetryOptions
{
    /**
     * @var bool
     */
    public $_AdjustReservationCalled = false;

    public function AdjustReservation(ReservationSeries $series, $retryParameters)
    {
        $this->_AdjustReservationCalled = true;
    }
}
