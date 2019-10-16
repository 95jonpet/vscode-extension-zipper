<?php

use App\Extension;

class ExtensionTest extends TestCase
{
    /**
     * An extension can be parsed.
     *
     * @return void
     */
    public function testParseExtension()
    {
        $extension = Extension::parse('VisualStudioExptTeam.vscodeintellicode@1.1.9');

        $this->assertEquals('VisualStudioExptTeam', $extension->getPublisher());
        $this->assertEquals('vscodeintellicode', $extension->getPackage());
        $this->assertEquals('1.1.9', $extension->getVersion());
    }

    /**
     * An extension can be parsed without a version.
     * In this case, a default version is used.
     *
     * @return void
     */
    public function testParseExtensionWithoutVersion()
    {
        $extension = Extension::parse('vscode-icons-team.vscode-icons');

        $this->assertEquals('vscode-icons-team', $extension->getPublisher());
        $this->assertEquals('vscode-icons', $extension->getPackage());
        $this->assertEquals(Extension::DEFAULT_EXTENSION_VERSION, $extension->getVersion());
    }

    /**
     * A list of extensions can be downloaded.
     *
     * @return void
     */
    public function testDownloadExtensions()
    {
        $data = [
            'extensions' => [
                'Gruntfuggly.todo-tree',
                'chrisdias.vscode-opennewinstance',
                'doraemon.vscode-code-runner',
            ],
        ];

        $this->json('POST', '/download-extensions', $data)
            ->seeHeader('content-type', 'application/zip')
            ->seeStatusCode(200);
    }
}
