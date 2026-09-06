<?php

namespace RealRashid\SweetAlert\Support;

/**
 * UpgradeChange - One thing the upgrader did, or one thing it refused to do.
 *
 * A change with `$applied` false is a warning: the upgrader found something
 * that v8 changed but could not rewrite it safely on its own, so a human has
 * to look. Those are reported just as loudly as the rewrites.
 */
class UpgradeChange
{
    final public function __construct(
        public readonly string $rule,
        public readonly int $line,
        public readonly string $before,
        public readonly ?string $after = null,
        public readonly bool $applied = true,
        public readonly string $note = '',
    ) {}

    /**
     * A rewrite the upgrader performed.
     */
    public static function rewrite(string $rule, int $line, string $before, string $after): static
    {
        return new static($rule, $line, $before, $after);
    }

    /**
     * Something a human has to change by hand.
     */
    public static function warning(string $rule, int $line, string $before, string $note): static
    {
        return new static($rule, $line, $before, null, false, $note);
    }
}
