<?php

use RealRashid\SweetAlert\Support\Upgrader;

/*
 * This code edits other people's applications, so the tests are written the
 * hostile way round: as much attention on what it must NOT touch as on what
 * it rewrites. A missed rewrite is an inconvenience; a wrong one is a bug in
 * someone else's codebase that they did not write.
 */

function php(string $body): string
{
    return "<?php\n\n".$body."\n";
}

function upgraded(string $body): string
{
    return (new Upgrader)->upgradePhp(php($body));
}

describe('html() signature', function () {
    it('moves the title out of a two-argument call', function () {
        expect(upgraded("alert()->html('Order shipped', '<b>#42</b>');"))
            ->toContain("alert()->title('Order shipped')->html('<b>#42</b>')");
    });

    it('carries the icon across from a three-argument call', function () {
        expect(upgraded("alert()->html('Title', '<b>Body</b>', 'success');"))
            ->toContain("alert()->title('Title')->html('<b>Body</b>')->icon('success')");
    });

    it('drops an empty icon rather than emitting icon(\'\')', function () {
        expect(upgraded("alert()->html('Title', '<b>Body</b>', '');"))
            ->toContain("alert()->title('Title')->html('<b>Body</b>')->flash();")
            ->not->toContain('icon(');
    });

    it('leaves an already-migrated single-argument call alone', function () {
        $body = "Alert::title('Hi')->html('<b>Body</b>')->flash();";

        expect(upgraded($body))->toContain($body);
    });

    it('is not fooled by a comma inside a string argument', function () {
        expect(upgraded("alert()->html('Hello, world', '<b>a, b, c</b>');"))
            ->toContain("->title('Hello, world')->html('<b>a, b, c</b>')");
    });

    it('is not fooled by a nested call in an argument', function () {
        expect(upgraded("alert()->html(__('msg.title'), view('parts.body', ['a' => 1])->render());"))
            ->toContain("->title(__('msg.title'))->html(view('parts.body', ['a' => 1])->render())");
    });

    it('handles a call split over several lines', function () {
        $out = upgraded("alert()->html(\n    'Title',\n    '<b>Body</b>'\n);");

        expect($out)->toContain("->title('Title')->html('<b>Body</b>')");
    });

    it('rewrites every call in a file, not just the first', function () {
        $out = upgraded("alert()->html('One', '<b>1</b>');\nalert()->html('Two', '<b>2</b>');");

        expect($out)->toContain("->title('One')->html('<b>1</b>')")
            ->and($out)->toContain("->title('Two')->html('<b>2</b>')");
    });
});

describe('what it refuses to touch', function () {
    it('leaves an unrelated object with an html() method alone', function () {
        $body = "\$mailable->html('Subject line', '<p>Body</p>');";

        expect(upgraded($body))->toContain($body);
    });

    it('reports the unrelated call as a warning instead of rewriting it', function () {
        $upgrader = new Upgrader;
        $upgrader->upgradePhp(php("\$pdf->html('Title', '<p>Body</p>');"));

        $warnings = array_filter($upgrader->collect(), fn ($c) => ! $c->applied);

        expect($warnings)->toHaveCount(1)
            ->and(reset($warnings)->rule)->toBe('html-signature');
    });

    it('does not rewrite html() inside a string', function () {
        $body = "\$note = \"call alert()->html('a', 'b') to fix\";";

        expect(upgraded($body))->toContain($body);
    });

    it('does not rewrite html() inside a comment', function () {
        $body = "// alert()->html('a', 'b');\n\$x = 1;";

        expect(upgraded($body))->toContain("// alert()->html('a', 'b');");
    });
});

describe('view() signature', function () {
    it('moves the title out of the v7 call', function () {
        expect(upgraded("alert()->view('Invoice', 'invoices.summary', ['id' => 1]);"))
            ->toContain("->title('Invoice')->view('invoices.summary', ['id' => 1])");
    });

    it('leaves the v8 call alone, because the second argument is an array', function () {
        $body = "Alert::title('Invoice')->view('invoices.summary', ['id' => 1])->flash();";

        expect(upgraded($body))->toContain($body);
    });

    it('carries the v7 icon across from the fifth argument', function () {
        expect(upgraded("alert()->view('T', 'v.name', [], [], 'success');"))
            ->toContain("->title('T')->view('v.name', [], [])->icon('success')");
    });
});

describe('container binding and moved classes', function () {
    it('rewrites the sweet-alert binding', function () {
        expect(upgraded("\$a = app('sweet-alert');"))->toContain("app('alert')");
    });

    it('rewrites the binding written with double quotes', function () {
        expect(upgraded('$a = app("sweet-alert");'))->toContain("app('alert')");
    });

    it('points the old SessionStore at the contract', function () {
        expect(upgraded('use RealRashid\SweetAlert\Storage\SessionStore;'))
            ->toContain('RealRashid\SweetAlert\Contracts\SessionStoreInterface');
    });
});

