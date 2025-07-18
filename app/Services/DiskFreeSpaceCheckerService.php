<?php
namespace App\Services;

use App\Exceptions\InvalidDownloadPathException;
use App\Exceptions\NotEnoughDiskSpaceException;
use App\Models\AppSettings;
use App\Models\Category;

class DiskFreeSpaceCheckerService
{
    /**
     * @throws InvalidDownloadPathException
     * @throws NotEnoughDiskSpaceException
     */
    public function checkFreeSpace(array $categories): void
    {
        $disk = $this->findDiskFromDownloadPath();

        if (!file_exists($disk)) {
            throw new InvalidDownloadPathException(__('settings.downloads.invalid_path_selected'));
        }

        $freeBytes = disk_free_space($disk);

        $freeGB = round($freeBytes / 1024 / 1024 / 1024, 2);
        $requiredGB = round($this->calculateTotalSpaceRequired($categories) / 1024, 2);

        if ($requiredGB > $freeGB) {
            throw new NotEnoughDiskSpaceException(__('settings.downloads.download_size_error', ['size' => $requiredGB]));
        }
    }

    protected function findDiskFromDownloadPath(): string
    {
        $appSettings = AppSettings::first();
        $disk = explode('\\', $appSettings->download_path);

        return sprintf('%s\\\\', $disk[0]);
    }

    protected function calculateTotalSpaceRequired(array $categories): float
    {
        return Category::query()
            ->whereIn('id', $categories)
            ->sum('size');
    }
}
