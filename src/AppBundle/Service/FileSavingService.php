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
    public $filesystem;

    /**
     * @var string $basePath
     */
    public $basePath;


    /**
     * ParseConsumer constructor
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->basePath = \AppKernel::getWebDir().'/htmlFiles';
    }

    public function saveContentToFile($filename, $content)
    {
        $folders = $this->createFolders($filename, 0, '', []);
        $this->filesystem->mkdir($folders);
        $this->filesystem->dumpFile($folders[count($folders) - 1].'/'.$filename.'.html', $content);

    }

    /**
     * @param string $filename
     * @param integer $i
     * @param string $folderPath
     * @param array $folders
     * @return array
     */
    private function createFolders(string $filename, int $i, string $folderPath, array $folders)
    {
        if ($i !== strlen($filename) || $i === 5) {
            $folderPath = '/'.substr($filename, $i, $i + 1);
            $folders[] = $folderPath;
            $this->createFolders($filename, $i + 1, $folderPath, $folders);
        }

        foreach ($folders as &$folder) {
            $folder .= $this->basePath.$folder;
        }
        unset($folder);

        return $folders;
    }
}
