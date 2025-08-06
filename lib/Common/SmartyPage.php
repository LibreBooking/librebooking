<?php


if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require_once ROOT_DIR . 'vendor/autoload.php';
}
require_once(ROOT_DIR . 'lib/Server/namespace.php');
require_once(ROOT_DIR . 'lib/Common/Validators/namespace.php');
require_once(ROOT_DIR . 'lib/Common/Converters/namespace.php');
require_once(ROOT_DIR . 'lib/Common/Helpers/namespace.php');
require_once(ROOT_DIR . 'lib/Common/SmartyControls/namespace.php');

use Smarty\Smarty;

class SmartyPage extends Smarty
{
    /**
     * @var PageValidators
     */
    public $Validators;

    /**
     * @var Resources
     */
    protected $Resources = null;

    /**
     * @var bool
     */
    private $IsValid = true;

    /**
     *
     * @param Resources $resources
     * @param string $RootPath
     */

    public $plugins_dir;

    public function __construct(
        protected ?Resources $resources = null,
        protected $RootPath = null
    ) {
        parent::__construct();

        $base = __DIR__ . '/../../';

        $this->debugging = isset($_GET['debug']);
        $this->AddTemplateDirectory($base . 'tpl');
        $this->compile_dir = $base . 'tpl_c';
        $this->config_dir = $base . 'configs';
        $this->cache_dir = $base . 'cache';
        $this->plugins_dir = $base . 'vendor/smarty/smarty/libs/plugins';
        //$this->error_reporting = E_ALL & ~E_NOTICE;
        $this->muteUndefinedOrNullWarnings();

        $cacheTemplates = Configuration::Instance()->GetKey(ConfigKeys::CACHE_TEMPLATES, new BooleanConverter());

        $this->caching = false;
        $this->compile_check = !$cacheTemplates;
        $this->force_compile = !$cacheTemplates;

        if (is_null($resources)) {
            $resources = Resources::GetInstance();
        }

        $this->Resources = &$resources;

        $this->AddTemplateDirectory($base . 'lang/' . $this->Resources->CurrentLanguage);

        $this->RegisterFunctions();
        $this->RegisterClasses();
    }

    public function AddTemplateDirectory($templateDirectory)
    {
        $this->addTemplateDir($templateDirectory);
    }

    /**
     * Fetches template in a specific language. A custom template file might override the default template.
     * In case the template is not available in the target language it will fall back to en_us.
     * @param string $templateName
     * @param string $languageCode Will be set to template var CurrentLanguage if null
     * @param bool $enforceCustomTemplate if true uses custom language from default language
     * if custom template of the target language is not available.
     * @return string
     */
    public function FetchLocalized($templateName, bool $enforceCustomTemplate, ?string $languageCode = null)
    {
        if ($languageCode == null) {
            $languageCode = $this->getTemplateVars('CurrentLanguage');
        }
        $langPath = ROOT_DIR . 'lang/';
        $localizedPath = $langPath . $languageCode;
        $customTemplateName = str_replace('.tpl', '-custom.tpl', $templateName);
        $hasCustomTemplate = file_exists($localizedPath . '/' . $customTemplateName);

        if ($enforceCustomTemplate && !$hasCustomTemplate) {
            $defaultLanguageCode = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE);
            $defaultLocalizedPath = $langPath . $defaultLanguageCode;
            $hasCustomDefaultTemplate = file_exists($defaultLocalizedPath . '/' . $customTemplateName);
            if ($languageCode != $defaultLanguageCode && $hasCustomDefaultTemplate) {
                $hasCustomTemplate = true;
                $localizedPath = $defaultLocalizedPath;
            }
        }

        if (file_exists($localizedPath . '/' . $templateName) || $hasCustomTemplate) {
            $path = $localizedPath;
            $this->AddTemplateDirectory($localizedPath);
        } else {
            // Fallback path
            $path = ROOT_DIR . 'lang/en_us/';
            $this->AddTemplateDirectory($path);
        }

        if (file_exists($path . '/' . $customTemplateName)) {
            $templateName = $customTemplateName;
        }

