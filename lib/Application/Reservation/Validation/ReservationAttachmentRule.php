<?php

class ReservationAttachmentRule implements IReservationValidationRule
{
    /**
     * @param ReservationSeries $reservationSeries
     * @param $retryParameters
     * @return ReservationRuleResult
     */
    public function Validate($reservationSeries, $retryParameters)
    {
        $attachments = $reservationSeries->AddedAttachments();

        $allowedExtensionsConfig = Configuration::Instance()->GetKey(ConfigKeys::UPLOAD_RESERVATION_ATTACHMENT_EXTENSIONS);

        if (empty($allowedExtensionsConfig) || empty($attachments)) {
            return new ReservationRuleResult();
        }

        $allowedExtensions = str_replace('.', '', $allowedExtensionsConfig);
        $allowedExtensions = str_replace(' ', '', $allowedExtensions);
        $allowedExtensionList = explode(',', $allowedExtensions);

        foreach ($attachments as $attachment) {
            $isValid = in_array($attachment->FileExtension(), $allowedExtensionList);
            if (!$isValid) {
                return new ReservationRuleResult($isValid, Resources::GetInstance()->GetString('InvalidAttachmentExtension', $allowedExtensionsConfig));
            }
        }

        return new ReservationRuleResult();
    }
}
