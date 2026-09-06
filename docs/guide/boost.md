# Laravel Boost

[Laravel Boost](https://github.com/laravel/boost) gives AI coding assistants
accurate, project-specific context. This package ships its own guideline and
skill, so an assistant working in your codebase writes v8 code instead of
guessing — or worse, reproducing v7 patterns it learned from older tutorials.

## What ships

| File | What it does |
|---|---|
| `resources/boost/guidelines/core.blade.php` | The package guideline Boost injects into the assistant's context |
| `resources/boost/skills/sweet-alert-development/SKILL.md` | A skill for building alert flows — toasts, inputs, guarded actions |

You do not install them separately. Boost discovers guidelines and skills from
the packages already in your `composer.json`:

```bash
php artisan boost:install
```

## Why it matters here

Three v8 changes fail *quietly* rather than loudly, which is exactly the kind of
thing an assistant gets wrong:

- `html()` takes one argument now. A v7-style `html($title, $code, $icon)` call
  does not error, because PHP ignores surplus arguments — it simply renders
  nothing.
- `view()` takes the view name first; v7 took the title first.
- `Alert::input()` and `Alert::make()` never flash themselves, so a chain that
  forgets `flash()` shows no alert at all.

The guideline calls all three out, along with the guarded-action attributes and
`submitTo()`, so generated code lands on the v8 API.

## Upgrading an existing project

If the assistant is working on a v7 codebase, point it at the migration command
rather than letting it rewrite call sites by hand:

```bash
php artisan alert:upgrade --dry-run
```

See the [Upgrade Guide](/guide/upgrade-guide) for what it covers.

## A note on editing the guideline

`core.blade.php` is compiled by Blade before it reaches the assistant, so
anything Blade recognises inside an example has to be escaped — `{{ }}` written
as `@{{ }}`, and directives as `@@sweetAlert`, `@@csrf` and so on. An unescaped
directive is *executed*, which is how a guideline can end up shipping a rendered
CSRF token instead of the code you meant to show.

The package's test suite renders the guideline the way Boost does and asserts
the output is prose, so this cannot regress unnoticed.
