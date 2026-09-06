<?php

namespace RealRashid\SweetAlert\Enums;

enum AlertType: string
{
    case Success = 'success';
    case Error = 'error';
    case Warning = 'warning';
    case Info = 'info';
    case Question = 'question';
}
