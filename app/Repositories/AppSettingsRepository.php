<?php
namespace App\Repositories;

use App\Models\AppSettings;

class AppSettingsRepository
{
    public function getAppMode(): AppSettings
    {
        return AppSettings::first();
    }

    public function getContentVersion(): int
    {
        return $this->getAppMode()->content_version;
    }
}