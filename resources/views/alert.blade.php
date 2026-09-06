@php
    $swalVersion     = config('sweetalert.js.version', '11');
    $swalCdnProvider = config('sweetalert.js.cdn_provider', 'jsdelivr');
    $globalTheme     = config('sweetalert.theme', 'light');
    $swalNeverLoadJs = config('sweetalert.never_load_js') === true;

    // Use the JS-only build so our own CSS <link> is not overridden by
    // the bundled styles that sweetalert2.all.min.js injects dynamically.
    $swalCdnJs = match($swalCdnProvider) {
        'unpkg'  => "https://unpkg.com/sweetalert2@{$swalVersion}/dist/sweetalert2.min.js",
        'cdnjs'  => "https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/{$swalVersion}/sweetalert2.min.js",
        'custom' => config('sweetalert.js.custom_cdn_js'),
        default  => "https://cdn.jsdelivr.net/npm/sweetalert2@{$swalVersion}/dist/sweetalert2.min.js",
    };

    $swalJsSrc = config('sweetalert.cdn') ?: $swalCdnJs;

    // Base CSS: default SweetAlert2 styles unless a global theme is set.
    $swalCdnCss = in_array($globalTheme, ['light', 'default', ''])
        ? "https://cdn.jsdelivr.net/npm/sweetalert2@{$swalVersion}/dist/sweetalert2.min.css"
        : "https://cdn.jsdelivr.net/npm/@sweetalert2/theme-{$globalTheme}/{$globalTheme}.min.css";
@endphp

@if (config('sweetalert.always_load_js') === true || Session::has('alert.config') || Session::has('alert.delete'))

    @unless ($swalNeverLoadJs)
        {{-- Animation CSS --}}
        @if (config('sweetalert.animation.enabled'))
            <link rel="stylesheet" href="{{ config('sweetalert.animation.animatecss') ?: 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css' }}">
        @endif

        {{-- SweetAlert2 CSS (default or global theme) --}}
        <link id="swal2-base-css" rel="stylesheet" href="{{ $swalCdnCss }}">

        {{-- SweetAlert2 JS (no bundled CSS) --}}
        <script src="{{ $swalJsSrc }}"></script>
        <script src="{{ asset('vendor/sweetalert/sweet-alert.js') }}" defer></script>
    @endunless

    @if (Session::has('alert.config'))
        @php
            $alertData   = json_decode(Session::pull('alert.config'), true);
            $alertConfig = $alertData['config'] ?? $alertData;
            $alertTheme  = $alertConfig['theme'] ?? null;
            $alertSubmit = $alertConfig['submitTo'] ?? null;
            unset($alertConfig['theme'], $alertConfig['submitTo']);
        @endphp

        <script>
            (function() {
                var swalConfig = {!! json_encode($alertConfig) !!};

                @if (isset($alertConfig['preConfirmRoute']))
                    var preConfirmRoute = '{{ $alertConfig['preConfirmRoute'] }}';
                    delete swalConfig.preConfirmRoute;

                    swalConfig.preConfirm = function(value) {
                        return fetch(preConfirmRoute, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ value: value })
                        }).then(function(response) {
                            return response.json();
                        }).then(function(data) {
                            if (data.valid === false) {
                                Swal.showValidationMessage(data.message || 'Validation failed');
                            }
                            return data;
                        }).catch(function() {
                            Swal.showValidationMessage('Request failed');
                        });
                    };
                @endif

                @if (isset($alertConfig['preDenyRoute']))
                    var preDenyRoute = '{{ $alertConfig['preDenyRoute'] }}';
                    delete swalConfig.preDenyRoute;

                    swalConfig.preDeny = function(value) {
                        return fetch(preDenyRoute, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ value: value })
                        }).then(function(response) {
                            return response.json();
                        }).then(function(data) {
                            if (data.valid === false) {
                                Swal.showValidationMessage(data.message || 'Validation failed');
                            }
                            return data;
                        }).catch(function() {
                            Swal.showValidationMessage('Request failed');
                        });
                    };
                @endif

                @if (isset($alertConfig['inputValidatorMessage']))
                    var validatorMsg = '{{ $alertConfig['inputValidatorMessage'] }}';
                    delete swalConfig.inputValidatorMessage;

                    swalConfig.inputValidator = function(value) {
                        if (!value) {
                            return validatorMsg;
                        }
                    };
                @endif

                function fire() {
                    var answered = Swal.fire(swalConfig);

                    @if ($alertSubmit)
                        {{-- submitTo(): post what the user chose or typed to a route,
                             so the answer reaches the server instead of being lost
                             the moment the dialog closes. --}}
                        var submitTo = {!! json_encode($alertSubmit) !!};

                        answered.then(function(result) {
                            if (! result.isConfirmed) {
                                return;
                            }

                            /*
                             * Compare against the method we were given, not
                             * form.method — the DOM lowercases that, so a
                             * 'GET' check against it never matches and the
                             * CSRF token ends up in the query string.
                             */
                            var isGet = submitTo.method === 'GET';

                            var form = document.createElement('form');
                            form.action = submitTo.url;
                            form.method = isGet ? 'GET' : 'POST';
                            form.style.display = 'none';

                            function hidden(name, value) {
                                var input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = name;
                                input.value = value === undefined || value === null ? '' : value;
                                form.appendChild(input);
                            }

                            if (! isGet) {
                                hidden('_token', '{{ csrf_token() }}');

                                if (submitTo.method !== 'POST') {
                                    hidden('_method', submitTo.method);
                                }
                            }

                            if (result.value !== undefined && result.value !== true) {
                                hidden(submitTo.field, result.value);
                            }

                            document.body.appendChild(form);
                            form.submit();
                        });
                    @endif
                }

                @if ($alertTheme && !in_array($alertTheme, ['light', 'default', '']))
                    {{-- Per-alert theme: inject CSS, then fire once loaded --}}
                    var _link = document.createElement('link');
                    _link.rel  = 'stylesheet';
                    _link.href = 'https://cdn.jsdelivr.net/npm/@sweetalert2/theme-{{ $alertTheme }}/{{ $alertTheme }}.min.css';
                    _link.onload  = fire;
                    _link.onerror = fire;

                    // The theme only wins if it comes after the base stylesheet in
                    // document order. This directive usually sits at the end of the
                    // body, so appending to <head> would put the theme first and
                    // the base sheet would override it.
                    var _base = document.getElementById('swal2-base-css');

                    if (_base && _base.parentNode) {
                        _base.parentNode.insertBefore(_link, _base.nextSibling);
                    } else {
                        (document.body || document.head).appendChild(_link);
                    }
                @else
                    fire();
                @endif
            })();
        </script>
    @endif
