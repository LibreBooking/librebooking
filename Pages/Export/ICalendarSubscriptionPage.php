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
     * Signal that the current request should be aborted. Used by tryBasicAuth()
     * for its fail-closed gates and credential checks (404/401, set by the
     * caller before invoking this method). An invalid subscription key is
     * signaled separately, via a false return from the presenter's PageLoad().
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

    /**
     * Display name emitted as X-WR-CALNAME in the ICS body so calendar clients
     * (Thunderbird, Apple Calendar, etc.) label the feed in their UI.
     * Null means no X-WR-CALNAME line is written.
     */
    public function SetCalendarName(?string $name): void;
}
