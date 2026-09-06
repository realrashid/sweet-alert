<?php

namespace RealRashid\SweetAlert\Exceptions;

class InvalidAlertConfigurationException extends \RuntimeException
{
    public static function invalidInputType(string $type): self
    {
        return new self("Invalid input type: [{$type}]. Please use a valid InputType enum value.");
    }

    public static function invalidPosition(string $position): self
    {
        return new self("Invalid position: [{$position}]. Please use a valid Position enum value.");
    }

    public static function missingTitle(): self
    {
        return new self('An alert must have a title set before flashing.');
    }

    public static function invalidAlertType(string $type): self
    {
        return new self("Invalid alert type: [{$type}]. Please use a valid AlertType enum value.");
    }
}
