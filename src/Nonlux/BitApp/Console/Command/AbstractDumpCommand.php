<?php
/**
 * Created by PhpStorm.
 * User: nonlux
 * Date: 06.07.14
 * Time: 17:50
 */

namespace Nonlux\BitApp\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractDumpCommand extends Command
{

    /**
     * @param $originDir
     * @param $targetDir
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    protected function dumpFiles($originDir, $targetDir)
    {
        $filesystem = new Filesystem();
        $flags = \FilesystemIterator::SKIP_DOTS;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($originDir, $flags), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            $target = str_replace($originDir, $targetDir, $file->getPathname());
            if (is_link($file) || is_file($file)) {
                $filesystem->copy($file, $target, true);
            } elseif (is_dir($file)) {
                $filesystem->mkdir($target);
            } else {
                throw new IOException(sprintf('Unable to guess "%s" file type.', $file), 0, null, $file);
            }
        }
    }
}