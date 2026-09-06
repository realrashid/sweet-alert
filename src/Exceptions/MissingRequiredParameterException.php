<?php

namespace RealRashid\SweetAlert\Exceptions;

class MissingRequiredParameterException extends \RuntimeException
{
    public static function titleRequired(): self
    {
        return new self('A title is required for this alert type.');
    }

    public static function inputOptionsRequired(): self
    {
        return new self('Input options are required when using select or radio input types.');
    }

    public static function deleteUrlRequired(): self
    {
        return new self('A delete URL is required for confirm delete alerts.');
    }
}
