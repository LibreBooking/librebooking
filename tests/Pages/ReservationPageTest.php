<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Reservation/ReservationPage.php');

class ReservationPageTest extends TestBase
{
    public function setUp(): void
    {
        parent::setUp();

        $resources = new ReservationPagePdfConfigResources();
        Resources::SetInstance($resources);
    }

    public function testBuildsScriptSafeReservationPdfConfigJson(): void
    {
        $page = new TestableReservationPage();
        $page->Set('AppTitle', 'Libre <Booking> & "PDF"');
        $page->Set('ReferenceNumber', 'rn-1');
        // Accented strings exercise Unicode behavior for HTML decoding and JSON encoding.
        $page->Set('ReservationUserName', 'Ren&eacute;e O&#039;Connor');
        $page->Set('ReservationTitle', 'Title &lt;/script&gt; x' . "\u{2028}" . 'y' . "\u{2029}" . 'z');
        $page->Set('Description', 'Description &amp; details');
        $page->Set('Resource', new ReservationPagePdfConfigResource());
        $page->Set('Terms', new stdClass());
        $page->Set('TermsAccepted', true);
        $page->Set('RepeatOptions', [
            RepeatType::None => ['key' => 'DoesNotRepeat', 'everyKey' => ''],
        ]);
        $page->Set('RepeatType', RepeatType::None);
        $page->Set('StartDate', Date::Create(2026, 4, 8, 14, 30, 0, 'America/Chicago'));
        $page->Set('EndDate', Date::Create(2026, 4, 8, 15, 30, 0, 'America/Chicago'));

        $json = $page->BuildReservationPdfConfigJson();
        $config = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $this->assertStringNotContainsString('</script>', $json);
        $this->assertStringContainsString('\u003C/script\u003E', $json);
        $this->assertStringContainsString('\u2028', $json);
        $this->assertStringContainsString('\u2029', $json);
        $this->assertStringNotContainsString("\u{2028}", $json);
        $this->assertStringNotContainsString("\u{2029}", $json);

        $this->assertSame('Renée O\'Connor', $config['reservationUserName']);
        $this->assertSame('Title </script> x' . "\u{2028}" . 'y' . "\u{2029}" . 'z', $config['reservationTitle']);
        $this->assertSame('Description & details', $config['reservationDescription']);
        $this->assertTrue($config['showTermsAcceptance']);
        $this->assertSame(CustomAttributeTypes::CHECKBOX, $config['customAttributeTypeCheckbox']);
    }

