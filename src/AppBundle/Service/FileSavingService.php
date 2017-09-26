<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FileSavingService
 * @package AppBundle\Service
 */
class FileSavingService
{
    /**
     * @var Filesystem $filesystem
     */
    private $filesystem;

    /**
     * @var string $basePath
     */
    private $basePath;


    /**
     * ParseConsumer constructor
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->basePath = \AppKernel::getWebDir().'/htmlFiles';
    }

    /**
     * Save html content to file
     * @param $filename
     * @param $content
     * @return string
     */
    public function saveContentToFile($filename, $content)
    {
        $folders = $this->createFolders($filename, 0, '', []);

        foreach ($folders as &$folder) {
            $folder = $this->basePath.$folder;
        }
        unset($folder);

        $this->filesystem->mkdir($folders);
        $pathToHtml = $folders[count($folders) - 1].'/'.$filename.'.html';
        $this->filesystem->dumpFile($pathToHtml, $content);

        return $pathToHtml;
    }

    /**
     * Recursive create folders up to 7th letter
     * @param string $filename
     * @param integer $i
     * @param string $folderPath
     * @param array $folders
     * @return array
     */
    private function createFolders(string $filename, int $i, string $folderPath, array $folders)
    {
        if ($i !== strlen($filename)) {
            if ($i !== 7) {
                $folderPath .= '/'.substr($filename, $i, 1);
                $folders[] = $folderPath;
                $folders = $this->createFolders($filename, $i + 1, $folderPath, $folders);
            }
        }

        return $folders;
    }
}
