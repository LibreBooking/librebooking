<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class CreditsRuleTest extends TestBase
{
    /**
     * @var FakeUserRepository
     */
    public $userRepository;

    /**
     * @var AccessoryAvailabilityRule
     */
    public $rule;

    public function setUp(): void
    {
        parent::setup();

        $this->userRepository = new FakeUserRepository();

        $this->rule = new CreditsRule($this->userRepository, $this->fakeUser);

        $this->fakeConfig->SetKey(ConfigKeys::CREDITS_ENABLED, 'true');
    }

    public function testRuleIsValidIfUserHasEnoughCredits()
    {
        $reservation = new TestReservationSeries();
        $reservation->WithCreditsRequired(10);

        $user = new FakeUser();
        $user->WithCredits(10);

        $this->userRepository->_User = $user;

        $result = $this->rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testRuleIsNotValidIfUserDoesNotHaveEnoughCredits()
    {
        $reservation = new TestReservationSeries();
        $reservation->WithCreditsRequired(10);

        $user = new FakeUser();
        $user->WithCredits(9);

        $result = $this->rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }
}