describe('warnings for what cannot be rewritten', function () {
    it('warns about the removed Toaster class', function () {
        $upgrader = new Upgrader;
        $upgrader->upgradePhp(php('use RealRashid\SweetAlert\Toaster;'));

        expect(collect($upgrader->collect())->pluck('rule'))->toContain('removed-class');
    });

    it('leaves the Toaster reference in place for the developer to decide', function () {
        expect(upgraded('use RealRashid\SweetAlert\Toaster;'))
            ->toContain('use RealRashid\SweetAlert\Toaster;');
    });

    it('warns about buildConfig()', function () {
        $upgrader = new Upgrader;
        $upgrader->upgradePhp(php('$c = alert()->buildConfig();'));

        expect(collect($upgrader->collect())->pluck('rule'))->toContain('removed-method');
    });
});

describe('blade', function () {
    it('swaps the old include for the directive', function () {
        $upgrader = new Upgrader;

        expect($upgrader->upgradeBlade("<body>\n@include('sweetalert::alert')\n</body>"))
            ->toContain('@sweetAlert')
            ->not->toContain('sweetalert::alert');
    });

    it('leaves other includes alone', function () {
        $upgrader = new Upgrader;
        $blade = "@include('partials.header')";

        expect($upgrader->upgradeBlade($blade))->toBe($blade);
    });
});

describe('config', function () {
    /*
     * The important one: a config published under v7 still pins background to
     * #fff, which SweetAlert2 applies as an inline style, which beats every
     * theme stylesheet. Upgrading the package alone does not fix it.
     */
    it('nulls the three defaults that break theming', function () {
        $upgrader = new Upgrader;

        $config = <<<'PHP'
        <?php
        return [
            'width' => env('SWEET_ALERT_WIDTH', '32rem'),
            'padding' => env('SWEET_ALERT_PADDING', '1.25rem'),
            'background' => env('SWEET_ALERT_BACKGROUND', '#fff'),
        ];
        PHP;

        $out = $upgrader->upgradeConfig($config);

        expect($out)->toContain("env('SWEET_ALERT_WIDTH')")
            ->and($out)->toContain("env('SWEET_ALERT_PADDING')")
            ->and($out)->toContain("env('SWEET_ALERT_BACKGROUND')")
            ->and($out)->not->toContain("'#fff'")
            ->and($upgrader->collect())->toHaveCount(3);
    });

    it('leaves other config values alone', function () {
        $upgrader = new Upgrader;
        $config = "<?php\nreturn ['timer' => env('SWEET_ALERT_TIMER', 5000)];";

        expect($upgrader->upgradeConfig($config))->toBe($config);
    });
});

describe('output is still valid PHP', function () {
    it('produces parseable code for every rewrite rule', function (string $body) {
        $out = upgraded($body);

        expect(fn () => token_get_all($out, TOKEN_PARSE))->not->toThrow(ParseError::class);
    })->with([
        "alert()->html('T', '<b>B</b>');",
        "alert()->html('T', '<b>B</b>', 'success');",
        "alert()->view('T', 'v.name', ['a' => 1]);",
        "alert()->html(__('a'), view('v', ['x' => [1, 2]])->render(), 'info');",
        "\$a = app('sweet-alert');",
    ]);
});

/*
 * The subtlest part of the migration. v7's html() and view() flashed the alert
 * themselves, so `alert()->html(...)` displayed something. In v8 they are plain
 * setters and `alert()` with no title does not flash — so a rewrite that only
 * fixed the argument order would turn a working alert into a silent no-op.
 */
describe('display behaviour survives the rewrite', function () {
    it('adds flash() so the migrated call still shows something', function () {
        expect(upgraded("alert()->html('Title', '<b>Body</b>');"))
            ->toContain("alert()->title('Title')->html('<b>Body</b>')->flash();");
    });

    it('adds flash() to a migrated view() call', function () {
        expect(upgraded("alert()->view('T', 'v.name');"))
            ->toContain("->view('v.name')->flash();");
    });

    it('does not add a second flash() when the chain already has one', function () {
        $out = upgraded("alert()->html('Title', '<b>Body</b>')->flash();");

        expect(substr_count($out, 'flash()'))->toBe(1);
    });

    it('does not add flash() when the chain flashes further along', function () {
        $out = upgraded("alert()->html('Title', '<b>Body</b>')->persistent()->flash();");

        expect(substr_count($out, 'flash()'))->toBe(1)
            ->and($out)->toContain("->html('<b>Body</b>')->persistent()->flash();");
    });
});

/*
 * Found by running the command against a real application: the binding rule
 * was a plain search-and-replace, so it edited a sentence in an alert message
 * that merely mentioned the old binding name.
 */
describe('prose is not code', function () {
    it('does not rewrite the binding name inside a string', function () {
        $body = "alert()->success('Note', 'app(\"sweet-alert\") was the v7 binding.');";

        expect(upgraded($body))->toContain('app("sweet-alert") was the v7 binding.');
    });

    it('does not rewrite the binding name inside a comment', function () {
        $body = "// resolve it with app('sweet-alert')\n\$x = 1;";

        expect(upgraded($body))->toContain("// resolve it with app('sweet-alert')");
    });

    it('still rewrites a real binding call in the same file', function () {
        $out = upgraded("// see app('sweet-alert')\n\$a = app('sweet-alert');");

        expect($out)->toContain("// see app('sweet-alert')")
            ->and($out)->toContain("\$a = app('alert');");
    });

    it('does not warn about buildConfig mentioned in a comment', function () {
        $upgrader = new Upgrader;
        $upgrader->upgradePhp(php("// we used to call buildConfig() here\n\$x = 1;"));

        expect($upgrader->collect())->toBe([]);
    });
});
