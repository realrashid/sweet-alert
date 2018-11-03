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
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('success')) {
            alert()->success($request->session()->get('success'));
        }

        if ($request->session()->has('info')) {
            alert()->info($request->session()->get('info'));
        }

        if ($request->session()->has('warning')) {
            alert()->warning($request->session()->get('warning'));
        }

        if ($request->session()->has('question')) {
            alert()->question($request->session()->get('question'));
        }

        if ($request->session()->has('info')) {
            alert()->info($request->session()->get('info'));
        }

        if ($request->session()->has('error')) {
            $error = $request->session()->get('error');

            if (!is_string($error)) {
                $error = $this->getErrors($error->getMessages());
            }

            alert()->error($error)->toHtml();
        }

        if ($request->session()->has('toast_success')) {
            alert()->toast($request->session()->get('toast_success'), 'success');
        }

        if ($request->session()->has('toast_info')) {
            toast($request->session()->get('toast_info'), 'info');
        }

        if ($request->session()->has('toast_warning')) {
            toast($request->session()->get('toast_warning'), 'warning');
        }

        if ($request->session()->has('toast_question')) {
            toast($request->session()->get('toast_question'), 'question');
        }

        if ($request->session()->has('toast_error')) {
            toast($request->session()->get('toast_error'), 'error');
        }

        return $next($request);
    }

    /**
     *
     *
     * @param $errors
     *
     * @return string
     */
    private function getErrors($errors)
    {
        $errors = collect($errors);

        return $errors->flatten()->implode('<br />');
    }
}