@endif

@php
        $confirmAuto = config('sweetalert.confirm.auto', true);

        // A flashed confirmDelete() customises the dialog for this request; the
        // config supplies the defaults for every other page load. Before v8 the
        // listener only existed on the flashed request, so a guarded link on any
        // other render was an ordinary link that opened the URL unconfirmed.
        $flashedDelete = $confirmAuto && Session::has('alert.delete')
            ? (json_decode(Session::pull('alert.delete'), true)['config'] ?? [])
            : [];

        $confirmDefaults = array_filter([
            'icon' => config('sweetalert.confirm.icon', 'question'),
            'title' => config('sweetalert.confirm.title', 'Are you sure?'),
            'text' => config('sweetalert.confirm.text', ''),
            'showCancelButton' => config('sweetalert.confirm.show_cancel_button', true),
            'showCloseButton' => config('sweetalert.confirm.show_close_button', false),
            'confirmButtonText' => __(config('sweetalert.confirm.confirm_button_text', 'Yes')),
            'confirmButtonColor' => config('sweetalert.confirm.confirm_button_color', '#3085d6'),
            'cancelButtonText' => __(config('sweetalert.confirm.cancel_button_text', 'Cancel')),
        ], fn ($value) => $value !== null && $value !== '');

        $deleteDefaults = array_merge(array_filter([
            'icon' => config('sweetalert.confirm_delete.icon', 'warning'),
            'title' => config('sweetalert.confirm_delete.title', 'Are you sure?'),
            'text' => config('sweetalert.confirm_delete.text', ''),
            'showCancelButton' => config('sweetalert.confirm_delete.show_cancel_button', true),
            'showCloseButton' => config('sweetalert.confirm_delete.show_close_button', false),
            'confirmButtonText' => __(config('sweetalert.confirm_delete.confirm_button_text', 'Yes, delete it!')),
            'confirmButtonColor' => config('sweetalert.confirm_delete.confirm_button_color', '#d33'),
            'cancelButtonText' => __(config('sweetalert.confirm_delete.cancel_button_text', 'Cancel')),
        ], fn ($value) => $value !== null && $value !== ''), $flashedDelete);
    @endphp

    <script>
        (function() {
            var ASSETS = {
                js: '{{ $swalNeverLoadJs ? '' : $swalJsSrc }}',
                css: '{{ $swalNeverLoadJs ? '' : $swalCdnCss }}'
            };

            var loading = null;

            /**
             * A page that only has a guarded link loads no SweetAlert2 at all, so
             * it is fetched on the first click rather than on every page view.
             */
            function ensureSwal() {
                if (typeof window.Swal !== 'undefined' || ! ASSETS.js) {
                    return Promise.resolve();
                }

                if (loading) {
                    return loading;
                }

                loading = new Promise(function(resolve) {
                    if (ASSETS.css && ! document.getElementById('swal2-base-css')) {
                        var css = document.createElement('link');
                        css.id = 'swal2-base-css';
                        css.rel = 'stylesheet';
                        css.href = ASSETS.css;
                        (document.head || document.body).appendChild(css);
                    }

                    var js = document.createElement('script');
                    js.src = ASSETS.js;
                    js.onload = resolve;
                    js.onerror = resolve;
                    (document.body || document.head).appendChild(js);
                });

                return loading;
            }

            /**
             * Show a config that arrived from JavaScript rather than the session.
             */
            function fireConfig(config) {
                ensureSwal().then(function() {
                    if (typeof window.Swal !== 'undefined') {
                        Swal.fire(config);
                    }
                });
            }

            /*
             * Livewire updates the DOM without a page load, so a Livewire alert
             * arrives as a browser event instead of a session flash. Nothing was
             * flashed, which means the assets were never loaded either — hence
             * the lazy load above, rather than assuming Swal is already there.
             */
            document.addEventListener('livewire:init', function() {
                if (typeof Livewire === 'undefined') {
                    return;
                }

                Livewire.on('sweetalert', function(event) {
                    var payload = Array.isArray(event) ? event[0] : event;

                    if (payload && payload.config) {
                        fireConfig(payload.config);
                    }
                });
            });

            // The same door for Alpine, Vue, React or plain JS:
            //   window.dispatchEvent(new CustomEvent('sweetalert', { detail: { config: {...} } }))
            window.addEventListener('sweetalert', function(event) {
                var payload = event.detail || {};

                if (payload.config) {
                    fireConfig(payload.config);
                }
            });

            @if ($confirmAuto)
            var CONFIRM = {!! json_encode($confirmDefaults) !!};
            var DELETE  = {!! json_encode($deleteDefaults) !!};
            var TOKEN   = '{{ csrf_token() }}';

            var SELECTOR = '[data-confirm-delete], [data-confirm]';
            var FORM_SELECTOR = 'form[data-confirm-delete], form[data-confirm]';
            var CONFIRMED = '__sweetAlertConfirmed';

            function attr(el, name) {
                return el.getAttribute('data-confirm-' + name);
            }

            function configFor(el, isDelete) {
                var config = {};
                var defaults = isDelete ? DELETE : CONFIRM;

                for (var key in defaults) {
                    if (Object.prototype.hasOwnProperty.call(defaults, key)) {
                        config[key] = defaults[key];
                    }
                }

                if (attr(el, 'title'))  { config.title = attr(el, 'title'); }
                if (attr(el, 'text'))   { config.text = attr(el, 'text'); }
                if (attr(el, 'icon'))   { config.icon = attr(el, 'icon'); }
                if (attr(el, 'button')) { config.confirmButtonText = attr(el, 'button'); }
                if (attr(el, 'cancel')) { config.cancelButtonText = attr(el, 'cancel'); }

                return config;
            }

            function methodFor(el, isDelete) {
                var method = attr(el, 'method');

                return method ? method.toUpperCase() : (isDelete ? 'DELETE' : 'POST');
            }

            /**
             * Resolves true only when the person said yes. If SweetAlert2 cannot
             * be reached the browser's own confirm() is used — a guarded action
             * must never go through unconfirmed just because a CDN was down.
             */
            function ask(el, isDelete) {
                return ensureSwal().then(function() {
                    var config = configFor(el, isDelete);

                    if (typeof window.Swal === 'undefined') {
                        return window.confirm(config.title || 'Are you sure?');
                    }

                    return Swal.fire(config).then(function(result) {
                        return !! result.isConfirmed;
                    });
                });
            }

            function sendTo(url, method, target) {
                // Against the requested method, not form.method: the DOM
                // lowercases that, so comparing it to 'GET' never matches and
                // the CSRF token would be appended to the URL.
                var isGet = method === 'GET';

                var form = document.createElement('form');
                form.action = url;
                form.method = isGet ? 'GET' : 'POST';
                form.style.display = 'none';

                // A link that asked to open in a new tab still should.
                if (target) {
                    form.target = target;
                }

                function hidden(name, value) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    form.appendChild(input);
                }

                if (! isGet) {
                    hidden('_token', TOKEN);

                    if (method !== 'POST') {
                        hidden('_method', method);
                    }
                }

                document.body.appendChild(form);
                form.submit();
            }

            document.addEventListener('click', function(event) {
                var el = event.target.closest(SELECTOR);

                if (! el || el.tagName === 'FORM') {
                    return;
                }

                // A guarded form is handled by the submit listener instead, so a
                // button inside one is not asked about twice.
                if (el.closest(FORM_SELECTOR)) {
                    return;
                }

                var href = el.getAttribute('href');
                var form = el.form || null;

                if (! href && ! form) {
                    return;
                }

                event.preventDefault();

                var isDelete = el.hasAttribute('data-confirm-delete');

                ask(el, isDelete).then(function(confirmed) {
                    if (! confirmed) {
                        return;
                    }

                    if (href) {
                        sendTo(href, methodFor(el, isDelete), el.getAttribute('target'));
                    } else if (form.requestSubmit) {
                        form.requestSubmit(el.type === 'submit' ? el : undefined);
                    } else {
                        form.submit();
                    }
                });
            });

            document.addEventListener('submit', function(event) {
                var form = event.target;

                if (! form.matches || ! form.matches(FORM_SELECTOR)) {
                    return;
                }

                if (form[CONFIRMED]) {
                    form[CONFIRMED] = false;

                    return;
                }

                event.preventDefault();

                ask(form, form.hasAttribute('data-confirm-delete')).then(function(confirmed) {
                    if (! confirmed) {
                        return;
                    }

                    form[CONFIRMED] = true;

                    if (form.requestSubmit) {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                });
            });
            @endif
        })();
    </script>
