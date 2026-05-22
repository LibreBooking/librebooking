<?php

interface ICalendarSubscriptionPage
{
    /**
     * @return string
     */
    public function GetSubscriptionKey();

    /**
     * @return string
     */
    public function GetUserId();

    /**
     * @param iCalendarReservationView[] $reservations
     */
    public function SetReservations($reservations);

    /**
     * Signal that the current request should be aborted. Implementations set a
     * flag that PageLoad() checks after each step; the HTTP status code (404 for
     * an invalid subscription key, 401 for a Basic Auth failure) is set by the
     * caller before invoking this method.
     */
    public function SetIsNotFound(): void;

    /**
     * @return string
     */
    public function GetScheduleId();

    /**
     * @return string
     */
    public function GetResourceId();

    /**
     * @return string
     */
    public function GetResourceGroupId();

    /**
     * @return int
     */
    public function GetAccessoryIds();

    /**
     * @return int
     */
    public function GetPastNumberOfDays();

    /**
     * @return int
     */
    public function GetFutureNumberOfDays();

    /**
     * Returns the per-request UserSession established by Basic Auth, or null when
     * the request is icskey-only / no Basic Auth occurred. The presenter uses this
     * as the active session when set, instead of consulting the server session —
     * the feed session is intentionally NOT persisted to $_SESSION.
     */
    public function GetFeedUserSession(): ?UserSession;
}
