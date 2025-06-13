<?php

namespace App\Enums;

enum VideoQuality: int
{
    case QUALITY_120 = 144;
    case QUALITY_240 = 240;
    case QUALITY_360 = 360;
    case QUALITY_480 = 480;
    case QUALITY_720 = 720;
    case QUALITY_1080 = 1080;
    case QUALITY_1440 = 1440;
    case QUALITY_2160 = 2160;
}
