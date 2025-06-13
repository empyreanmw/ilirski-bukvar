<?php

namespace App\Enums;

enum PlayerType: string
{
    case YOUTUBE = 'youtube';
    case GOOGLE_DRIVE = 'google_drive';
    case PDF = 'pdf';
    case REGULAR_VIDEO = 'regular_video';
    case VIMEO = 'vimeo';
}
