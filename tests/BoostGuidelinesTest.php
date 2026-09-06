<?php

use Illuminate\Support\Facades\Blade;
use RealRashid\SweetAlert\Builders\AlertBuilder;

/*
 * The Boost guideline is a Blade file, so anything in its examples that Blade
 * recognises gets executed rather than shown. v7.3.1 had to ship a fix for
 * exactly this (#190), and writing the v8 guideline reintroduced it twice — an
 * unescaped @csrf rendered a real hidden token field into the document, and an
 * unescaped @sweetAlert in a sentence injected the whole runtime script.
 *
 * These render the file the way Boost does and assert the output is prose.
 */
function guidelinePath(): string
{
    return __DIR__.'/../resources/boost/guidelines/core.blade.php';
}

function renderGuideline(): string
{
    return Blade::render(file_get_contents(guidelinePath()), []);
}

describe('the Boost guideline', function () {
    it('exists where Boost looks for it', function () {
        expect(file_exists(guidelinePath()))->toBeTrue();
    });

    it('compiles without executing any directive', function () {
        $rendered = renderGuideline();

        expect($rendered)
            ->not->toContain('<script')
            ->and($rendered)->not->toContain('name="_token"')
            ->and($rendered)->not->toContain('data-confirm-delete], [data-confirm');
    });

    it('keeps its examples intact', function () {
        $rendered = renderGuideline();

        expect($rendered)
            ->toContain('@sweetAlert')
            ->and($rendered)->toContain('@csrf')
            ->and($rendered)->toContain('{{ route(');
    });

    it('leaves no escape markers behind', function () {
        expect(renderGuideline())->not->toContain('@@');
    });

    it('strips its own authoring notes', function () {
        expect(renderGuideline())->not->toContain('Laravel Boost guideline for');
    });

    /*
     * An AI agent following this file writes code against it, so the API it
     * describes has to be the API that exists.
     */
    it('only describes methods the package actually has', function (string $method) {
        expect(renderGuideline())->toContain($method.'(');
        expect(method_exists(AlertBuilder::class, $method))->toBeTrue();
    })->with(['success', 'error', 'warning', 'info', 'question', 'title', 'html', 'view', 'toast', 'input', 'flash', 'submitTo', 'timer', 'confirmDelete']);

    it('names publish tags the service provider actually registers', function (string $tag) {
        expect(renderGuideline())->toContain($tag);

        $provider = file_get_contents(__DIR__.'/../src/SweetAlertServiceProvider.php');

        expect($provider)->toContain("'{$tag}'");
    })->with(['sweetalert-config', 'sweetalert-views', 'sweetalert-asset']);

    it('names commands the package actually registers', function (string $command) {
        expect(renderGuideline())->toContain($command);

        $provider = file_get_contents(__DIR__.'/../src/SweetAlertServiceProvider.php');
        $registered = str_contains($provider, 'InstallCommand')
            && str_contains($provider, 'PublishCommand')
            && str_contains($provider, 'UpgradeCommand');

        expect($registered)->toBeTrue();
    })->with(['alert:install', 'alert:publish', 'alert:upgrade']);
});

describe('the Boost skill', function () {
    it('exists with the frontmatter Boost expects', function () {
        $path = __DIR__.'/../resources/boost/skills/sweet-alert-development/SKILL.md';

        expect(file_exists($path))->toBeTrue();

        $contents = file_get_contents($path);

        expect($contents)->toStartWith('---')
            ->and($contents)->toContain('name: sweet-alert-development')
            ->and($contents)->toContain('description:');
    });

    it('does not still describe the v7 API', function () {
        $contents = file_get_contents(__DIR__.'/../resources/boost/skills/sweet-alert-development/SKILL.md');

        expect($contents)
            ->not->toContain('sweetalert.all.js')
            ->and($contents)->not->toContain('sweetalert-view"')
            ->and($contents)->not->toContain('alwaysLoadJS');
    });
});
