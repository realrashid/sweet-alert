<?php

namespace RealRashid\SweetAlert\Support;

/**
 * Upgrader - Rewrites v7 call sites to their v8 equivalents.
 *
 * This edits application source code, so it is deliberately conservative:
 * it uses PHP's own tokeniser rather than pattern matching, it only rewrites
 * a call when it can prove the call belongs to this package, and anything it
 * cannot prove is reported as a warning instead of being changed.
 *
 * Every rule here maps to a documented v7 -> v8 break. If a break cannot be
 * rewritten mechanically — a `Toaster` type hint, a removed method — it is a
 * warning rule, never a silent edit.
 */
class Upgrader
{
    /**
     * Tokens that end the statement we scan backwards through when deciding
     * whether a `->html(...)` really belongs to an alert chain.
     */
    private const STATEMENT_BOUNDARIES = [';', '{', '}'];

    /**
     * Markers that prove a chain came from this package.
     */
    private const ALERT_MARKERS = [
        'alert', 'Alert', 'toast', 'Toast', 'sweetAlert', 'sweetToast',
        'confirmDelete', 'Toaster', 'swal',
    ];

    /**
     * @var list<UpgradeChange>
     */
    protected array $changes = [];

    /**
     * Rewrite a PHP source file. Returns the new source; collect() has the log.
     */
    public function upgradePhp(string $source): string
    {
        $this->changes = [];

        $source = $this->rewriteChainedCalls($source);
        $source = $this->rewriteSimpleTokens($source);
        $this->warnAboutRemovedApi($source);

        return $source;
    }

    /**
     * Rewrite a Blade file. Blade is not valid PHP, so this only touches the
     * directives, which are unambiguous.
     */
    public function upgradeBlade(string $source): string
    {
        $this->changes = [];

        $pattern = "/@include\(\s*['\"]sweetalert::alert['\"]\s*\)/";

        return preg_replace_callback($pattern, function (array $m) use ($source) {
            $this->changes[] = UpgradeChange::rewrite(
                'blade-directive',
                $this->lineOf($source, $m[0]),
                $m[0],
                '@sweetAlert'
            );

            return '@sweetAlert';
        }, $source) ?? $source;
    }

    /**
     * Rewrite a published config file.
     *
     * Only the three keys that break theming are touched. The rest of the v7
     * config was restructured rather than renamed, so re-publishing is the
     * honest advice and the command says so.
     */
    public function upgradeConfig(string $source): string
    {
        $this->changes = [];

        foreach (['SWEET_ALERT_WIDTH', 'SWEET_ALERT_PADDING', 'SWEET_ALERT_BACKGROUND'] as $env) {
            $pattern = "/env\(\s*'{$env}'\s*,\s*'[^']*'\s*\)/";

            $source = preg_replace_callback($pattern, function (array $m) use ($source) {
                $after = "env('".$this->envNameIn($m[0])."')";

                $this->changes[] = UpgradeChange::rewrite(
                    'config-themable-default',
                    $this->lineOf($source, $m[0]),
                    $m[0],
                    $after
                );

                return $after;
            }, $source) ?? $source;
        }

        return $source;
    }

    /**
     * @return list<UpgradeChange>
     */
    public function collect(): array
    {
        return $this->changes;
    }

    // ──────────────────────────────────────────────
    // Rules
    // ──────────────────────────────────────────────

