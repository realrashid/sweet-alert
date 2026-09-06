<?php

use RealRashid\SweetAlert\Facades\Alert;

/*
 * These render the actual Blade view, because the two theme bugs behind them
 * were invisible to every test that stopped at the flashed config: the config
 * was right, and the alert still came out white.
 */
function renderAlertView(): string
{
    return view('sweetalert::alert')->render();
}

describe('the rendered view', function () {
    it('gives the base stylesheet an id so a theme can be inserted after it', function () {
        session()->flush();
        Alert::success('Themed');

        expect(renderAlertView())->toContain('id="swal2-base-css"');
    });

    /*
     * The directive normally sits at the end of the body, so appending the
     * theme <link> to <head> put it *before* the base stylesheet and the base
     * won. The theme has to be inserted directly after the base sheet.
     */
    it('inserts a per-alert theme after the base stylesheet, not into the head', function () {
        session()->flush();
        Alert::info('Dark')->theme('dark');

        $html = renderAlertView();

        expect($html)->toContain('theme-dark/dark.min.css')
            ->and($html)->toContain("getElementById('swal2-base-css')")
            ->and($html)->toContain('insertBefore(_link, _base.nextSibling)')
            ->and($html)->not->toContain('document.head.appendChild(_link)');
    });

    it('does not send the theme on to SweetAlert2 as a config key', function () {
        session()->flush();
        Alert::info('Dark')->theme('dark');

        expect(renderAlertView())->not->toContain('"theme":"dark"');
    });

    it('fires no alert when none is pending', function () {
        session()->flush();

        expect(renderAlertView())->not->toContain('Swal.fire(swalConfig)');
    });

    it('loads no SweetAlert2 assets when no alert is pending', function () {
        session()->flush();

        expect(renderAlertView())->not->toContain('<script src=');
    });
});

/*
 * The listener behind data-confirm and data-confirm-delete. Before v8 it was
 * rendered only on the request where confirmDelete() had been flashed, so on
 * any other page load a guarded link was an ordinary link and the browser
 * simply opened the URL — reported as #174.
 */
describe('the confirm listener', function () {
    it('is present on a page with no alert at all', function () {
        session()->flush();

        expect(renderAlertView())
            ->toContain('data-confirm-delete')
            ->toContain("addEventListener('click'")
            ->toContain("addEventListener('submit'");
    });

    it('carries the delete defaults from config, with no flash needed', function () {
        session()->flush();
        config()->set('sweetalert.confirm_delete.confirm_button_text', 'Yes, remove it');

        expect(renderAlertView())->toContain('Yes, remove it');
    });

    it('lets a flashed confirmDelete override the defaults for that request', function () {
        session()->flush();
        Alert::confirmDelete('Delete invoice #42?', 'You cannot undo this.');

        expect(renderAlertView())->toContain('Delete invoice #42?');
    });

    it('can be turned off', function () {
        session()->flush();
        config()->set('sweetalert.confirm.auto', false);

        expect(renderAlertView())->not->toContain('data-confirm-delete');
    });

    it('falls back to the browser confirm if SweetAlert2 cannot be reached', function () {
        session()->flush();

        expect(renderAlertView())->toContain('window.confirm(');
    });

    it('sends no assets to fetch when the app loads its own SweetAlert2', function () {
        session()->flush();
        config()->set('sweetalert.never_load_js', true);

        expect(renderAlertView())->toContain("js: ''");
    });
});

describe('submitTo', function () {
    it('wires the answer up to a route', function () {
        session()->flush();
        Alert::input('What is your name?')->submitTo('https://example.test/names')->flash();

        // json_encode escapes the slashes in a URL, so match the host and the
        // wiring rather than the raw string.
        expect(renderAlertView())
            ->toContain('example.test')
            ->toContain('var submitTo =')
            ->toContain('result.isConfirmed')
            ->toContain('form.submit()');
    });

    it('does not pass submitTo on to SweetAlert2 as a config key', function () {
        session()->flush();
        Alert::input('Name')->submitTo('https://example.test/names')->flash();

        expect(renderAlertView())->not->toContain('"submitTo":{"url"');
    });

    it('adds a method override for anything that is not POST or GET', function () {
        session()->flush();
        Alert::warning('Publish?')->submitTo('https://example.test/posts/1', 'PUT')->flash();

        expect(renderAlertView())->toContain("hidden('_method', submitTo.method)")
            ->toContain('"method":"PUT"');

        expect(renderAlertView())->not->toContain('var submitTo =');
    });
});

/*
 * Livewire and plain-JS alerts arrive as browser events, not session flashes,
 * so the page they land on has nothing pending — which is exactly the page the
 * directive used to render nothing at all on. The bridge has to be there
 * regardless, and it has to be able to fetch SweetAlert2 by itself.
 */
