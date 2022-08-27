<?php

namespace App\Enums;

enum TableStatus: string
{
    case pending = 'pending';
    case Avalaiable = 'avalaiable';
    case Unvalaiable = 'unvalaiable';
}
