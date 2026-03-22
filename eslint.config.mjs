import globals from 'globals';
import eslintConfigPrettier from 'eslint-config-prettier';

export default [
  {
    files: ['Web/scripts/**/*.js'],
    languageOptions: {
      ecmaVersion: 2021,
      sourceType: 'script',
      globals: {
        ...globals.browser,

        // Third-party libraries
        jQuery: 'readonly',
        $: 'readonly',
        _: 'readonly',
        moment: 'readonly',
        flatpickr: 'readonly',
        DOMPurify: 'readonly',
        grecaptcha: 'readonly',
        JSZip: 'readonly',
        Chart: 'readonly',
        bootstrap: 'readonly',
        html2canvas: 'readonly',

        // Core utilities (phpscheduleit.js)
        startsWith: 'readonly',
        createCookie: 'readonly',
        readCookie: 'readonly',
        eraseCookie: 'readonly',
        getQueryStringValue: 'readonly',
        init: 'readonly',
        validateEmail: 'readonly',
        initializeAccordions: 'readonly',
        cookies: 'readonly',

        // AJAX helpers (ajax-helpers.js)
        ajaxPost: 'readonly',
        ajaxGet: 'readonly',
        HasResponseText: 'readonly',
        ConfigureAsyncForm: 'readonly',
        ConfigureUploadForm: 'readonly',
        BeforeFormSubmit: 'readonly',
        BeforeSerializeDecorator: 'readonly',
        BeforeSerialize: 'readonly',
        PerformAsyncAction: 'readonly',
        PerformAsyncPost: 'readonly',
        ClearAsyncErrors: 'readonly',
        HtmlDecode: 'readonly',
        ajaxPagination: 'readonly',
        ConfigureAdminDialog: 'readonly',

        // Date helpers (date-helper.js)
        dateHelper: 'readonly',
        ChangeDate: 'readonly',
        dpDateChanged: 'readonly',
        getNumberOfDaysInReservation: 'readonly',

        // Page/component constructors
        Calendar: 'readonly',
        Reservation: 'readonly',
        Schedule: 'readonly',
        Recurrence: 'readonly',
        Participation: 'readonly',
        Approval: 'readonly',
        AvailabilitySearch: 'readonly',
        ReservationSearch: 'readonly',
        Dashboard: 'readonly',
        Profile: 'readonly',
        Registration: 'readonly',
        Reminder: 'readonly',
        ResourceDisplay: 'readonly',
        UserCredits: 'readonly',
        MonitorDisplay: 'readonly',
        ReportChart: 'readonly',
        reportsCleanText: 'readonly',

        // Menu functions (menubar.js)
        mopen: 'readonly',
        mclose: 'readonly',
        mclosetimer: 'readonly',
        mcancelclosetimer: 'readonly',

        // Schedule globals (schedule.js)
        renderEvents: 'readonly',
        addNumericalIdsToRows: 'readonly',
        scheduleOpts: 'readonly',
        scheduleSpecificDates: 'readonly',
        resourceOrder: 'readonly',
        filterResources: 'readonly',

        // Calendar globals (calendar.js)
        rebindSubscriptionData: 'readonly',
        drillDownClick: 'readonly',
        openNewReservation: 'readonly',
        getUrlFormattedDate: 'readonly',

        // Embedded calendar
        bookedLoadCalendar: 'readonly',
        translateTooltips: 'readonly',
        reportId: 'readonly',

        // Popup helpers (resourcePopup.js, userPopup.js, reservationPopup.js)
        bindResourceDetails: 'readonly',
        attachUserDetailsPopup: 'readonly',
        attachReservationPopup: 'readonly',

        // Menu state variables (menubar.js)
        timeout: 'writable',
        closetimer: 'writable',
        submenuitem: 'writable',
      },
    },
    rules: {
      // Bug-catching rules
      'no-undef': 'error',
      'no-unreachable': 'error',
      'no-dupe-keys': 'error',
      'no-duplicate-case': 'error',
      'no-constant-condition': 'error',
      'no-debugger': 'error',
      'no-extra-semi': 'error',
      'use-isnan': 'error',
      'valid-typeof': 'error',
      'no-unsafe-negation': 'error',
      'no-sparse-arrays': 'error',
      'no-unused-vars': ['error', { args: 'none', varsIgnorePattern: '^[A-Z]' }],

      // Code consistency rules
      'prefer-const': 'error',
    },
  },
  eslintConfigPrettier,
];