    /**
     * v7 passed the title to html() and view(); v8 sets it with title().
     *
     *   ->html($title, $code, $icon)  ->  ->title($title)->html($code)->icon($icon)
     *   ->view($title, $view, $data)  ->  ->title($title)->view($view, $data)
     *
     * PHP ignores surplus arguments to a userland method, so an un-migrated
     * html() call does not error — it quietly renders the title as the body.
     * That silence is the whole reason this rule exists.
     */
    protected function rewriteChainedCalls(string $source): string
    {
        // Rewrites are applied back-to-front so earlier offsets stay valid.
        $edits = [];

        foreach ($this->findCalls($source, ['html', 'view']) as $call) {
            $args = $call['args'];
            $method = $call['method'];

            if (! $this->isLegacyShape($method, $args)) {
                continue;
            }

            if (! $call['isAlertChain']) {
                $this->changes[] = UpgradeChange::warning(
                    "{$method}-signature",
                    $call['line'],
                    $call['source'],
                    "Looks like the v7 {$method}() signature, but this chain could not be traced "
                    .'back to the Alert facade or helper, so it was left alone. Check it by hand.'
                );

                continue;
            }

            $replacement = $this->legacyReplacement($method, $args);

            /*
             * v7's html() and view() flashed the alert themselves, so a bare
             * `alert()->html(...)` displayed something. In v8 they are plain
             * setters, and `alert()` with no title does not flash either — so
             * without this the migrated call would be a silent no-op, which is
             * exactly the failure the upgrade is supposed to prevent.
             */
            if (! $call['tailFlashes']) {
                $replacement .= '->flash()';
            }

            $this->changes[] = UpgradeChange::rewrite(
                "{$method}-signature",
                $call['line'],
                $call['source'],
                $replacement
            );

            $edits[] = [$call['start'], $call['end'], $replacement];
        }

        foreach (array_reverse($edits) as [$start, $end, $replacement]) {
            $source = substr($source, 0, $start).$replacement.substr($source, $end);
        }

        return $source;
    }

    /**
     * Is this the v7 shape rather than the v8 one?
     */
    protected function isLegacyShape(string $method, array $args): bool
    {
        if ($method === 'html') {
            // v8 takes one argument; v7 took a title as well.
            return count($args) >= 2;
        }

        // v7: view($title, $view, ...) — the second argument is the view name.
        // v8: view($view, $data, ...) — the second argument is an array.
        return count($args) >= 2 && $this->looksLikeString($args[1]);
    }

    /**
     * Build the v8 chain that replaces a v7 call.
     */
    protected function legacyReplacement(string $method, array $args): string
    {
        $title = $args[0];
        $out = "->title({$title})";

        if ($method === 'html') {
            $out .= '->html('.($args[1] ?? "''").')';
            $icon = $args[2] ?? null;
        } else {
            $viewArgs = array_slice($args, 1, 3);
            $out .= '->view('.implode(', ', $viewArgs).')';
            $icon = $args[4] ?? null;
        }

        if ($icon !== null && ! $this->isEmptyString($icon)) {
            $out .= "->icon({$icon})";
        }

        return $out;
    }

    /**
     * Container binding and class moves.
     *
     * Done over the token stream rather than with a search-and-replace, so a
     * doc comment or a message that happens to mention `app('sweet-alert')`
     * is left as prose instead of being silently edited.
     */
    protected function rewriteSimpleTokens(string $source): string
    {
        $tokens = token_get_all($source);
        $out = '';
        $count = count($tokens);

        $movedClasses = [
            'RealRashid\\SweetAlert\\Storage\\SessionStore' => 'RealRashid\\SweetAlert\\Contracts\\SessionStoreInterface',
        ];

        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];

            if (! is_array($token)) {
                $out .= $token;

                continue;
            }

            // app('sweet-alert') -> app('alert')
            if ($token[0] === T_STRING && $token[1] === 'app') {
                $consumed = $this->matchContainerBinding($tokens, $i);

                if ($consumed !== null) {
                    $this->changes[] = UpgradeChange::rewrite(
                        'container-binding',
                        $token[2],
                        "app('sweet-alert')",
                        "app('alert')"
                    );

                    $out .= "app('alert')";
                    $i = $consumed;

                    continue;
                }
            }

            if ($this->isNameToken($token)) {
                $replaced = strtr($token[1], $movedClasses);

                if ($replaced !== $token[1]) {
                    $this->changes[] = UpgradeChange::rewrite('moved-class', $token[2], $token[1], $replaced);
                    $out .= $replaced;

                    continue;
                }
            }

