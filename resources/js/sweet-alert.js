/**
 * SweetAlert2 Laravel Integration - Custom JavaScript
 *
 * Provides helper utilities and framework integration listeners
 * (Livewire v4, plain browser events) for server-triggered alerts.
 */

const SweetAlertLaravel = {
    /**
     * Close the currently active SweetAlert2 popup.
     */
    close: function() {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
    },

    /**
     * Trigger a confirm action on the current popup.
     */
    confirm: function() {
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.clickConfirm();
        }
    },

    /**
     * Trigger a cancel action on the current popup.
     */
    cancel: function() {
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.clickCancel();
        }
    },

    /**
     * Trigger a deny action on the current popup.
     */
    deny: function() {
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.clickDeny();
        }
    },

    /**
     * Check if a SweetAlert2 popup is currently visible.
     */
    isVisible: function() {
        return typeof Swal !== 'undefined' && Swal.isVisible();
    },

    /**
     * Show a popup with the given configuration.
     */
    fire: function(config) {
        if (typeof Swal !== 'undefined') {
            return Swal.fire(config);
        }
        return Promise.resolve();
    }
};

// Make it available globally
if (typeof window !== 'undefined') {
    window.SweetAlertLaravel = SweetAlertLaravel;
}

/*
 * The Livewire and browser-event bridges used to live here, but this file is
 * only on the page once its assets have been published *and* the directive has
 * decided to load them — which it does not do on a page with no pending alert.
 * A Livewire alert has no pending alert by definition, so the listeners were
 * never registered and the alert never appeared.
 *
 * They are emitted inline by the @sweetAlert directive instead, which also
 * loads SweetAlert2 on demand when an event arrives. Keeping a second copy here
 * would fire every Livewire alert twice.
 */
