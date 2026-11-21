<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
require_once(__DIR__ . '/../../Pages/Ajax/ReservationPopupPage.php');

/**
 * Live Smarty-vs-Twig golden comparison for Ajax templates:
 *   - tpl/Ajax/reservation/checkin_failed.tpl        → .twig
 *   - tpl/Ajax/reservation/checkin_successful.tpl    → .twig
 *   - tpl/Ajax/reservation/delete_failed.tpl         → .twig
 *   - tpl/Ajax/reservation/delete_successful.tpl     → .twig
 *   - tpl/Ajax/reservation/reservation_error.tpl     → .twig
 *   - tpl/Ajax/reservation/save_failed.tpl           → .twig
 *   - tpl/Ajax/reservation/save_successful.tpl       → .twig
 *   - tpl/Ajax/reservation/update_successful.tpl     → .twig
 *   - tpl/Ajax/reservation/waitlist_added.tpl        → .twig
 *   - tpl/Ajax/reservation/reservation_attributes.tpl → .twig
 *   - tpl/Ajax/reservation/reservation_attributes_print.tpl → .twig
 *   - tpl/Ajax/reservation_popup.tpl                 → .twig (structural)
 *   - tpl/Ajax/user_popup.tpl                        → .twig (structural)
 *
 * Parity strategy
 * ---------------
 * Simple fragments (checkin_*, delete_*, reservation_error, waitlist_added,
 * save_failed without retry, save_successful, update_successful,
 * reservation_attributes, reservation_attributes_print):
 * full assertParity — both engines must produce normalized-identical output.
 *
 * save_failed with retry: structural assertTwigContains because the retry
 * section uses constant('FormKeys::RESERVATION_RETRY_PREFIX') which Smarty
 * renders via {FormKeys::RESERVATION_RETRY_PREFIX} class-constant syntax.
 *
 * reservation_popup and user_popup: structural assertTwigContains because
 * they involve PopupFormatter and User objects with complex interactions.
 */
