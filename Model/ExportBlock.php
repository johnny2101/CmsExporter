<?php

namespace Cosmobile\CmsExporter\Model;

use Exception;
use Magento\Cms\Model\Block;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportBlock
{
    private File $file;

    public function __construct(
        File $file
    )
    {
        $this->file = $file;
    }

    public function exportCmsBlocks($configFileName, $collection): string | bool
    {
        $blocks = [];

        /** @var Block $block */
        foreach ($collection as $block) {
            $blocks[] = $block->getData();
        }

        try {
            $content = json_encode($blocks, JSON_PRETTY_PRINT);
            $filePath = __DIR__ . '/../' . $configFileName;
            $this->file->write($filePath, $content);

            return $content;
        } catch (Exception $e) {
            return false;
        }
    }
}
