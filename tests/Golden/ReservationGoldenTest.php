<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Reservation templates:
 *   - tpl/Reservation/private-participation.tpl  → .twig (trivial comment)
 *   - tpl/Reservation/pdf_libraries.tpl          → .twig
 *   - tpl/Reservation/pdf.tpl                    → .twig
 *   - tpl/Reservation/attachment-error.tpl       → .twig
 *   - tpl/Reservation/participation.tpl          → .twig
 *   - tpl/Reservation/invitees.tpl               → .twig
 *   - tpl/Reservation/collect-guest.tpl          → .twig
 *   - tpl/Reservation/view.tpl                   → .twig (structural)
 *   - tpl/Reservation/create.tpl                 → .twig (structural)
 *   - tpl/Reservation/edit.tpl                   → .twig (structural)
 *   - tpl/Reservation/approve.tpl                → .twig (structural)
 *   - tpl/Reservation/availability.twig          → structural
 *
 * Parity strategy
 * ---------------
 * Simple partials (private-participation, pdf_libraries, pdf, participation,
 * invitees, attachment-error): full assertParity — both engines must produce
 * normalized-identical output.
 *
 * collect-guest: assertParity with $_SERVER['REQUEST_URI'] pinned.
 *
 * Complex full-page templates (view, create, edit, approve, availability):
 * structural assertion — Twig renders without exception and key strings appear
 * in the output. Full parity requires RecurrenceControl, DatePickerSetupControl
 * and many domain objects that are intentionally deferred.
 *
 * Divergences from Smarty source noted inline.
 */
class ReservationGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';
        $_SERVER['REQUEST_URI'] = '/web/reservation.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        $fakeServer->UserSession->UserId = 42;
        ServiceLocator::SetServer($fakeServer);
        Date::_SetNow(Date::Parse('2025-06-15 10:00:00', 'UTC'));
    }

    protected function tearDown(): void
    {
        $prop = new \ReflectionProperty(Date::class, '_Now');
        $prop->setAccessible(true);
        $prop->setValue(null, null);

        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);
        parent::tearDown();
    }

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = $smarty->render($tplName);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = $twig->render($twigName);

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    /**
     * Render Twig only and assert the output contains expected strings.
     *
     * @param array<string, mixed> $vars
     * @param string[]             $expectedStrings
     */
    private function assertTwigContains(string $twigName, array $vars, array $expectedStrings): void
    {
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $output = $twig->render($twigName);
        foreach ($expectedStrings as $needle) {
            $this->assertStringContainsString($needle, $output, "Expected '$needle' in $twigName output");
        }
    }

    // ── Simple partials — full parity ─────────────────────────────────────────

    public function testPrivateParticipationMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/private-participation.tpl',
            'Reservation/private-participation.twig',
            []
        );
    }

    public function testPdfLibrariesMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/pdf_libraries.tpl',
            'Reservation/pdf_libraries.twig',
            []
        );
    }

    public function testPdfMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/pdf.tpl',
            'Reservation/pdf.twig',
            ['ReservationPdfConfigJson' => '{"key":"value"}']
        );
    }

    public function testAttachmentErrorMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/attachment-error.tpl',
            'Reservation/attachment-error.twig',
            []
        );
    }

    public function testParticipationMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/participation.tpl',
            'Reservation/participation.twig',
            ['AllowParticipantsToJoin' => false, 'AllowGuestParticipation' => false]
        );
    }

    public function testParticipationWithJoinMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/participation.tpl',
            'Reservation/participation.twig',
            ['AllowParticipantsToJoin' => true, 'AllowGuestParticipation' => false]
        );
    }

    public function testInviteesMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/invitees.tpl',
            'Reservation/invitees.twig',
            ['AllowParticipantsToJoin' => false, 'AllowGuestParticipation' => false]
        );
    }

    public function testInviteesWithGuestMatchesSmarty(): void
    {
        $this->assertParity(
            'Reservation/invitees.tpl',
            'Reservation/invitees.twig',
            ['AllowParticipantsToJoin' => true, 'AllowGuestParticipation' => true]
        );
    }

    public function testCollectGuestMatchesSmarty(): void
    {
        $_SERVER['REQUEST_URI'] = '/web/guest.php';
        $this->assertParity(
            'Reservation/collect-guest.tpl',
            'Reservation/collect-guest.twig',
            []
        );
    }

    // ── Structural tests for complex full-page templates ─────────────────────

    /**
     * Build a minimal fake Date-like period object.
     */
    private function makePeriod(string $begin, string $end, bool $reservable = true): object
    {
        return new class ($begin, $end, $reservable) {
            public function __construct(
                private string $begin,
                private string $end,
                private bool $reservable
            ) {
            }
            public function Begin(): string
            {
                return $this->begin;
            }
            public function End(): string
            {
                return $this->end;
            }
            public function IsReservable(): bool
            {
                return $this->reservable;
            }
            public function Label(mixed $date = null): string
            {
                return $this->begin;
            }
            public function LabelEnd(mixed $date = null): string
            {
                return $this->end;
            }
            public function Span(): int
            {
                return 1;
            }
        };
    }

    /**
     * Build a minimal fake resource object for view/create templates.
     */
    private function makeResource(int $id, string $name): object
    {
        return new class ($id, $name) {
            public int $Id;
            public string $Name;
            public function __construct(int $id, string $name)
            {
                $this->Id = $id;
                $this->Name = $name;
            }
            public function GetId(): int
            {
                return $this->Id;
            }
            public function GetName(): string
            {
                return $this->Name;
            }
            public function GetColor(): string
            {
                return '';
            }
            public function GetTextColor(): string
            {
                return '';
            }
            public function GetRequiresApproval(): bool
            {
                return false;
            }
            public function IsCheckInEnabled(): bool
            {
                return false;
            }
            public function IsAutoReleased(): bool
            {
                return false;
            }
            public function GetAutoReleaseMinutes(): int
            {
                return 0;
            }
        };
    }

    /**
     * Build the minimal vars needed for view.twig to render without error.
     *
     * @return array<string, mixed>
     */
    private function makeViewVars(): array
    {
        $startDate = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $endDate = Date::Parse('2025-06-15 11:00:00', 'UTC');
        $selectedPeriod = $this->makePeriod('10:00', '11:00');
        $resource = $this->makeResource(1, 'Test Resource');

        return [
            'ResourceId' => 1,
            'CanViewResourceReservations' => [1],
            'AdditionalResourceIds' => [],
            'UserId' => 42,
            'IAmParticipating' => false,
            'IAmInvited' => false,
            'ShowParticipation' => false,
            'AllowParticipation' => false,
            'ShowReservationDetails' => true,
            'ShowUserDetails' => true,
            'ReservationUserName' => 'Test User',
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'StartPeriods' => [$selectedPeriod],
            'EndPeriods' => [$selectedPeriod],
            'SelectedStart' => $selectedPeriod,
            'SelectedEnd' => $selectedPeriod,
            'RepeatType' => 'none',
            'RepeatOptions' => ['none' => ['key' => 'DoesNotRepeat', 'everyKey' => '']],
            'IsRecurring' => false,
            'RepeatWeekdays' => [],
            'RepeatMonthlyType' => '',
            'RepeatInterval' => 1,
            'RepeatTerminationDate' => $endDate,
            'DayNames' => [],
            'ResourceName' => 'Test Resource',
            'AvailableResources' => [],
            'Accessories' => [],
            'ReservationTitle' => 'Test Title',
            'Description' => 'Test Description',
            'ReferenceNumber' => 'ABC123',
            'Attachments' => [],
            'Participants' => [],
            'Invitees' => [],
            'ReturnUrl' => '/calendar',
            'Path' => '/',
            'ReservationId' => 1,
            'ReservationAction' => 'view',
            'CheckInRequired' => false,
            'CheckOutRequired' => false,
            'CanJoinWaitList' => false,
            'AllowParticipantsToJoin' => false,
            'CanAlterParticipation' => false,
            'AutoReleaseMinutes' => null,
            'Timezone' => 'UTC',
            'Resource' => $resource,
            'CanViewAdmin' => false,
            'checkinAdminOnly' => false,
            'checkoutAdminOnly' => false,
            'ReservationPdfConfigJson' => '{}',
            'SCRIPT_NAME' => '/web/reservation.php',
        ];
    }

    public function testViewTwigRendersWithoutError(): void
    {
        $vars = $this->makeViewVars();
        $this->assertTwigContains(
            'Reservation/view.twig',
            $vars,
            ['page-view-reservation', 'Test User', 'Test Title', 'ABC123']
        );
    }

    public function testViewTwigWithPermissionDenied(): void
    {
        $vars = $this->makeViewVars();
        $vars['CanViewResourceReservations'] = [];
        $vars['UserId'] = 99; // different from FakeServer UserId=42
        $this->assertTwigContains(
            'Reservation/view.twig',
            $vars,
            ['page-view-reservation', "don't have permissions"]
        );
    }

    public function testApproveTwigRendersWithoutError(): void
    {
        $vars = $this->makeViewVars();
        $vars['IsRecurring'] = false;
        $this->assertTwigContains(
            'Reservation/approve.twig',
            $vars,
            ['btnApprove', 'Approve', 'btnApprovalUpdate']
        );
    }

    /**
     * Build minimal vars for create.twig structural rendering.
     *
     * @return array<string, mixed>
     */
    private function makeCreateVars(): array
    {
        $startDate = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $endDate = Date::Parse('2025-06-15 11:00:00', 'UTC');
        $period = $this->makePeriod('10:00', '11:00');
        $resource = $this->makeResource(1, 'Test Resource');

        return [
            'TitleRequired' => false,
            'DescriptionRequired' => false,
            'ShowParticipation' => false,
            'AllowParticipation' => false,
            'ShowReservationDetails' => true,
            'ShowUserDetails' => true,
            'ShowAdditionalResources' => false,
            'UserId' => 42,
            'ReservationUserName' => 'Test User',
            'CanChangeUser' => false,
            'CreditsEnabled' => false,
            'CurrentUserCredits' => 0,
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'StartPeriods' => [$period],
            'EndPeriods' => [$period],
            'SelectedStart' => $period,
            'SelectedEnd' => $period,
            'LockPeriods' => false,
            'HideRecurrence' => false,
            'RepeatTerminationDate' => $endDate,
            'AvailabilityStart' => $startDate,
            'AvailabilityEnd' => $endDate,
            'FirstWeekday' => 0,
            'ScheduleId' => 1,
            'ResourceId' => 1,
            'Resource' => $resource,
            'AvailableResources' => [],
            'AdditionalResourceIds' => [],
            'AvailableAccessories' => [],
            'Accessories' => [],
            'RemindersEnabled' => false,
            'UploadsEnabled' => false,
            'Terms' => null,
            'TermsAccepted' => false,
            'Description' => '',
            'ReservationId' => 0,
            'ReferenceNumber' => '',
            'ReservationAction' => 'create',
            'ReturnUrl' => '/schedule',
            'RepeatType' => 'none',
            'RepeatInterval' => 1,
            'RepeatMonthlyType' => '',
            'RepeatWeekdays' => [],
            'Participants' => [],
            'Invitees' => [],
            'ParticipatingGuests' => [],
            'InvitedGuests' => [],
            'CustomRepeatDates' => [],
            'Timezone' => 'UTC',
            'MaximumResources' => 0,
            'MaxUploadCount' => 1,
            'MaxUploadSize' => 10,
            'ResourceGroupsAsJson' => '[]',
            'ReminderTimeStart' => 15,
            'ReminderTimeEnd' => 15,
            'ReminderIntervalStart' => 'minutes',
            'ReminderIntervalEnd' => 'minutes',
            'ReservationPdfConfigJson' => '{}',
            'ReservationTitle' => '',
            'EmailEnabled' => false,
            'RequiresApproval' => false,
            'IsRecurring' => false,
            'CheckInRequired' => false,
            'CheckOutRequired' => false,
            'AutoReleaseMinutes' => null,
            'CanViewAdmin' => false,
            'checkinAdminOnly' => false,
            'checkoutAdminOnly' => false,
        ];
    }

    public function testCreateTwigRendersWithoutError(): void
    {
        $vars = $this->makeCreateVars();
        $this->assertTwigContains(
            'Reservation/create.twig',
            $vars,
            ['page-reservation', 'form-reservation', 'btnCreate']
        );
    }

    public function testEditTwigRendersWithoutError(): void
    {
        $vars = $this->makeCreateVars();
        $vars['ReferenceNumber'] = 'EDIT123';
        $vars['ReservationId'] = 5;
        $vars['ReservationTitle'] = 'Edit Title';
        $vars['Attachments'] = [];
        $this->assertTwigContains(
            'Reservation/edit.twig',
            $vars,
            ['page-reservation', 'form-reservation', 'btnEdit', 'EDIT123']
        );
    }

    public function testAvailabilityTwigRendersWithoutError(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');

        $period = new class () {
            public function Span(): int
            {
                return 1;
            }
            public function Label(mixed $d = null): string
            {
                return 'Morning';
            }
            public function Begin(): object
            {
                return new class () {
                    public function Hour(): int
                    {
                        return 9;
                    }
                    public function Minute(): int
                    {
                        return 0;
                    }
                };
            }
            public function End(): object
            {
                return new class () {
                    public function Hour(): int
                    {
                        return 10;
                    }
                    public function Minute(): int
                    {
                        return 0;
                    }
                };
            }
        };

        $slot = new class () {
            public function PeriodSpan(): int
            {
                return 1;
            }
        };

        $resource = new class () {
            public function GetId(): int
            {
                return 1;
            }
            public function GetName(): string
            {
                return 'Room A';
            }
        };

        $user = new class () {
            public int $UserId = 42;
            public string $FullName = 'Test User';
        };

        $layout = new class ($period, $slot) {
            public function __construct(private object $period, private object $slot)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date): array
            {
                return [$this->period];
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [$this->slot];
            }
        };

        $factory = new class () {
            public function GetFunction(mixed $slot, bool $flag = true): string
            {
                return 'displayReservable';
            }
        };

        $vars = [
            'BoundDates' => [$date],
            'DailyLayout' => $layout,
            'Resources' => [$resource],
            'User' => $user,
            'Participants' => [],
            'Invitees' => [],
            'DisplaySlotFactory' => $factory,
        ];

        $this->assertTwigContains(
            'Reservation/availability.twig',
            $vars,
            ['reservations-2025-06-15', 'Room A', 'btnHideAvailability', 'reservable slot']
        );
    }
}