        return $this->fetch($templateName);
    }

    protected function RegisterClasses()
    {
        // Classes that should be registered
        $classesToRegister = [
            'Actions',
            'AutoCompleteType',
            'CalendarActions',
            'CalendarTypes',
            'CannedReport',
            'ColumnNames',
            'ConfigActions',
            'ConfigKeys',
            'ConfigSettingType',
            'CookieKeys',
            'CustomAttributeCategory',
            'CustomAttributeTypes',
            'Date',
            'DayOfWeek',
            'EmailTemplatesActions',
            'FormKeys',
            'InvitationAction',
            'ManageAccessoriesActions',
            'ManageAnnouncementsActions',
            'ManageAttributesActions',
            'ManageBlackoutsActions',
            'ManageGroupsActions',
            'ManageQuotasActions',
            'ManageReservationsActions',
            'ManageResourcesActions',
            'ManageSchedules',
            'ManageUsersActions',
            'Pages',
            'ProfileActions',
            'QueryStringKeys',
            'QuotaDuration',
            'QuotaScope',
            'QuotaUnit',
            'RegisterActions',
            'RepeatMonthlyType',
            'ReportActions',
            'Report_GroupBy',
            'Report_Range',
            'Report_ResultSelection',
            'Report_Usage',
            'ReservationAction',
            'ReservationConflictResolution',
            'ReservationEvent',
            'ReservationReminderInterval',
            'ReservationStatus',
            'ResourcePermissionType',
            'ResourceStatus',
            'Resources',
            'Schedule',
            'ScheduleLayout',
            'ScheduleStyle',
            'SeriesUpdateScope',
            'TermsOfService',
            'UserAttribute',

        ];

        foreach ($classesToRegister as $className) {
            try {
                if (class_exists($className)) {
                    $this->registerClass($className, $className);
                }
            } catch (Exception $ex) {
                error_log("Error registering $className : " . $ex->getMessage());
            }
        }
    }

    protected function RegisterFunctions()
    {
        $this->registerPlugin('function', 'translate', $this->SmartyTranslate(...));
        $this->registerPlugin('function', 'formatdate', $this->FormatDate(...));
        $this->registerPlugin('function', 'format_date', $this->FormatDate(...));
        $this->registerPlugin('function', 'html_link', $this->PrintLink(...));
        $this->registerPlugin('function', 'control', $this->DisplayControl(...));
        $this->registerPlugin('function', 'validator', $this->Validator(...));
        $this->registerPlugin('function', 'textbox', $this->Textbox(...));
        $this->registerPlugin('function', 'object_html_options', $this->ObjectHtmlOptions(...));
        $this->registerPlugin('block', 'validation_group', $this->ValidationGroup(...));
        $this->registerPlugin('function', 'setfocus', $this->SetFocus(...));
        $this->registerPlugin('function', 'formname', $this->GetFormName(...));
        $this->registerPlugin('modifier', 'url2link', $this->CreateUrl(...));
        $this->registerPlugin('function', 'js_array', $this->CreateJavascriptArray(...));
        $this->registerPlugin('function', 'async_validator', $this->AsyncValidator(...));
        $this->registerPlugin('function', 'fullname', $this->DisplayFullName(...));
        $this->registerPlugin('function', 'add_querystring', $this->AddQueryString(...));
        $this->registerPlugin('function', 'resource_image', $this->GetResourceImage(...));
        $this->registerPlugin('modifier', 'escapequotes', $this->EscapeQuotes(...));
        $this->registerPlugin('function', 'flush', $this->Flush(...));
        $this->registerPlugin('function', 'jsfile', $this->IncludeJavascriptFile(...));
        $this->registerPlugin('function', 'cssfile', $this->IncludeCssFile(...));
        $this->registerPlugin('function', 'indicator', $this->DisplayIndicator(...));
        $this->registerPlugin('function', 'read_only_attribute', $this->ReadOnlyAttribute(...));
        $this->registerPlugin('function', 'csrf_token', $this->CSRFToken(...));
        $this->registerPlugin('function', 'cancel_button', $this->CancelButton(...));
        $this->registerPlugin('function', 'update_button', $this->UpdateButton(...));
        $this->registerPlugin('function', 'add_button', $this->AddButton(...));
        $this->registerPlugin('function', 'delete_button', $this->DeleteButton(...));
        $this->registerPlugin('function', 'reset_button', $this->ResetButton(...));
        $this->registerPlugin('function', 'filter_button', $this->FilterButton(...));
        $this->registerPlugin('function', 'ok_button', $this->OkButton(...));
        $this->registerPlugin('function', 'showhide_icon', $this->ShowHideIcon(...));
        $this->registerPlugin('function', 'sort_column', $this->SortColumn(...));
        $this->registerPlugin('function', 'formatcurrency', $this->FormatCurrency(...));
        $this->registerPlugin('function', 'linebreak', $this->LineBreak(...));
        $this->registerPlugin('modifier', 'urlencode', $this->UrlEncode(...));
        $this->registerPlugin('modifier', 'html_entity_decode', $this->HtmlEntityDecode(...));
        $this->registerPlugin('modifier', 'intval', $this->Intval(...));
        $this->registerPlugin('modifier', 'strtolower', $this->Strtolower(...));
        $this->registerPlugin('function', 'datatable', $this->CreateDataTable(...));
        $this->registerPlugin('function', 'datatablefilter', $this->CreateDataTableFilter(...));
        $this->registerPlugin('modifier', 'microtime', $this->Microtime(...));
        $this->registerPlugin('modifier', 'array_key_exists', $this->ArrayKeyExists(...));
        $this->registerPlugin('modifier', 'count', $this->Count(...));

        /**
         * PageValidators
         */
        $this->Validators = new PageValidators($this);
    }

    public function IsValid()
    {
        try {
            $this->Validate();
            $this->IsValid = $this->Validators->AreAllValid();
            return $this->IsValid;
        } catch (Exception $ex) {
            Log::Error('Error during page validation', $ex);
            return false;
        }
    }

    public function Validate()
    {
        $this->Validators->Validate();
    }

    /**
     * @var array|IValidator[]
     */
    public $failedValidators = [];

    /**
     * @param $id int
     * @param $validator IValidator
     */
    public function AddFailedValidation($id, $validator)
    {
        $this->failedValidators[$id] = $validator;
    }

    private function AppendAttributes($params, $knownAttributes)
    {
        $extraKeys = array_diff(array_keys($params), $knownAttributes);

        $attributes = new StringBuilder();
        foreach ($extraKeys as $key) {
            $attributes->Append("$key=\"{$params[$key]}\" ");
        }

        return $attributes->ToString();
    }

    public function PrintLink($params, $smarty)
    {
        $string = $this->Resources->GetString($params['key']);
        if (!isset($params['title'])) {
            $title = $string;
        } else {
            $title = $this->Resources->GetString($params['title']);
        }

        if (BookedStringHelper::StartsWith($params['href'], '/')) {
            $href = $params['href'];
        } else {
            $href = $this->RootPath . $params['href'];
        }

        $knownAttributes = ['key', 'title', 'href'];
        $attributes = $this->AppendAttributes($params, $knownAttributes);

        return "<a href=\"$href\" class=\"link-primary\" title=\"$title\" $attributes><i class=\"bi bi-people-fill me-1\"></i>$string</a>";
    }

    public function SmartyTranslate($params, $smarty)
    {
        if (!isset($params['args'])) {
            return $this->Resources->GetString($params['key'], '');
        }
        return $this->Resources->GetString($params['key'], explode(',', $params['args']));
    }

    public function FormatDate($params, $smarty)
    {
        if (!isset($params['date']) || empty($params['date'])) {
            return '';
        }

        $date = is_string($params['date']) ? Date::Parse($params['date']) : $params['date'];

        /** @var Date $date */
        $date = isset($params['timezone']) ? $date->ToTimezone($params['timezone']) : $date;

        if (isset($params['format'])) {
            return $date->Format($params['format']);
        }

        $key = 'general_date';
        if (isset($params['key'])) {
            $key = $params['key'];
        }
        $format = $this->Resources->GetDateFormat($key);

        $formatted = $date->Format($format);

        if (str_contains((string) $format, 'l')) {
            // correct english day name to translated day name
            $english_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $days = $this->Resources->GetDays('full');
            $formatted = str_replace($english_days[$date->Weekday()], $days[$date->Weekday()], $formatted);
        }
        return $formatted;
    }

    public function DisplayControl($params, $smarty)
    {
        $type = $params['type'];
        require_once(ROOT_DIR . "Controls/$type.php");

        /** @var Control $control */
        $control = new $type($this);

        foreach ($params as $key => $val) {
            if ($key != 'type') {
                $control->Set($key, $val);
            }
        }

        $control->PageLoad();
    }

    public function ValidationGroup($params, $content, $smarty, &$repeat)
    {
        $class = 'error';

        if (isset($params['class'])) {
            $class = $params['class'];
        }

        if (!$repeat) {
            $actualContent = trim((string) $content);

            return empty($actualContent) ? '' :
                '<div class="' . $class . ' d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-2 me-3"></i>
                    <div class="error-list">
                        <ul class="list-unstyled">' . $actualContent . '</ul>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
        return '';
    }

    public function Validator($params, $smarty)
    {
        $validator = $this->Validators->Get($params['id']);
        if (!$validator->IsValid()) {
            if (isset($params['key']) && !empty($params['key'])) {
                return '<li>' . $this->SmartyTranslate(['key' => $params['key']], $smarty) . '</li>';
            }

            $messages = $validator->Messages();
            if (!empty($messages)) {
                $errors = '';
                foreach ($messages as $message) {
                    $errors .= sprintf('<li id="%s">%s</li>', $params['id'], $message);
                }

                return $errors;
            }
        }
        return '';
    }

    public function AsyncValidator($params, $smarty)
    {
        $message = '';
        if (isset($params['key']) && !empty($params['key'])) {
            $message = $this->SmartyTranslate(['key' => $params['key']], $smarty);
        }
        return sprintf('<li class="asyncValidation" id="%s">%s</li>', $params['id'], $message);
    }

    public function Textbox($params, $smarty)
    {
        $class = null;
        $value = null;
        $size = null;
        $tabindex = null;
        $type = null;

        if (isset($params['class'])) {
            $params['class'] = $params['class'] . ' form-control';
        } else {
            $params['class'] = 'form-control';
        }

        if (isset($params['value'])) {
            $value = $params['value'];
        }

        if (isset($params['size'])) {
            $size = $params['size'];
        }

        if (isset($params['tabindex'])) {
            $tabindex = $params['tabindex'];
        }

        if (isset($params['type'])) {
            $type = strtolower($params['type']);
        } else {
            $type = 'text';
        }
        if (isset($params['required']) && $params['required'] == true) {
            $required = true;
        } else {
            $required = false;
        }

        $id = null;
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        if (isset($params['placeholderkey'])) {
            $params['placeholder'] = $this->Resources->GetString($params['placeholderkey']);
        }

        $knownAttributes = ['value', 'type', 'name', 'placeholderkey', 'required'];
        $attributes = $this->AppendAttributes($params, $knownAttributes);

        if ($type == 'password') {
            $textbox = new SmartyPasswordbox($params['name'], 'password', $id, $value, $attributes, $required, $smarty);
        } else {
            $textbox = new SmartyTextbox($params['name'], $type, $id, $value, $attributes, $required, $smarty);
        }

        return $textbox->Html();
    }

    public function ObjectHtmlOptions($params, $smarty)
    {
        $key = $params['key'];
        $label = $params['label'];
        $options = $params['options'];
        $type = $params['type'] ?? 'array';
        $usemethod = $params['usemethod'] ?? true;
        $selected = $params['selected'] ?? '';

        $builder = new StringBuilder();
        foreach ($options as $option) {
            $_key = ($usemethod) ? $option->$key() : $option->$key;
            $_label = ($usemethod) ? $option->$label() : $option->$label;
            $isselected = ($_key == $selected) ? 'selected="selected"' : '';
            $builder->Append(sprintf(
                '<option label="%s" value="%s"%s>%s</option>',
                $_label,
                $_key,
                $isselected,
                $_label
            ));
        }

        return $builder->ToString();
    }

    public function SetFocus($params, $smarty)
    {
        $id = isset($params['key']) ? FormKeys::Evaluate($params['key']) : $params['id'];
        return "<script type=\"text/javascript\">document.getElementById('$id').focus();</script>";
    }

    public function GetFormName($params, $smarty)
    {
        $append = '';

        if (isset($params['multi'])) {
            $append = '[]';
        }
        return 'name=\'' . FormKeys::Evaluate($params['key']) . $append . '\'';
    }

    public function CreateUrl($url)
    {
        // credit to WordPress wp-includes/formatting.php
        $make_url_clickable = function ($matches) {
            $ret = '';
            $url = $matches[2];

            if (empty($url)) {
                return $matches[0];
            }
            // removed trailing [.,;:] from URL
            if (in_array(substr((string) $url, -1), ['.', ',', ';', ':']) === true) {
                $ret = substr((string) $url, -1);
                $url = substr((string) $url, 0, strlen((string) $url) - 1);
            }

            $text = $url;
            if (strlen($text) > 30) {
                $text = substr($text, 0, 30) . '...';
            }

            return $matches[1] . "<a href=\"$url\" target=\"_blank\" rel=\"nofollow\">$text</a>" . $ret;
        };

        $make_web_ftp_clickable_cb = function ($matches) {
            $ret = '';
            $dest = $matches[2];
            $dest = 'http://' . $dest;

            // removed trailing [,;:] from URL
            if (in_array(substr($dest, -1), ['.', ',', ';', ':']) === true) {
                $ret = substr($dest, -1);
                $dest = substr($dest, 0, strlen($dest) - 1);
            }

            $text = $dest;
            if (strlen($text) > 30) {
                $text = substr($text, 0, 30) . '...';
            }

            return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$text</a>" . $ret;
        };

        $make_email_clickable_cb = function ($matches) {
            $email = $matches[2] . '@' . $matches[3];
            return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
        };

        $url = ' ' . $url;
        $url = preg_replace_callback(
            '#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
            $make_url_clickable,
            $url
        );
        $url = preg_replace_callback(
            '#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
            $make_web_ftp_clickable_cb,
            (string) $url
        );
        $url = preg_replace_callback(
            '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i',
            $make_email_clickable_cb,
            (string) $url
        );
        $url = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", (string) $url);
        $url = trim((string) $url);
        return $url;
    }

    public function CreateDataTable($params)
    {
        $tableId = $params['tableId'];
        $searchText = $this->Resources->GetString('Filter');
        $AllText = $this->Resources->GetString('All');
        $NoResultsFoundText = $this->Resources->GetString('NoResultsFound');
        $copyText = $this->Resources->GetString('Copy');
        $exportText = $this->Resources->GetString('Export');
        $printText = $this->Resources->GetString('Print');
        $showHideText = $this->Resources->GetString('ShowHide');
        $infoText = $this->Resources->GetString('Info');
        $lengthMenuText = $this->Resources->GetString('LengthMenu');

        if ($tableId == 'report-results') {
            $pagination = '"paging": false,
                "lengthChange": false,
                "searching": false,
                "info": false,
                "ordering": false,';
        } else {
            $pagination = '"lengthMenu": [ [25, 50, 75, 100, -1], [ 25, 50, 75, 100, "' . $AllText . '"] ],';
        }

        return sprintf(
            '<script>
           var table =  $("#' . $tableId . '").DataTable({
                "searching": false,
                "dom": \'<"d-flex justify-content-center flex-wrap"B><"d-flex justify-content-between flex-wrap mt-2"fil>rt<"d-flex justify-content-center"i><"d-flex justify-content-center"p><"clear">\',
                ' . $pagination . '
                "language": {
                    search: "' . $searchText . '",
                    info: "' . $infoText . '",
                    infoEmpty: "' . $NoResultsFoundText . '",
                    infoFiltered: "",
                    lengthMenu: "' . $lengthMenuText . '",
                    zeroRecords: "' . $NoResultsFoundText . '",
                },
                "buttons": [
                    {
                        extend: "copyHtml5",
                        text: "<i class=\"bi bi-copy me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $copyText . '</div>",
                    },
                    {
                        extend: "excelHtml5",
                        text: "<i class=\"bi bi-file-earmark-spreadsheet me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $exportText . ' Excel</div>",
                    },
                    {
                        extend: "pdfHtml5",
                        text: "<i class=\"bi bi-filetype-pdf me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $exportText . ' PDF</div>",
                    },
                    {
                        extend: "print",
                        text: "<i class=\"bi bi-printer me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $printText . '</div>",
                    },
                    {
                        extend: "colvis",
                        text: "<i class=\"bi bi-list-check me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $showHideText . '</div>",
                    }
                ],
                "initComplete": function(settings, json) {
                    var table = this.api();
                    table.on("init.dt", function () {
                        $(".dt-buttons .btn-secondary").removeClass("btn-secondary").addClass("btn-primary");
                        $(".dt-buttons").addClass("btn-group-sm");
                        $(".buttons-collection").addClass("btn-sm");
                    });
                },
                "drawCallback": function (settings) {
                    if (typeof setUpEditables !== "undefined") {
                        setUpEditables();
                    }
                }
            });
        </script>
        '
        );
    }
    public function CreateDataTableFilter($params)
    {
        $tableId = $params['tableId'];
        $searchText = $this->Resources->GetString('Filter');
        $viewAllText = $this->Resources->GetString('All');
        $NoResultsFoundText = $this->Resources->GetString('NoResultsFound');
        $infoText = $this->Resources->GetString('Info');
        $lengthMenuText = $this->Resources->GetString('LengthMenu');

        return sprintf(
            '<script>
           var table =  $("#' . $tableId . '").DataTable({
                "dom": \'<"d-flex justify-content-between my-1"fl><t>t<"d-flex justify-content-center"i><"d-flex justify-content-center"p><"clear">\',
                "lengthMenu": [ [25, 50, 75, 100, -1], [ 25, 50, 75, 100, "' . $viewAllText . '"] ],
                language: {
                    search: "' . $searchText . '",
                    info: "' . $searchText . '",
                    infoEmpty: "' . $infoText . '",
                    infoFiltered: "",
                    lengthMenu: "' . $lengthMenuText . '",
                    zeroRecords: "' . $NoResultsFoundText .
                '"
                },
                "drawCallback": function (settings) {
                    if (typeof setUpEditables !== "undefined") {
                        setUpEditables();
                    }
                }
            });
        </script>
        '
        );
    }
    public function ReplaceQueryString($url, $key, $value)
    {
        $newUrl = $url;

        if (!str_contains((string) $url, (string) $key)) { // does not have variable
            if (!str_contains((string) $url, '?')) { // and does not have any query string
                $newUrl = sprintf('%s?%s=%s', $url, $key, $value);
            } else {
                $newUrl = sprintf('%s&amp;%s=%s', $url, $key, $value); // and has existing query string
            }
        } else {
            $pattern = '/(\?|&)(' . $key . '=.*)/';
            $replace = '${1}' . $key . '=' . $value;

            $newUrl = preg_replace($pattern, $replace, (string) $url);
        }

        return $newUrl;
    }

    public function CreateJavascriptArray($params, $smarty)
    {
        $array = $params['array'];

        $string = implode('","', $array);

        return "[\"$string\"]";
    }

    public function DisplayFullName($params, $smarty)
    {
        $config = Configuration::Instance();
        $ignorePrivacy = false;
        if (isset($params['ignorePrivacy']) && strtolower($params['ignorePrivacy'] == 'true')) {
            $ignorePrivacy = true;
        }

        if (
            !$ignorePrivacy && $config->GetKey(
                ConfigKeys::PRIVACY_HIDE_USER_DETAILS,
                new BooleanConverter()
            ) && !ServiceLocator::GetServer()->GetUserSession()->IsAdmin
        ) {
            return $this->Resources->GetString('Private');
        }

        $fullName = new FullName($params['first'], $params['last']);

        return htmlspecialchars($fullName->__toString());
    }

    public function AddQueryString($params, $smarty)
    {
        $url = new Url(ServiceLocator::GetServer()->GetUrl());
        $name = constant(sprintf('QueryStringKeys::%s', $params['key']));
        $url->AddQueryString($name, $params['value']);

        return $url->ToString();
    }

    public function GetResourceImage($params, $smarty)
    {
        $imageUrl = Configuration::Instance()->GetKey(ConfigKeys::UPLOAD_IMAGE_URL);

        if (!str_contains((string) $imageUrl, 'http://')) {
            $imageUrl = Configuration::Instance()->GetScriptUrl() . "/$imageUrl";
        }

        return "$imageUrl/{$params['image']}";
    }

    public function EscapeQuotes($var)
    {
        $str = str_replace('\'', '&#39;', $var);
        return str_replace('"', '&quot;', $str);
    }

    public function Flush($params, $smarty)
    {
        echo '<!-- flushing -->';
        flush();
    }

    public function IncludeJavascriptFile($params, $smarty)
    {
        $versionNumber = Configuration::VERSION;
        $async = isset($params['async']) ? ' async' : '';
        echo "<script type=\"text/javascript\" src=\"{$this->RootPath}scripts/{$params['src']}?v=$versionNumber\"{$async}></script>";
    }

    public function IncludeCssFile($params, $smarty)
    {
        $versionNumber = Configuration::VERSION;
        $src = $params['src'];
        if (!BookedStringHelper::Contains($src, '/')) {
            $src = "css/{$src}";
        }
        echo "<link rel='stylesheet' type='text/css' href='{$this->RootPath}{$src}?v=$versionNumber'/>";
    }

    public function DisplayIndicator($params, $smarty)
    {
        $id = $params['id'] ?? '';
        $size = isset($params['size']) ? "spinner-border-{$params['size']}" : 'spinner-border-sm';
        $show = isset($params['show']) ? '' : 'd-none';
        $class = $params['class'] ?? 'indicator';

        echo "<span id=\"$id\" class=\"spinner-border $size $class $show\"></span>";
    }

    public function ReadOnlyAttribute($params, $smarty)
    {
        $attrVal = $params['value'];
        $attribute = $params['attribute'];
        if ($attribute->Type() == CustomAttributeTypes::CHECKBOX) {
            if ($attrVal == 1) {
                echo Resources::GetInstance()->GetString('Yes');
            } else {
                echo Resources::GetInstance()->GetString('No');
            }
        } else {
            echo $attrVal;
        }
    }

    public function CSRFToken($params, $smarty)
    {
        echo '<input type="hidden" id="csrf_token" name="' . FormKeys::CSRF_TOKEN . '" value="' .
            ServiceLocator::GetServer()->GetUserSession()->CSRFToken . '"/>';
    }

    private function GetButtonAttributes($params)
    {
        $knownAttributes = ['key', 'class'];
        return $this->AppendAttributes($params, $knownAttributes);
    }

    public function CancelButton($params, $smarty)
    {
        $key = $params['key'] ?? 'Cancel';
        $class = $params['class'] ?? '';
        echo '<button type="button" class="btn btn-outline-secondary cancel ' . $class . '" data-bs-dismiss="modal" ' . $this->GetButtonAttributes($params) . '>' .
            Resources::GetInstance()->GetString($key) . '</button>';
    }

    public function UpdateButton($params, $smarty)
    {
        $key = $params['key'] ?? 'Update';
        $class = isset($params['class']) ? ' ' . $params['class'] . ' ' : '';
        $type = isset($params['submit']) ? 'submit' : 'button';
        $save = $type == 'submit' ? '' : ' save ';

        echo '<button type="' . $type . '" class="btn btn-primary' . $save . $class . '" ' . $this->GetButtonAttributes($params) . '><i class="bi bi-check2-circle"></i> ' . Resources::GetInstance()
            ->GetString($key) . '</button>';
    }

    public function AddButton($params, $smarty)
    {
        $key = $params['key'] ?? 'Add';
        $class = $params['class'] ?? '';
        $submit = $params['submit'] ?? false;
        $type = 'button';
        if ($submit) {
            $type = 'submit';
        }

        echo '<button type="' . $type . '" class="btn btn-primary save ' . $class . '" ' . $this->GetButtonAttributes($params) . '><i class="bi bi-check2-circle"></i> ' . Resources::GetInstance()
            ->GetString($key) . '</button>';
    }

    public function DeleteButton($params, $smarty)
    {
        $key = $params['key'] ?? 'Delete';
        $class = $params['class'] ?? '';
        $submit = $params['submit'] ?? false;
        $type = 'button';
        if ($submit) {
            $type = 'submit';
        }
        echo '<button type="' . $type . '" class="btn btn-danger save ' . $class . '" ' . $this->GetButtonAttributes($params) . '><i class="bi bi-trash3-fill"></i> ' . Resources::GetInstance()
            ->GetString($key) . '</button>';
    }

    public function ResetButton($params, $smarty)
    {
        $key = $params['key'] ?? 'Reset';
        $class = $params['class'] ?? '';
        echo '<button type="reset" class="btn btn-outline-secondary ' . $class . '" ' . $this->GetButtonAttributes($params) . '><i class="bi bi-arrow-counterclockwise me-1"></i>' . Resources::GetInstance()
            ->GetString($key) . '</button>';
    }

    public function FilterButton($params, $smarty)
    {
        $key = $params['key'] ?? 'Filter';
        $class = $params['class'] ?? '';
        echo '<button type="search" class="btn btn-primary ' . $class . '" ' . $this->GetButtonAttributes($params) . '><i class="bi bi-search"></i> ' . Resources::GetInstance()
            ->GetString($key) . '</button>';
    }

    public function OkButton($params, $smarty)
    {
        $key = $params['key'] ?? 'OK';
        $class = $params['class'] ?? '';
        echo '<button type="button" class="btn btn-primary ' . $class . '" ' . $this->GetButtonAttributes($params) . '><i class="bi bi-check2-circle"></i> ' . Resources::GetInstance()
            ->GetString($key) . '</button>';
    }

    public function ShowHideIcon($params, $smarty)
    {
        $class = $params['class'] ?? '';
        echo '<a class="link-primary" href="#"><i class="show-hide bi ' . $class . '"></i><span class="visually-hidden">Show/Hide</span></a>';
    }

    public function SortColumn($params, $smarty)
    {
        $server = ServiceLocator::GetServer();
        $url = $server->GetRequestUri();

        $sortField = $params['field'];
        $sortDirection = 'asc';
        $currentDirection = $server->GetQuerystring(QueryStringKeys::SORT_DIRECTION);
        $currentField = $server->GetQuerystring(QueryStringKeys::SORT_FIELD);

        $hasQueryString = BookedStringHelper::Contains($url, '?');
        $sd = QueryStringKeys::SORT_DIRECTION;
        $sf = QueryStringKeys::SORT_FIELD;

        $indicator = '';
        if ($sortField == $currentField) {
            $sortDirection = $currentDirection == 'asc' ? 'desc' : 'asc';
            $indicator = "<i class=\"bi bi-caret-down-fill\"></i>";
            if ($currentDirection == 'asc') {
                $indicator = "<i class=\"bi bi-caret-up-fill\"></i>";
            }
        }

        if (BookedStringHelper::Contains($url, $sd)) {
            $url = preg_replace("/$sd=(asc|desc)&?/", "$sd=$sortDirection&", (string) $url);
        } else {
            $url = $url . ($hasQueryString ? "&" : "?") . "$sd=$sortDirection";
        }

        if (BookedStringHelper::Contains($url, $sf)) {
            $url = preg_replace("/$sf=[a-zA-Z0-9_\\-]+&?/", "$sf=$sortField&", $url);
        } else {
            $url = "$url&$sf=$sortField";
        }

        echo '<a href="' . $url . '">' . $this->Resources->GetString($params['key']) . ' ' . $indicator . '</a>';
    }

    public function FormatCurrency($params, $smarty)
    {
        $amount = isset($params['amount']) && is_numeric($params['amount']) ? floatval($params['amount']) : 0.0;
        $currency = $params['currency'] ?? 'USD';

        if (!class_exists('NumberFormatter')) {
            if ($currency == 'USD') {
                echo '$' . number_format($amount, 2) . ' USD';
            } else {
                echo 'We cannot format this currency. <a href="http://php.net/manual/en/book.intl.php">You must enable internationalization</a>.';
            }
        } else {
            $fmt = new NumberFormatter($this->Resources->CurrentLanguage, NumberFormatter::CURRENCY);
            echo $fmt->formatCurrency($amount, $currency);
        }
    }

    public function LineBreak($params, $smarty)
    {
        return "\n";
    }

    public function UrlEncode($url)
    {
        return urlencode((string) $url);
    }

    public function Microtime(bool $as_float = false): string|float
    {
        return microtime($as_float);
    }

    public function ArrayKeyExists(string|int|float|bool|null $key, array $array): bool
    {
        return array_key_exists($key, $array);
    }

    public function Count(Countable|array $value, int $mode = COUNT_NORMAL): int
    {
        return count($value, $mode);
    }

    public function HtmlEntityDecode($string)
    {
        return html_entity_decode((string) $string);
    }

    public function Intval($string)
    {
        return intval($string);
    }

    public function Strtolower($string)
    {
        return strtolower((string) $string);
    }
}
