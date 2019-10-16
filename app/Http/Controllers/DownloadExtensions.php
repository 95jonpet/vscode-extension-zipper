<?php

namespace App\Http\Controllers;

use App\Extension;
use Illuminate\Http\Request;
use ZipArchive;

/**
 * Invokable controller for downloading VS Code extensions.
 */
class DownloadExtensions extends Controller
{
    /**
     * Download VS Code extensions as a zip from a list.
     */
    public function __invoke(Request $request)
    {
        $extensions = collect($this->validate($request, [
            'extensions' => 'required|array'
        ])['extensions'])->map(function ($extensionIdentifier) {
            return Extension::parse($extensionIdentifier);
        });

        $zipFile = $this->createTempFile();
        $zip = new ZipArchive();
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($extensions as $extension) {
            $zip->addFile($this->download($extension), $extension->getFileName());
        }

        $zip->close();
        return response()->download($zipFile);
    }

    /**
     * Download an extension to a temporary file.
     * A temporary file is created.
     */
    private function download(Extension $extension): string
    {
        $file = $this->createTempFile();
        file_put_contents($file, fopen($extension->getPackageUrl(), 'rb'));
        return $file;
    }

    /**
     * Create a temporary file.
     */
    private function createTempFile(): string
    {
        return tempnam(sys_get_temp_dir(), 'vscode-extension-');
    }
}