    public function testReservationPdfConfigKeepsSmartyFormattingBehavior(): void
    {
        $page = new TestableReservationPage();
        $page->Set('Resource', new ReservationPagePdfConfigResource());
        $page->Set('RepeatOptions', [
            RepeatType::Weekly => ['key' => 'Weekly', 'everyKey' => 'weeks'],
        ]);
        $page->Set('RepeatType', RepeatType::Weekly);
        $page->Set('IsRecurring', true);
        $page->Set('RepeatWeekdays', [3]);
        $page->Set('StartDate', Date::Create(2026, 4, 8, 14, 30, 0, 'America/Chicago'));
        $page->Set('EndDate', Date::Create(2026, 4, 8, 15, 30, 0, 'America/Chicago'));
        $page->Set('RepeatTerminationDate', Date::Create(2026, 4, 29, 15, 30, 0, 'America/Chicago'));

        $config = json_decode($page->BuildReservationPdfConfigJson(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('Réservation Spéciale', $config['reservationDetailsTitle']);
        $this->assertSame('Días Repetidos', $config['daysLabel']);
        $this->assertSame('Miércoles 2026-04-08 14:30', $config['beginDateValue']);
        $this->assertSame(['Miércoles'], $config['repeatWeekdays']);
        $this->assertSame('semanal', $config['repeatTypeLabel']);
        $this->assertSame('semanas', $config['repeatEveryLabel']);
    }

    public function testReservationPdfConfigIncludesExpectedResourceData(): void
    {
        $page = new TestableReservationPage();
        $page->Set('Resource', new ReservationPagePdfConfigResource('Main Room', true, true, true, 15));
        $page->Set('AdditionalResourceIds', [2]);
        $page->Set('AvailableResources', [
            new ReservationPagePdfConfigResource('Ignored Room', false, false, false, null, 1),
            new ReservationPagePdfConfigResource('Overflow Room', false, true, false, null, 2),
        ]);
        $page->Set('RepeatOptions', [
            RepeatType::None => ['key' => 'DoesNotRepeat', 'everyKey' => ''],
        ]);
        $page->Set('RepeatType', RepeatType::None);

        $config = json_decode($page->BuildReservationPdfConfigJson(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(2, $config['resources']);
        $this->assertSame([
            'name' => 'Main Room',
            'requiresApproval' => true,
            'requiresCheckIn' => true,
            'releasedIn' => '15',
        ], $config['resources'][0]);
        $this->assertSame([
            'name' => 'Overflow Room',
            'requiresApproval' => false,
            'requiresCheckIn' => true,
            'releasedIn' => ' - ',
        ], $config['resources'][1]);
    }
}

class TestableReservationPage extends ReservationPage
{
    public function BuildReservationPdfConfigJson(): string
    {
        $method = new ReflectionMethod(ReservationPage::class, 'SetReservationPdfConfig');
        $method->invoke($this);

        return (string)$this->smarty->getTemplateVars('ReservationPdfConfigJson');
    }

    protected function GetPresenter()
    {
        return new class () implements IReservationPresenter {
            public function PageLoad()
            {
            }
        };
    }

    protected function GetTemplateName()
    {
        return 'Reservation/pdf.tpl';
    }

    protected function GetReservationAction()
    {
        return '';
    }

    public function SetTermsAccepted($accepted)
    {
        $this->Set('TermsAccepted', $accepted);
    }
}

class ReservationPagePdfConfigResources extends FakeResources
{
    // Accented strings exercise Unicode behavior for Smarty capitalization and localized day names.
    /**
     * @var array<string, string>
     */
    private array $strings = [
        'DoesNotRepeat' => 'no se repite',
        'ReservationDetails' => 'réservation spéciale',
        'RepeatDaysPrompt' => 'días repetidos',
        'Weekly' => 'semanal',
        'weeks' => 'semanas',
    ];

    public function GetString($key, $args = []): string
    {
        return $this->strings[$key] ?? parent::GetString($key, $args);
    }

    public function GetDateFormat($key)
    {
        if ($key === 'dashboard') {
            return 'l Y-m-d H:i';
        }

        if ($key === 'schedule_daily') {
            return 'l Y-m-d';
        }

        return parent::GetDateFormat($key);
    }

    public function GetDays($key)
    {
        return ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    }
}

class ReservationPagePdfConfigResource implements IBookableResource
{
    public function __construct(
        private string $name = 'Conference Room',
        private bool $requiresApproval = false,
        private bool $checkInEnabled = false,
        private bool $autoReleased = false,
        private ?int $autoReleaseMinutes = null,
        public int $Id = 1
    ) {
    }

    public function GetId()
    {
        return $this->Id;
    }

    public function GetName()
    {
        return $this->name;
    }

    public function GetResourceId()
    {
        return $this->Id;
    }

    public function GetAdminGroupId()
    {
        return 0;
    }

    public function GetScheduleId()
    {
        return 0;
    }

    public function GetScheduleAdminGroupId()
    {
        return 0;
    }

    public function GetStatusId()
    {
        return 0;
    }

    public function GetMinimumLength()
    {
        return TimeInterval::None();
    }

    public function GetRequiresApproval()
    {
        return $this->requiresApproval;
    }

    public function IsCheckInEnabled()
    {
        return $this->checkInEnabled;
    }

    public function IsAutoReleased()
    {
        return $this->autoReleased;
    }

    public function GetAutoReleaseMinutes()
    {
        return $this->autoReleaseMinutes;
    }

    public function GetResourceTypeId()
    {
        return 0;
    }

    public function GetColor()
    {
        return null;
    }

    public function GetTextColor()
    {
        return null;
    }
}
