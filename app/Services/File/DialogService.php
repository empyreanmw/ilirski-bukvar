<?php
namespace App\Services\File;

use App\Enums\FileType;
use Native\Laravel\Dialog;

class DialogService
{
    public function open(string $fileType, ?string $defaultPath)
    {
        return match ($fileType) {
            FileType::FOLDER->value => $this->getFolderDialog($defaultPath),
            FileType::FILE->value => $this->getFileDialog(defaultPath: $defaultPath, extensions: ['exe'])
        };
    }

    protected function getFolderDialog(?string $defaultPath)
    {
        $dialog = Dialog::new()
            ->folders();

        if ($defaultPath !== null) {
            $dialog->defaultPath($defaultPath);
        }
        
        return $dialog
            ->open();
    }

    protected function getFileDialog(?string $defaultPath, string $name = '', array $extensions = [])
    {
        $dialog = Dialog::new()
            ->filter($name, $extensions)
            ->files();

        if ($defaultPath !== null) {
            $dialog->defaultPath($defaultPath);
        }

        return $dialog
            ->open();
    }
}
