<?php
namespace App\Services;

use App\Enums\PlayerType;
use App\Models\Content;
use Psr\Http\Message\ResponseInterface;

class ExtensionFinderService
{
    const EXTENSION_JPG = 'jpg';
    const EXTENSION_PNG = 'png';
    const EXTENSION_MP4 = 'mp4';
    const EXTENSION_PDF = 'pdf';

    /**
     * @throws \Exception
     */
    public function getExtensionFromResponse(ResponseInterface $response, Content $content): string
    {
        $extension = $this->getExtensionFromContentDisposition($response->getHeaderLine('Content-Disposition'));

        if ($extension === null) {
            $extension = $this->getExtensionFromContentType($response->getHeaderLine('Content-Type'), $content);
        }

        if ($extension === null) {
            throw new \Exception('Could not determine file extension');
        }

        return $extension;
    }

    protected function getExtensionFromContentType(string $contentType, Content $content): ?string
    {
        return match ($contentType) {
            'image/jpeg' => self::EXTENSION_JPG,
            'image/png' => self::EXTENSION_PNG,
            'video/mp4' => self::EXTENSION_MP4,
            default => $this->getExtensionFromContent($content), // fallback
        };
    }

    protected function getExtensionFromContentDisposition(string $contentDisposition): ?string
    {
        $filename = null;

        if (preg_match('/filename=\"?(.*?)\"?$/', $contentDisposition, $matches)) {
            $filename = $matches[1]; // You get "document.pdf"
        }

        if ($filename === null) {
            return null;
        }

        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function getExtensionFromContent(Content $content): string
    {
        return match ($content->player_type) {
            PlayerType::YOUTUBE, PlayerType::VIMEO, PlayerType::GOOGLE_DRIVE => self::EXTENSION_MP4,
            PlayerType::PDF => self::EXTENSION_PDF,
            default => self::EXTENSION_MP4,
        };
    }
}
