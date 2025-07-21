<?php

require_once(ROOT_DIR . 'Pages/Export/CalendarExportPage.php');
require_once(ROOT_DIR . 'Presenters/CalendarExportPresenter.php');

class CalendarExportPresenterTest extends TestBase
{
    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $repo;

    /**
     * @var ICalendarExportPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var CalendarExportPresenter
     */
    private $presenter;

    /**
     * @var ICalendarExportValidator|PHPUnit\Framework\MockObject\MockObject
     */
    private $validator;

    /**
     * @var FakePrivacyFilter
     */
    private $privacyFilter;

    public function setUp(): void
    {
        parent::setup();

        $this->repo = $this->createMock('IReservationViewRepository');
        $this->page = $this->createMock('ICalendarExportPage');
        $this->validator = $this->createMock('ICalendarExportValidator');
        $this->privacyFilter = new FakePrivacyFilter();

        $this->presenter = new CalendarExportPresenter($this->page, $this->repo, $this->validator, $this->privacyFilter);
    }

    public function testLoadsReservationByReferenceNumber()
    {
        $referenceNumber = 'ref';
        $reservationResult = new ReservationView();

        $this->validator->expects($this->atLeastOnce())
                ->method('IsValid')
                ->willReturn(true);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->repo->expects($this->once())
                ->method('GetReservationForEditing')
                ->with($this->equalTo($referenceNumber))
                ->willReturn($reservationResult);

        $this->page->expects($this->once())
                ->method('SetReservations')
                ->with($this->arrayHasKey(0));

        $this->presenter->PageLoad($this->fakeUser);
    }

    public function testCannotSeeReservationDetailsIfConfiguredOff()
    {
        $referenceNumber = 'ref';
        $reservationResult = new ReservationView();

        $this->validator->expects($this->atLeastOnce())
                ->method('IsValid')
                ->willReturn(true);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->repo->expects($this->once())
                ->method('GetReservationForEditing')
                ->with($this->equalTo($referenceNumber))
                ->willReturn($reservationResult);

        $this->page->expects($this->once())
                ->method('SetReservations')
                ->with($this->arrayHasKey(0));

        $this->presenter->PageLoad($this->fakeUser);
    }

    public function testOrganizerIsOwnerIfCurrentUserIsNotOrganizer()
    {
        // this fixes a bug in outlook which prevents you from adding a meeting that you are the organizer of
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = $user->UserId + 1;
        $res->OwnerFirstName = "f";
        $res->OwnerLastName = "l";
        $res->OwnerEmailAddress = "e@m.com";

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);
        $this->assertEquals($res->OwnerEmailAddress, $reservationView->OrganizerEmail);
        $fullName = new FullName($res->OwnerFirstName, $res->OwnerLastName);
        $this->assertEquals($fullName->__toString(), $reservationView->Organizer);
    }

    public function testOrganizerIsDefaultedIfCurrentUserIsOrganizer()
    {
        // this fixes a bug in outlook which prevents you from adding a meeting that you are the organizer of
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = $user->UserId;
        $res->OwnerFirstName = "f";
        $res->OwnerLastName = "l";
        $res->OwnerEmailAddress = "e@m.com";

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);
        $this->assertEquals('e-noreply@m.com', $reservationView->OrganizerEmail);
        $fullName = new FullName($res->OwnerFirstName, $res->OwnerLastName);
        $this->assertEquals($fullName->__toString(), $reservationView->Organizer);
    }

    public function testViewHidesDetailsWhenNoAccess()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();

        $this->privacyFilter->_CanViewDetails = false;
        $this->privacyFilter->_CanViewUser = false;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->assertEquals($user, $this->privacyFilter->_LastViewDetailsUserSession);
        $this->assertEquals($user, $this->privacyFilter->_LastViewUserUserSession);

        $this->assertEquals($res, $this->privacyFilter->_LastViewDetailsReservation);
        $this->assertEquals($res, $this->privacyFilter->_LastViewUserReservation);

        $this->assertEquals('Private', $reservationView->Organizer);
        $this->assertEquals('Private', $reservationView->OrganizerEmail);
        $this->assertEquals('Private', $reservationView->Summary);
        $this->assertEquals('Private', $reservationView->Description);
    }
}
