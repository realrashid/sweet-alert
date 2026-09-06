/**
 * useSweetAlert — React hook for Inertia.
 *
 * The ShareSweetAlertWithInertia middleware puts a pending alert on the page as
 * the `sweetalert` prop. This shows it, on the first load and after every
 * subsequent visit.
 *
 * It listens to Inertia's own `inertia:success` event rather than depending on
 * the prop changing identity between renders, which is not something to rely
 * on. `inertia:success` is used in preference to `inertia:navigate` because a
 * visit to the URL you are already on does not emit `navigate` — which is
 * exactly the save-twice case.
 *
 * The configuration is handed to the package's own listener rather than to Swal
 * directly, so SweetAlert2 is fetched on demand — an Inertia page has no
 * session alert to trigger the eager load, so Swal is usually not on the page
 * yet when the first alert arrives.
 *
 * Call it once, in your root layout:
 *
 *   import { useSweetAlert } from '@/sweet-alert/useSweetAlert';
 *
 *   export default function Layout({ children }) {
 *       useSweetAlert();
 *
 *       return <main>{children}</main>;
 *   }
 *
 * Requires the @sweetAlert directive in the root Blade template, which is what
 * puts that listener on the page.
 */

import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';

/**
 * What was shown for the page currently on screen.
 *
 * The first load presents the alert twice — once from the effect and once from
 * the navigate event Inertia fires for that same page — and the two arrive as
 * different objects holding the same alert, so comparing references does not
 * catch it. Comparing the serialised configuration does, and clearing this when
 * a new navigation starts means two genuinely identical alerts in a row are
 * still both shown.
 */
let shownForThisPage = null;

/**
 * @param {Object} [options]
 * @param {Function} [options.onAlert] - Handle the alert yourself instead.
 */
export function useSweetAlert(options = {}) {
    const { sweetalert } = usePage().props;

    useEffect(() => {
        function present(config) {
            if (! config) {
                return;
            }

            const key = JSON.stringify(config);

            if (key === shownForThisPage) {
                return;
            }

            shownForThisPage = key;

            if (typeof options.onAlert === 'function') {
                options.onAlert(config);

                return;
            }

            showSweetAlert(config);
        }

        function handleResponse(event) {
            present(event?.detail?.page?.props?.sweetalert);
        }

        function handleStart() {
            shownForThisPage = null;
        }

        present(sweetalert);

        document.addEventListener('inertia:start', handleStart);
        document.addEventListener('inertia:success', handleResponse);

        return () => {
            document.removeEventListener('inertia:start', handleStart);
            document.removeEventListener('inertia:success', handleResponse);
        };
    }, [sweetalert]);
}

/**
 * Show an alert from anywhere — an event handler, a plain script.
 *
 * @param {Object} config - A SweetAlert2 configuration object.
 */
export function showSweetAlert(config) {
    if (typeof window === 'undefined') {
        return;
    }

    window.dispatchEvent(new CustomEvent('sweetalert', { detail: { config } }));
}