describe('the event bridge', function () {
    it('is present on a page with no alert', function () {
        session()->flush();

        expect(renderAlertView())
            ->toContain("addEventListener('livewire:init'")
            ->toContain("Livewire.on('sweetalert'")
            ->toContain("addEventListener('sweetalert'");
    });

    it('is present even when the confirm listener is switched off', function () {
        session()->flush();
        config()->set('sweetalert.confirm.auto', false);

        expect(renderAlertView())
            ->toContain("Livewire.on('sweetalert'")
            ->and(renderAlertView())->not->toContain('data-confirm-delete');
    });

    it('ships no CSRF token when the confirm listener is switched off', function () {
        session()->flush();
        config()->set('sweetalert.confirm.auto', false);

        expect(renderAlertView())->not->toContain('var TOKEN');
    });

    it('does ship one when the confirm listener is on, since it submits forms', function () {
        session()->flush();

        expect(renderAlertView())->toContain('var TOKEN');
    });

    it('can fetch SweetAlert2 on demand', function () {
        session()->flush();

        expect(renderAlertView())
            ->toContain('function ensureSwal')
            ->toContain('sweetalert2');
    });

    it('asks for no assets when the app loads SweetAlert2 itself', function () {
        session()->flush();
        config()->set('sweetalert.never_load_js', true);

        expect(renderAlertView())->toContain("js: ''");
    });
});

/*
 * preConfirmRoute() posts the value to a route while the dialog is still open,
 * so the server can reject it and the person can correct it without losing the
 * dialog. Verified in a browser both ways: a rejection keeps the dialog open
 * and shows the server's message, an acceptance closes it.
 */
describe('preConfirmRoute', function () {
    it('wires the route into a preConfirm handler', function () {
        session()->flush();
        Alert::input('Pick one')->preConfirmRoute('https://example.test/check')->flash();

        $html = renderAlertView();

        expect($html)
            ->toContain('swalConfig.preConfirm')
            ->toContain('example.test')
            ->toContain('showValidationMessage');
    });

    it('does not leave the route in the config it hands to SweetAlert2', function () {
        session()->flush();
        Alert::input('Pick one')->preConfirmRoute('https://example.test/check')->flash();

        expect(renderAlertView())->toContain('delete swalConfig.preConfirmRoute');
    });

    it('sends the CSRF token with the check', function () {
        session()->flush();
        Alert::input('Pick one')->preConfirmRoute('https://example.test/check')->flash();

        expect(renderAlertView())->toContain('X-CSRF-TOKEN');
    });

    it('does the same for preDenyRoute', function () {
        session()->flush();
        Alert::warning('Sure?')->preDenyRoute('https://example.test/deny')->flash();

        expect(renderAlertView())
            ->toContain('swalConfig.preDeny')
            ->toContain('delete swalConfig.preDenyRoute');
    });
});

/*
 * Every theme the docs advertise, checked against the URL the view builds.
 * All seven were confirmed rendering in a browser; these keep the mapping
 * honest if the CDN layout or the theme list ever changes.
 */
describe('themes', function () {
    it('builds the right stylesheet URL for a per-alert theme', function (string $theme) {
        session()->flush();
        Alert::info('Themed')->theme($theme)->flash();

        expect(renderAlertView())->toContain("theme-{$theme}/{$theme}.min.css");
    })->with(['dark', 'borderless', 'minimal', 'material-ui', 'bootstrap-4', 'wordpress-admin']);

    it('loads no theme stylesheet for the default look', function (string $theme) {
        session()->flush();
        Alert::info('Plain')->theme($theme)->flash();

        expect(renderAlertView())->not->toContain('@sweetalert2/theme-');
    })->with(['light', 'default', '']);

    it('uses the global theme for the base stylesheet', function () {
        session()->flush();
        config()->set('sweetalert.theme', 'minimal');
        Alert::info('Global');

        expect(renderAlertView())->toContain('theme-minimal/minimal.min.css');
    });
});

/*
 * Found by clicking through the awkward shapes a real template throws at this:
 * a click landing on a child element, a link opening in a new tab, a guarded
 * button inside an unguarded form, a GET action, a modifier click.
 */
describe('the guarded-action submitter', function () {
    /*
     * The DOM lowercases form.method, so comparing it to 'GET' never matched
     * and a GET action had the CSRF token appended to its URL — where it would
     * be kept in browser history, sent in Referer headers and written to logs.
     */
    it('never puts the CSRF token in a URL', function () {
        session()->flush();

        $html = renderAlertView();

        expect($html)->toContain('var isGet =')
            ->and($html)->toContain('if (! isGet) {')
            ->and($html)->not->toContain("form.method !== 'GET'");
    });

    it('keeps the token for everything that is not a GET', function () {
        session()->flush();

        expect(renderAlertView())->toContain("hidden('_token', TOKEN)");
    });

    it('honours a link that asked to open in a new tab', function () {
        session()->flush();

        expect(renderAlertView())
            ->toContain('form.target = target')
            ->toContain("el.getAttribute('target')");
    });

    /*
     * requestSubmit(button) rather than form.submit(), so a submit button's
     * own name and value reach the server — form.submit() drops them.
     */
    it('submits a guarded button through the form so its name and value survive', function () {
        session()->flush();

        expect(renderAlertView())->toContain('form.requestSubmit(');
    });

    it('finds the guarded element from whatever child was clicked', function () {
        session()->flush();

        expect(renderAlertView())->toContain('event.target.closest(SELECTOR)');
    });

    it('does nothing for a guarded element with no href and no form', function () {
        session()->flush();

        expect(renderAlertView())->toContain('if (! href && ! form)');
    });
});
