<?php

namespace Cosmobile\CmsExporter\Controller\Adminhtml\Cms\Block;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Message\ManagerInterface;

class Import extends Action
{
    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Context $context
     * @param BlockFactory $blockFactory
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context          $context,
        BlockFactory     $blockFactory,
        RedirectFactory  $resultRedirectFactory,
        ManagerInterface $messageManager,
        Filesystem       $filesystem
    )
    {
        parent::__construct($context);
        $this->blockFactory = $blockFactory;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
        $this->filesystem = $filesystem;
    }

    /**
     * Execute action
     *
     * @return Redirect
     */
    public function execute()
    {
        try {
            $file = $this->getRequest()->getFiles('import_file');
            if (!$file || !$file['tmp_name']) {
                throw new LocalizedException(__('Seleziona un file JSON da importare.'));
            }

            $data = json_decode(file_get_contents($file['tmp_name']), true);
            if (!$data) {
                throw new LocalizedException(__('Errore durante la decodifica del file JSON.'));
            }

            foreach ($data as $blockData) {
                $block = $this->blockFactory->create();
                $block->setData($blockData);
                $block->save();
            }

            $this->messageManager->addSuccessMessage(__('Blocchi CMS importati correttamente.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Errore durante l\'importazione dei blocchi CMS.'));
        }

        return $this->resultRedirectFactory->create()->setPath('cms/block/index');
    }
}

