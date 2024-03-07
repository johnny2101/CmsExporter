<?php

namespace Cosmobile\CmsExporter\Console\Command;

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

class ExportCmsBlock extends Command
{
    private State $state;
    private BlockFactory $blockFactory;
    private File $file;
    private DirectoryList $directoryList;
    private Json $jsonSerializer;

    public function __construct(
        State $state,
        BlockFactory $blockFactory,
        File $file,
        DirectoryList $directoryList,
        Json $jsonSerializer,
        ?string $name = null
    )
    {
        parent::__construct($name);
        $this->state = $state;
        $this->blockFactory = $blockFactory;
        $this->file = $file;
        $this->directoryList = $directoryList;
        $this->jsonSerializer = $jsonSerializer;
    }

    protected function configure(): void
    {
        $this->setName("cosmobile:export:cms:blocks");
        $this->setDescription("Export CMS blocks");
        $this->addArgument('config-file', InputArgument::REQUIRED, 'Configuration file name');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->state->setAreaCode(Area::AREA_GLOBAL);

        $configFileName = $input->getArgument('config-file');
        $this->exportCmsBlocks($configFileName, $output);

        return Command::SUCCESS;
    }

    private function exportCmsBlocks(string $configFileName, OutputInterface $output): void
    {
        $blocks = [];

        /** @var Block $block */
        foreach ($this->blockFactory->create()->getCollection() as $block) {
            $blocks[] = $block->getData();
        }

        try {
            $content = json_encode($blocks, JSON_PRETTY_PRINT);
            $filePath = __DIR__ . '/../../' . $configFileName;
            $output->writeln($filePath);
            $this->file->write($filePath, $content);
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
