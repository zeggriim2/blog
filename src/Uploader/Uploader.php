<?php

declare(strict_types=1);

namespace App\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader implements UploaderInterface
{

    public function __construct(
        private string $uploadsRelativeDir,
        private string $uploadsAbsoluteDir,
        private SluggerInterface $slugger,
    )
    {
    }

    public function upload(UploadedFile $file): string
    {
        $filename = sprintf(
            '%s_%s.%s',
            $this->slugger->slug($file->getClientOriginalName()),
            uniqid(),
            $file->guessExtension()
        );

        $file->move($this->uploadsAbsoluteDir, $filename);
        return $this->uploadsRelativeDir . "/" . $filename;
    }
}