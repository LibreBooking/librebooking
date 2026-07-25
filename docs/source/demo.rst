Live Demo
=========

A hosted demo of LibreBooking is available for testing. It runs on a free
`Fly.io <https://fly.io/>`_ tier and may take a few seconds to wake up if it
has been idle.

.. list-table::
   :header-rows: 1

   * - Role
     - Username
     - Password
   * - Admin
     - ``admin``
     - ``demoadmin``
   * - User
     - ``user``
     - ``demouser``

Note: This instance is public and **resets every 20 minutes** to ensure a
clean environment.

.. raw:: html

   <div class="lb-demo-launcher">
     <div class="lb-demo-spinner" aria-hidden="true"></div>
     <p id="lb-demo-status" aria-live="polite">Waking up the demo server&hellip;</p>
     <p id="lb-demo-fallback" hidden>
       Taking a while? <a href="https://librebooking-demo.fly.dev/Web/">Open the demo directly</a>.
     </p>
     <noscript>
       <p><a href="https://librebooking-demo.fly.dev/Web/">Open the demo directly</a> (JavaScript is required for the automatic redirect).</p>
     </noscript>
   </div>

   <style>
     .lb-demo-launcher {
       text-align: center;
       padding: 1.5rem 2rem;
       max-width: 24rem;
       margin: 2rem auto;
       background: var(--color-background-secondary);
       border: 1px solid var(--color-background-border);
       border-radius: 0.5rem;
     }
     .lb-demo-spinner {
       width: 2.5rem;
       height: 2.5rem;
       margin: 0 auto 1rem;
       border: 4px solid var(--color-background-border);
       border-top-color: var(--color-brand-primary);
       border-radius: 50%;
       animation: lb-demo-spin 1s linear infinite;
     }
     @keyframes lb-demo-spin {
       to { transform: rotate(360deg); }
     }
     #lb-demo-status {
       font-size: 1rem;
       color: var(--color-foreground-secondary);
       min-height: 1.5em;
     }
     #lb-demo-fallback {
       margin-top: 1rem;
       font-size: 0.875rem;
     }
   </style>

   <script>
     (function () {
       var DEMO_URL = 'https://librebooking-demo.fly.dev/Web/';
       var RETRY_DELAY_MS = 2000;
       var PROBE_TIMEOUT_MS = 8000;
       var SLOW_NOTICE_AFTER_MS = 30000;
       var FALLBACK_LINK_AFTER_MS = 90000;
       var MIN_DISPLAY_MS = 2000;
       var startTime = Date.now();
       var statusEl = document.getElementById('lb-demo-status');
       var fallbackEl = document.getElementById('lb-demo-fallback');

       function updateStatus() {
         var elapsed = Date.now() - startTime;
         if (elapsed < SLOW_NOTICE_AFTER_MS) {
           statusEl.textContent = 'Waking up the demo server…';
         } else {
           statusEl.textContent = 'Still waking up — Fly.io cold-start can take up to 2 minutes…';
           if (elapsed >= FALLBACK_LINK_AFTER_MS) {
             fallbackEl.hidden = false;
           }
         }
       }

       function probeOnce() {
         var options = { mode: 'no-cors', cache: 'no-store' };
         if (typeof AbortSignal !== 'undefined' && typeof AbortSignal.timeout === 'function') {
           options.signal = AbortSignal.timeout(PROBE_TIMEOUT_MS);
         }
         return fetch(DEMO_URL, options).then(function () {
           return true;
         }).catch(function () {
           return false;
         });
       }

       function pollLoop() {
         updateStatus();
         probeOnce().then(function (ok) {
           if (ok) {
             var remaining = MIN_DISPLAY_MS - (Date.now() - startTime);
             setTimeout(function () {
               window.location.replace(DEMO_URL);
             }, Math.max(remaining, 0));
           } else {
             setTimeout(pollLoop, RETRY_DELAY_MS);
           }
         });
       }

       setInterval(updateStatus, 1000);
       pollLoop();
     })();
   </script>