            $out .= $token[1];
        }

        return $out;
    }

    /**
     * Is this `app(` followed by the old binding name and a closing paren?
     * Returns the index of the last consumed token, or null.
     */
    protected function matchContainerBinding(array $tokens, int $i): ?int
    {
        $open = $this->nextMeaningful($tokens, $i);

        if ($open === null || $tokens[$open] !== '(') {
            return null;
        }

        $arg = $this->nextMeaningful($tokens, $open);

        if ($arg === null || ! is_array($tokens[$arg]) || $tokens[$arg][0] !== T_CONSTANT_ENCAPSED_STRING) {
            return null;
        }

        if (trim($tokens[$arg][1], '\'"') !== 'sweet-alert') {
            return null;
        }

        $close = $this->nextMeaningful($tokens, $arg);

        return ($close !== null && $tokens[$close] === ')') ? $close : null;
    }

    protected function isNameToken(array $token): bool
    {
        $names = [T_STRING];

        foreach (['T_NAME_QUALIFIED', 'T_NAME_FULLY_QUALIFIED', 'T_NAME_RELATIVE'] as $constant) {
            if (defined($constant)) {
                $names[] = constant($constant);
            }
        }

        return in_array($token[0], $names, true);
    }

    /**
     * Things v8 removed outright. Rewriting these would mean guessing at
     * intent, so they are reported and left exactly as they are.
     *
     * Comments and strings are skipped: a note in a docblock saying "we used
     * to call buildConfig()" is not something a developer needs to action.
     */
    protected function warnAboutRemovedApi(string $source): void
    {
        $tokens = token_get_all($source);
        $count = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];

            if (! is_array($token)) {
                continue;
            }

            if ($this->isNameToken($token) && str_contains($token[1], 'SweetAlert\\Toaster')) {
                $this->changes[] = UpgradeChange::warning(
                    'removed-class',
                    $token[2],
                    $token[1],
                    'The Toaster class is gone in v8. Use the Alert facade, or type-hint '
                    .'RealRashid\SweetAlert\Builders\AlertBuilder.'
                );

                continue;
            }

            if ($token[0] !== T_OBJECT_OPERATOR) {
                continue;
            }

            $nameIndex = $this->nextMeaningful($tokens, $i);

            if ($nameIndex === null || ! is_array($tokens[$nameIndex]) || $tokens[$nameIndex][0] !== T_STRING) {
                continue;
            }

            if ($tokens[$nameIndex][1] === 'buildConfig') {
                $this->changes[] = UpgradeChange::warning(
                    'removed-method',
                    $tokens[$nameIndex][2],
                    '->buildConfig()',
                    'buildConfig() was removed. Use toArray() for the array or toJson() for the JSON.'
                );
            }
        }
    }

    // ──────────────────────────────────────────────
    // Tokenising
    // ──────────────────────────────────────────────

    /**
     * Find `->method(...)` calls and split their arguments at the top level.
     *
     * Done on the token stream so that strings, nested calls, arrays and
     * closures containing commas cannot be mistaken for argument separators.
     *
     * @param  list<string>  $methods
     * @return list<array{method:string,args:list<string>,start:int,end:int,line:int,source:string,isAlertChain:bool,tailFlashes:bool}>
     */
    protected function findCalls(string $source, array $methods): array
    {
        $tokens = token_get_all($source);
        $found = [];

        // Byte offset of each token, so edits can be spliced back precisely.
        $offsets = [];
        $offset = 0;

        foreach ($tokens as $i => $token) {
            $offsets[$i] = $offset;
            $offset += strlen(is_array($token) ? $token[1] : $token);
        }

        foreach ($tokens as $i => $token) {
            if (! is_array($token) || $token[0] !== T_OBJECT_OPERATOR) {
                continue;
            }

            $nameIndex = $this->nextMeaningful($tokens, $i);

            if ($nameIndex === null) {
                continue;
            }

            $name = $tokens[$nameIndex];

            if (! is_array($name) || $name[0] !== T_STRING || ! in_array($name[1], $methods, true)) {
                continue;
            }

            $parenIndex = $this->nextMeaningful($tokens, $nameIndex);

            if ($parenIndex === null || $tokens[$parenIndex] !== '(') {
                continue;
            }

            $closeIndex = $this->matchingParen($tokens, $parenIndex);

            if ($closeIndex === null) {
                continue;
            }

            $start = $offsets[$i];
            $end = $offsets[$closeIndex] + 1;

            $found[] = [
                'method' => $name[1],
                'args' => $this->splitArguments($tokens, $parenIndex, $closeIndex),
                'start' => $start,
                'end' => $end,
                'line' => $name[2],
                'source' => substr($source, $start, $end - $start),
                'isAlertChain' => $this->isAlertChain($tokens, $i),
                'tailFlashes' => $this->tailFlashes($source, $end),
            ];
        }

        return $found;
    }

    /**
     * Walk backwards to the start of the statement and look for something that
     * proves this chain came from the package. Without that proof the call is
     * only reported, never rewritten — plenty of other objects have html().
     */
    protected function isAlertChain(array $tokens, int $index): bool
    {
        for ($i = $index; $i >= 0; $i--) {
            $token = $tokens[$i];

            if (! is_array($token)) {
                if (in_array($token, self::STATEMENT_BOUNDARIES, true)) {
                    return false;
                }

                continue;
            }

            if ($token[0] === T_OPEN_TAG) {
                return false;
            }

            if (($token[0] === T_STRING || $token[0] === T_VARIABLE)
                && $this->matchesAlertMarker(ltrim($token[1], '$'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Does the rest of this statement already call flash()?
     */
    protected function tailFlashes(string $source, int $from): bool
    {
        $end = strpos($source, ';', $from);
        $tail = $end === false ? substr($source, $from) : substr($source, $from, $end - $from);

        return str_contains($tail, 'flash(');
    }

    protected function matchesAlertMarker(string $name): bool
    {
        foreach (self::ALERT_MARKERS as $marker) {
            if (strcasecmp($name, $marker) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Split the arguments between two parens at depth one.
     *
     * @return list<string>
     */
    protected function splitArguments(array $tokens, int $open, int $close): array
    {
        $depth = 0;
        $args = [];
        $current = '';

        for ($i = $open + 1; $i < $close; $i++) {
            $token = $tokens[$i];
            $text = is_array($token) ? $token[1] : $token;

            if (! is_array($token)) {
                if (in_array($token, ['(', '[', '{'], true)) {
                    $depth++;
                } elseif (in_array($token, [')', ']', '}'], true)) {
                    $depth--;
                } elseif ($token === ',' && $depth === 0) {
                    $args[] = trim($current);
                    $current = '';

                    continue;
                }
            }

            $current .= $text;
        }

        if (trim($current) !== '') {
            $args[] = trim($current);
        }

        return $args;
    }

    protected function matchingParen(array $tokens, int $open): ?int
    {
        $depth = 0;

        for ($i = $open; $i < count($tokens); $i++) {
            $token = $tokens[$i];

            if (is_array($token)) {
                continue;
            }

            if ($token === '(') {
                $depth++;
            } elseif ($token === ')') {
                $depth--;

                if ($depth === 0) {
                    return $i;
                }
            }
        }

        return null;
    }

    protected function nextMeaningful(array $tokens, int $from): ?int
    {
        for ($i = $from + 1; $i < count($tokens); $i++) {
            $token = $tokens[$i];

            if (is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }

            return $i;
        }

        return null;
    }

    // ──────────────────────────────────────────────
    // Small helpers
    // ──────────────────────────────────────────────

    protected function looksLikeString(string $arg): bool
    {
        return str_starts_with($arg, "'") || str_starts_with($arg, '"');
    }

    protected function isEmptyString(string $arg): bool
    {
        return in_array(trim($arg), ["''", '""', 'null'], true);
    }

    protected function envNameIn(string $call): string
    {
        preg_match("/'([A-Z_]+)'/", $call, $m);

        return $m[1] ?? '';
    }

    protected function lineOf(string $source, string $needle): int
    {
        $offset = strpos($source, $needle);

        return $offset === false ? 0 : substr_count(substr($source, 0, $offset), "\n") + 1;
    }
}
