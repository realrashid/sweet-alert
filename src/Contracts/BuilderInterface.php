<?php

namespace RealRashid\SweetAlert\Contracts;

interface BuilderInterface extends AlertInterface
{
    /**
     * Create a new builder instance.
     */
    public static function make(): static;

    /**
     * Reset the builder to its default state.
     */
    public function reset(): static;
}