class AjaxGoldenTest extends GoldenTemplateTestCase
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

    // ── checkin_failed ────────────────────────────────────────────────────────

    public function testCheckinFailedCheckingInMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/checkin_failed.tpl',
            'Ajax/reservation/checkin_failed.twig',
            ['IsCheckingIn' => true, 'Errors' => []]
        );
    }

    public function testCheckinFailedCheckingOutWithErrorsMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/checkin_failed.tpl',
            'Ajax/reservation/checkin_failed.twig',
            ['IsCheckingIn' => false, 'Errors' => ['Error one', 'Error two']]
        );
    }

    // ── checkin_successful ────────────────────────────────────────────────────

    public function testCheckinSuccessfulCheckingInMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/checkin_successful.tpl',
            'Ajax/reservation/checkin_successful.twig',
            ['IsCheckingIn' => true]
        );
    }

    public function testCheckinSuccessfulCheckingOutMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/checkin_successful.tpl',
            'Ajax/reservation/checkin_successful.twig',
            ['IsCheckingIn' => false]
        );
    }

    // ── delete_failed ─────────────────────────────────────────────────────────

    public function testDeleteFailedEmptyErrorsMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/delete_failed.tpl',
            'Ajax/reservation/delete_failed.twig',
            ['Errors' => []]
        );
    }

    public function testDeleteFailedWithErrorsMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/delete_failed.tpl',
            'Ajax/reservation/delete_failed.twig',
            ['Errors' => ['Cannot delete', 'Still in use']]
        );
    }

    // ── delete_successful ─────────────────────────────────────────────────────

    public function testDeleteSuccessfulMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/delete_successful.tpl',
            'Ajax/reservation/delete_successful.twig',
            []
        );
    }

    // ── reservation_error ─────────────────────────────────────────────────────

    public function testReservationErrorMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/reservation_error.tpl',
            'Ajax/reservation/reservation_error.twig',
            []
        );
    }

    // ── waitlist_added ────────────────────────────────────────────────────────

    public function testWaitlistAddedMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/waitlist_added.tpl',
            'Ajax/reservation/waitlist_added.twig',
            []
        );
    }

    // ── save_failed ───────────────────────────────────────────────────────────

    public function testSaveFailedPlainMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/save_failed.tpl',
            'Ajax/reservation/save_failed.twig',
            ['Errors' => ['Overlap conflict'], 'CanJoinWaitList' => false, 'CanBeRetried' => false]
        );
    }

    public function testSaveFailedWithWaitlistMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/save_failed.tpl',
            'Ajax/reservation/save_failed.twig',
            ['Errors' => ['Slot taken'], 'CanJoinWaitList' => true, 'CanBeRetried' => false]
        );
    }

    public function testSaveFailedWithRetryRendersCorrectly(): void
    {
        $retryParam = new class () {
            public function Name(): string
            {
                return 'skip_conflict';
            }

            public function Value(): string
            {
                return '1';
            }
        };
        $this->assertTwigContains(
            'Ajax/reservation/save_failed.twig',
            [
                'Errors' => ['Cannot save'],
                'CanJoinWaitList' => false,
                'CanBeRetried' => true,
                'RetryParameters' => [$retryParam],
                'RetryMessages' => ['Skipped 2 conflicts'],
            ],
            ['btnRetry', 'skip_conflict', 'reservation-failed', 'Skipped 2 conflicts']
        );
    }

    // ── save_successful ───────────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeSaveVars(bool $requiresApproval = false): array
    {
        $instance = new class () {
            public function StartDate(): Date
            {
                return Date::Parse('2025-06-15 10:00:00', 'UTC');
            }
        };
        $resource = new class () {
            public function GetName(): string
            {
                return 'Room A';
            }
        };
        return [
            'divId' => 'reservation-created',
            'messageKey' => 'ReservationCreated',
            'RequiresApproval' => $requiresApproval,
            'Instances' => [$instance],
            'Resources' => [$resource],
            'ReferenceNumber' => 'REF-001',
            'Timezone' => 'UTC',
        ];
    }

    public function testSaveSuccessfulNoApprovalMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/save_successful.tpl',
            'Ajax/reservation/save_successful.twig',
            $this->makeSaveVars()
        );
    }

    public function testSaveSuccessfulRequiresApprovalMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/save_successful.tpl',
            'Ajax/reservation/save_successful.twig',
            $this->makeSaveVars(true)
        );
    }

    // ── update_successful ─────────────────────────────────────────────────────

    public function testUpdateSuccessfulMatchesSmarty(): void
    {
        $instance = new class () {
            public function StartDate(): Date
            {
                return Date::Parse('2025-06-15 10:00:00', 'UTC');
            }
        };
        $resource = new class () {
            public function GetName(): string
            {
                return 'Room A';
            }
        };
        // No divId/messageKey in outer vars — they come from the include with-clause.
        $vars = [
            'RequiresApproval' => false,
            'Instances' => [$instance],
            'Resources' => [$resource],
            'ReferenceNumber' => 'REF-001',
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Ajax/reservation/update_successful.tpl',
            'Ajax/reservation/update_successful.twig',
            $vars
        );
    }

    // ── reservation_attributes ────────────────────────────────────────────────

    public function testReservationAttributesEmptyMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/reservation_attributes.tpl',
            'Ajax/reservation/reservation_attributes.twig',
            ['Attributes' => [], 'ReadOnly' => false]
        );
    }

    public function testReservationAttributesNotDefinedMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/reservation_attributes.tpl',
            'Ajax/reservation/reservation_attributes.twig',
            ['ReadOnly' => false]
        );
    }

    // ── reservation_attributes_print ─────────────────────────────────────────

    public function testReservationAttributesPrintEmptyMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation/reservation_attributes_print.tpl',
            'Ajax/reservation/reservation_attributes_print.twig',
            ['Attributes' => [], 'CustomAttributeTypeDateTime' => 5]
        );
    }

    public function testReservationAttributesPrintWithItemsMatchesSmarty(): void
    {
        $attr = new class () {
            public function Id(): int
            {
                return 10;
            }

            public function Type(): int
            {
                return 1; // SINGLE_LINE_TEXTBOX
            }

            public function Label(): string
            {
                return 'My Label';
            }

            public function Value(): string
            {
                return 'My Value';
            }
        };
        $this->assertParity(
            'Ajax/reservation/reservation_attributes_print.tpl',
            'Ajax/reservation/reservation_attributes_print.twig',
            ['Attributes' => [$attr], 'CustomAttributeTypeDateTime' => 5]
        );
    }

    // ── reservation_popup ─────────────────────────────────────────────────────

    public function testReservationPopupUnauthorizedMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/reservation_popup.tpl',
            'Ajax/reservation_popup.twig',
            ['authorized' => false]
        );
    }

    public function testReservationPopupAuthorizedRendersWithoutError(): void
    {
        $resource = new class () {
            public function Id(): int
            {
                return 1;
            }

            public function Name(): string
            {
                return 'Room A';
            }
        };
        $participant = new class () {
            /** @var string */
            public string $FirstName = 'John';

            /** @var string */
            public string $LastName = 'Doe';

            public function IsOwner(): bool
            {
                return false;
            }
        };
        $accessory = new class () {
            /** @var string */
            public string $Name = 'Projector';

            /** @var int */
            public int $QuantityReserved = 1;
        };
        $formatter = new PopupFormatter();
        $startDate = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $endDate = Date::Parse('2025-06-15 11:00:00', 'UTC');

        $this->assertTwigContains(
            'Ajax/reservation_popup.twig',
            [
                'authorized' => true,
                'resources' => [$resource],
                'CanViewResourceReservations' => [1],
                'UserId' => 42,
                'OwnerId' => 42,
                'IAmParticipating' => false,
                'IAmInvited' => false,
                'hideUserInfo' => false,
                'hideDetails' => false,
                'fullName' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '555-1234',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'title' => 'My Reservation',
                'participants' => [$participant],
                'accessories' => [$accessory],
                'summary' => 'A brief description',
                'attributes' => [],
                'requiresApproval' => false,
                'duration' => '1 hour',
                'ReservationId' => 99,
                'formatter' => $formatter,
            ],
            ['res_popup_details', 'Room A', 'My Reservation', 'test@example.com']
        );
    }

    public function testReservationPopupWithNoPermissionsRendersPrivate(): void
    {
        $resource = new class () {
            public function Id(): int
            {
                return 1;
            }

            public function Name(): string
            {
                return 'Room A';
            }
        };
        $formatter = new PopupFormatter();
        $startDate = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $endDate = Date::Parse('2025-06-15 11:00:00', 'UTC');

        $this->assertTwigContains(
            'Ajax/reservation_popup.twig',
            [
                'authorized' => true,
                'resources' => [$resource],
                'CanViewResourceReservations' => [], // no permissions
                'UserId' => 99, // different user
                'OwnerId' => 1,
                'IAmParticipating' => false,
                'IAmInvited' => false,
                'hideUserInfo' => false,
                'hideDetails' => false,
                'fullName' => 'Hidden User',
                'email' => '',
                'phone' => '',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'title' => '',
                'participants' => [],
                'accessories' => [],
                'summary' => '',
                'attributes' => [],
                'requiresApproval' => false,
                'duration' => '1 hour',
                'ReservationId' => 99,
                'formatter' => $formatter,
            ],
            ['text-danger', 'res_popup_details', 'Room A']
        );
    }

    // ── user_popup ────────────────────────────────────────────────────────────

    public function testUserPopupCannotViewMatchesSmarty(): void
    {
        $this->assertParity(
            'Ajax/user_popup.tpl',
            'Ajax/user_popup.twig',
            ['CanViewUser' => false]
        );
    }

    public function testUserPopupWithFullDetailsRendersCorrectly(): void
    {
        $user = new class () {
            public function FirstName(): string
            {
                return 'John';
            }

            public function LastName(): string
            {
                return 'Doe';
            }

            public function EmailAddress(): string
            {
                return 'john@example.com';
            }

            public function GetAttribute(string $attr): string
            {
                return match ($attr) {
                    'phone' => '555-1234',
                    'organization' => 'ACME Corp',
                    'position' => 'Developer',
                    default => ''
                };
            }

            public function GetAttributeValue(int $id): string
            {
                return 'custom-val';
            }
        };
        $attr = new class () {
            public function Label(): string
            {
                return 'Custom Field';
            }

            public function Id(): int
            {
                return 1;
            }
        };
        $this->assertTwigContains(
            'Ajax/user_popup.twig',
            ['CanViewUser' => true, 'User' => $user, 'Attributes' => [$attr]],
            ['userDetailsPopup', 'john@example.com', '555-1234', 'ACME Corp', 'Developer', 'Custom Field']
        );
    }
}
