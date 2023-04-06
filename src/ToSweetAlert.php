<?php

namespace RealRashid\SweetAlert;

use Closure;

class ToSweetAlert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function handle($request, Closure $next)
    {
        $messageTypes = [
            'info',
            'success',
            'warning',
            'error',
            'question',
        ];

        foreach ($messageTypes as $message) {
            if ($request->session()->has($message)) {
                alert()->{$message}(
                    is_array($request->session()->get($message))
                        ? $request->session()->get($message)[0] // if array is passed, put the 1st param as a title
                        : $request->session()->get($message)    // else put the whole value as title
                    ,
                    is_array($request->session()->get($message))
                        ? $request->session()->get($message)[1] // if array is passed, put the 2st param as a description
                        : null                                   // else put nothing as description
                );
            }
        }

        if ($request->session()->has('errors') && config('sweetalert.middleware.auto_display_error_messages')) {
            $error = $request->session()->get('errors');

            if (!is_string($error)) {
                $error = $this->getErrors($error->getMessages());
            }

            alert()->error($error);
        }

        if ($request->session()->has('toast_success')) {
            alert()->toast($request->session()->get('toast_success'), 'success')->middleware();
        }

        if ($request->session()->has('toast_info')) {
            toast($request->session()->get('toast_info'), 'info')->middleware();
        }

        if ($request->session()->has('toast_warning')) {
            toast($request->session()->get('toast_warning'), 'warning')->middleware();
        }

        if ($request->session()->has('toast_question')) {
            toast($request->session()->get('toast_question'), 'question')->middleware();
        }

        if ($request->session()->has('toast_error')) {
            toast($request->session()->get('toast_error'), 'error')->middleware();
        }

        return $next($request);
    }

    /**
     * Get the validation errors
     *
     * @param object $errors
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    private function getErrors($errors)
    {
        $errors = collect($errors);
        return $errors->flatten()->implode('<br />');
    }
}
