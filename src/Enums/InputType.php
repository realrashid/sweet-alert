<?php

namespace RealRashid\SweetAlert\Enums;

enum InputType: string
{
    case Text = 'text';
    case Email = 'email';
    case Password = 'password';
    case Number = 'number';
    case Tel = 'tel';
    case Range = 'range';
    case Textarea = 'textarea';
    case Select = 'select';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case File = 'file';
    case Url = 'url';
    case Color = 'color';
    case Date = 'date';
    case DatetimeLocal = 'datetime-local';
    case Time = 'time';
    case Month = 'month';
    case Week = 'week';
    case Search = 'search';
}
