/* exported dateHelper */
/**
 * dateHelper
 * -----------
 * Date and time utilities in pure JavaScript (without moment.js).
 *
 * Conventions:
 * - Dates are handled as native Date data.
 * - Times are handled as strings 'HH:mm' or 'HH:mm:ss'.
 * - Difference calculations return rounded (not exact) values.
 */
var dateHelper = (function () {
  var oneDay = 86_400_000; // 24*60*60*1000 => hours*minutes*seconds*milliseconds

  /**
   * Calculates the rounded difference between two Date objects.
   *
   * @param {Date} end
   * @param {Date} begin
   * @returns {{
   *   RoundedDays: number,
   *   RoundedHours: number,
   *   RoundedMinutes: number,
   *   isNonPositive: boolean
   * }}
   *
   * Notes:
   * - Values are absolute and then signed based on order
   * - Hours and minutes are modulo-based (not total hours)
   * - This mimics moment.duration behavior used previously
   */
  function getDifference(end, begin) {
    const difference = end.getTime() - begin.getTime();
    const isNonPositive = difference <= 0;
    const absMs = Math.abs(difference);

    const totalMinutes = Math.floor(absMs / 60_000);
    const totalHours = Math.floor(totalMinutes / 60);
    const totalDays = Math.floor(totalHours / 24);

    return {
      RoundedDays: (isNonPositive ? -1 : 1) * totalDays,
      RoundedHours: (isNonPositive ? -1 : 1) * (totalHours % 24),
      RoundedMinutes: (isNonPositive ? -1 : 1) * (totalMinutes % 60),
      isNonPositive,
    };
  }

  // Pads a number to 2 digits with leading zero
  const pad = (n) => n.toString().padStart(2, '0');

  /**
   * Formats a Date object as 'YYYY-MM-DD' or 'YYYY-MM-DD HH:mm'.
   *
   * By default, uses local time components (getFullYear, getMonth, getDate, etc.).
   * Set useUtc=true to use UTC components (getUTCFullYear, ...).
   *
   * WARNING: For Date objects created from 'YYYY-MM-DD' (e.g., via parseYMDDate),
   * using toISOString() or UTC methods will shift the day if your local timezone is behind UTC.
   * Always use the default (local) mode for calendar and reservation logic unless you explicitly need UTC.
   *
   * Examples:
   *   formatDate(new Date(2026, 3, 22))            // '2026-04-22' (local)
   *   formatDate(new Date(2026, 3, 22, 15, 30), true) // '2026-04-22 15:30' (local)
   *   formatDate(new Date(Date.UTC(2026, 3, 22)), false, true) // '2026-04-22' (UTC)
   *
   * @param {Date} date
   * @param {boolean} withTime - If true, includes ' HH:mm'
   * @param {boolean} useUtc - If true, uses UTC components (default: false)
   * @returns {string}
   */
  function formatDate(date, withTime = false, useUtc = false) {
    if (!(date instanceof Date) || isNaN(date)) return '';
    const y = useUtc ? date.getUTCFullYear() : date.getFullYear();
    const m = useUtc ? date.getUTCMonth() + 1 : date.getMonth() + 1;
    const d = useUtc ? date.getUTCDate() : date.getDate();
    const ymd = y + '-' + pad(m) + '-' + pad(d);
    if (!withTime) return ymd;
    const hh = useUtc ? date.getUTCHours() : date.getHours();
    const mm = useUtc ? date.getUTCMinutes() : date.getMinutes();
    return ymd + ' ' + pad(hh) + ':' + pad(mm);
  }

  /**
   * Parses a time string into a Date object (today's date).
   *
   * @param {string} time - Expected format: 'HH:mm' or 'HH:mm:ss'
   * @returns {Date|null}
   *
   * Notes:
   * - Uses today's date
   * - Seconds default to 0 if not provided
   */
  var parseTime = function (time) {
    if (!time) return null;

    var parts = time.split(':'); // ["HH", "mm", "ss"]
    if (parts.length !== 2 && parts.length !== 3) {
      console.warn('parseTime: invalid time format', time);
      return null;
    }

    var h = parseInt(parts[0], 10) || 0;
    var m = parseInt(parts[1], 10) || 0;
    var s = parseInt(parts[2], 10) || 0;

    var d = new Date();
    d.setHours(h, m, s, 0);

    return d;
  };

  /**
   * Formats a 24-hour time into an internal value and a display text.
   *
   * - `value` is always "HH:mm" (24-hour format)
   * - `text` is formatted according to the provided PHP-like format, e.g., "H:i", "h:i a"
   *
   * The format string usually comes from the backend, for example:
   *   $this->Set('TimeFormat', Resources::GetInstance()->GetDateFormat('period_time'));
   *
   * @param {number} hour24 - Hour in 24-hour format (0–23)
   * @param {number} minute - Minute (0–59)
   * @param {string} format - Display format string (PHP-like)
   *
   * @returns {{
   *   value: string, // "HH:mm" 24-hour internal value
   *   text: string   // Human-readable representation according to format
   * }}
   */

  function formatTime(hour24, minute, format) {
    const value = `${pad(hour24)}:${pad(minute)}`; // internal value always 24h

    switch (format) {
      // https://www.php.net/manual/en/datetime.format.php
      // 'H' = 24-hour with leading zeros
      case 'H:i':
        return { value, text: `${pad(hour24)}:${pad(minute)}` };
      // 'h' = 12-hour with leading zeros
      case 'h:i A':
        return { value, text: `${pad(hour24 % 12 || 12)}:${pad(minute)} ${hour24 >= 12 ? 'PM' : 'AM'}` };
      case 'h:i a':
        return { value, text: `${pad(hour24 % 12 || 12)}:${pad(minute)} ${hour24 >= 12 ? 'pm' : 'am'}` };
      case 'h:i':
        return { value, text: `${pad(hour24 % 12 || 12)}:${pad(minute)}` };
      // 'g' = 12-hour without leading zeros
      case 'g:i A':
        return { value, text: `${hour24 % 12 || 12}:${pad(minute)} ${hour24 >= 12 ? 'PM' : 'AM'}` };
      case 'g:i a':
        return { value, text: `${hour24 % 12 || 12}:${pad(minute)} ${hour24 >= 12 ? 'pm' : 'am'}` };
      // 'G' = 24-hour without leading zeros
      case 'G.i':
        return { value, text: `${hour24}.${pad(minute)}` };
      case 'G:i':
        return { value, text: `${hour24}:${pad(minute)}` };
      default:
        return { value, text: value };
    }
  }

  /**
   * Initializes a time <select> element.
   *
   * Priority for initial value:
   * 1. initialValue argument
   * 2. data-default attribute
   * 3. '00:00'
   *
   * @param {HTMLSelectElement} el
   * @param {string|null} initialValue - 'HH:mm'
   */
  function initTimePicker(el, initialValue) {
    const format = el.dataset.format || 'H:i';
    const dataset_step = parseInt(el.dataset.step || 30, 10);
    const step = Number.isInteger(dataset_step) && dataset_step > 0 && dataset_step <= 60 ? dataset_step : 30;

    let value = '00:00';

    // Use initial value passed if it exists
    if (initialValue) {
      value = initialValue;
    }
    // Otherwise, use data-default
    else if (el.dataset.default) {
      value = el.dataset.default;
    }

    // normalizes HH:mm:ss to HH:mm
    if (value.length > 5) {
      value = value.substring(0, 5);
    }

    fillTimeSelect(el, format, step, value);
  }

  /**
   * Fills a <select> with time options.
   *
   * @param {HTMLSelectElement} selectElement
   * @param {string} format
   * @param {number} step - Minutes step
   * @param {string} selectedValue - 'HH:mm'
   *
   * Notes:
   * - This function clears existing options
   */
  function fillTimeSelect(selectElement, format = 'H:i', step = 30, selectedValue = '00:00') {
    selectElement.innerHTML = '';
    step = Number.isInteger(step) && step > 0 && step <= 60 ? step : 30;

    for (let h = 0; h < 24; h++) {
      for (let m = 0; m < 60; m += step) {
        const formatted = formatTime(h, m, format);
        const option = document.createElement('option');
        option.value = formatted.value;
        option.textContent = formatted.text;

        if (formatted.value === selectedValue) {
          option.selected = true;
        }

        selectElement.appendChild(option);
      }
    }
  }

  /**
   * Parses a 'YYYY-MM-DD' string into a Date object (local time).
   *
   * @param {string} ymd - Date string in 'YYYY-MM-DD' format
   * @returns {Date|null}
   */
  function parseYMDDate(ymd) {
    if (!ymd || typeof ymd !== 'string') return null;
    var parts = ymd.split('-');
    if (parts.length !== 3) return null;
    var year = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10) - 1;
    var day = parseInt(parts[2], 10);
    if (isNaN(year) || isNaN(month) || isNaN(day)) return null;
    return new Date(year, month, day);
  }

  return {
    pad,
    formatDate,
    parseYMDDate,
    MoreThanOneDayBetweenBeginAndEnd: function (beginDateElement, beginTimeElement, endDateElement, endTimeElement) {
      var begin = this.GetDate(beginDateElement, beginTimeElement);
      var end = this.GetDate(endDateElement, endTimeElement);

      var timeBetweenDates = end.getTime() - begin.getTime();

      return timeBetweenDates > oneDay;
    },

    /**
     * Builds a Date object from date and time inputs.
     *
     * Expects:
     * - dateElement.val() => 'YYYY-MM-DD'
     * - timeElement.val() => 'HH:mm' or 'HH:mm:ss'
     */
    GetDate: function (dateElement, timeElement) {
      return new Date(dateElement.val() + 'T' + timeElement.val());
    },

    GetDateDifference: function (beginDateElement, beginTimeElement, endDateElement, endTimeElement) {
      var begin = this.GetDate(beginDateElement, beginTimeElement);
      var end = this.GetDate(endDateElement, endTimeElement);
      return getDifference(end, begin);
    },

    /**
     * Adds a millisecond difference to a time string.
     *
     * @param {number} diff - Milliseconds
     * @param {string} time - 'HH:mm' or 'HH:mm:ss'
     * @returns {string} 'HH:mm:00'
     */
    AddTimeDiff: function (diff, time) {
      var d = parseTime(time);
      if (!d) return time;

      d = new Date(d.getTime() + diff);

      var hh = String(d.getHours()).padStart(2, '0');
      var mm = String(d.getMinutes()).padStart(2, '0');

      return hh + ':' + mm + ':00';
    },

    GetTimeDifference: function (beginTime, endTime) {
      var start = parseTime(beginTime);
      var end = parseTime(endTime);
      if (!start || !end) {
        return 0;
      }
      return end.getTime() - start.getTime();
    },

    /**
     * Validates a time range and marks elements as invalid if needed.
     *
     * @param {HTMLInputElement|HTMLSelectElement} startEl
     * @param {HTMLInputElement|HTMLSelectElement} endEl
     * @param {string} invalidClass - CSS class to apply (default: 'is-invalid')
     *
     * @returns {boolean} true if valid, false otherwise
     */
    ValidateTimeRangeElements: function (startEl, endEl, invalidClass = 'is-invalid') {
      if (!startEl || !endEl) {
        console.warn('ValidateTimeRangeElements: missing elements');
        return false;
      }

      startEl.classList.remove(invalidClass);
      endEl.classList.remove(invalidClass);

      if (!startEl.value || !endEl.value) {
        return false;
      }

      const startValue = startEl.value;
      let endValue = endEl.value;

      // Special case: '00:00' is treated as the end of the day (24:00) for validation
      if (endValue === '00:00') {
        endValue = '24:00';
      }

      const diff = this.GetTimeDifference(startValue, endValue);

      if (diff <= 0) {
        startEl.classList.add(invalidClass);
        endEl.classList.add(invalidClass);
        return false;
      }

      return true;
    },

    initTimePicker,
    formatTime,
    fillTimeSelect,
  };
})();
