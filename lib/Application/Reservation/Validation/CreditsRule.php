<?php

require_once(ROOT_DIR . 'Domain/Access/UserRepository.php');

class CreditsRule implements IReservationValidationRule
{
    /**
     * @var IUserRepository
     */
    private $userRepository;
    /**
     * @var UserSession
     */
    private $user;

    public function __construct(IUserRepository $userRepository, UserSession $user)
    {
        $this->userRepository = $userRepository;
        $this->user = $user;
    }

    public function Validate($reservationSeries, $retryParameters)
    {
        if (!Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter())) {
            return new ReservationRuleResult();
        }

        $user = $this->userRepository->LoadById($this->user->UserId);
        $userCredits = $user->GetCurrentCredits();

        $creditsConsumedByThisReservation = $reservationSeries->GetCreditsConsumed();
        $creditsRequired = $reservationSeries->GetCreditsRequired();

        Log::Debug(
            'Credits allocated to reservation=%s, Credits required=%s, Credits available=%s, ReservationSeriesId=%s, UserId=%s',
            $creditsConsumedByThisReservation,
            $creditsRequired,
            $userCredits,
            $reservationSeries->SeriesId(),
            $user->Id()
        );

        return new ReservationRuleResult(
            $creditsRequired <= $userCredits + $creditsConsumedByThisReservation,
            Resources::GetInstance()->GetString('CreditsRule', [$creditsRequired, $userCredits])
        );
    }
}
