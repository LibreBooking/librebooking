<?php

declare(strict_types=1);

namespace LibreBooking\Common\Templating;

/**
 * The template engines a page or email can be rendered with.
 *
 * The backing values are the options of the template.engine setting.
 */
enum TemplateEngine: string
{
    case SMARTY = 'smarty';
    case TWIG = 'twig';

    /**
     * The engine selected by the template.engine setting. Smarty is used when
     * the setting is missing or has an unknown value.
     */
    public static function configured(): self
    {
        $value = \Configuration::Instance()->GetKey(\ConfigKeys::TEMPLATE_ENGINE);

        return is_string($value) ? (self::tryFrom($value) ?? self::SMARTY) : self::SMARTY;
    }
}
