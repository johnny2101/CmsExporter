<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cosmobile\CmsExporter\Controller\Adminhtml\Block;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Cosmobile\CmsExporter\Model\ExportBlock;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class MassDelete
 */
class Export extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Cms::block';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ExportBlock
     */
    protected $exporter;
    private \Magento\Framework\App\Response\Http\FileFactory $fileFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context                                          $context,
        Filter                                           $filter,
        CollectionFactory                                $collectionFactory,
        ExportBlock                                      $exporter,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->exporter = $exporter;
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $block) {
            echo json_encode($block->getData());
        }

        $content = $this->exporter->exportCmsBlocks("fileDiProva.json", $collection);

        if(!$content) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        } else {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been exported.', $collectionSize));
            return $this->fileFactory->create("downloaded.json", $content, DirectoryList::VAR_DIR);
        }
    }
}
