<?php

namespace App\Enums;

enum ContentType: string
{
    case VIDEO = 'video';
    case BOOK = 'book';
    case MOVIE = 'movie';
    case CARTOON = 'cartoon';
}
