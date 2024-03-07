<?php
/**
 * Copyright © 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Cosmobile\CmsExporter\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Modal extends Template implements ButtonProviderInterface
{
    protected $_template = "CmsExporter::modal.phtml";
    /**
     * Constructor
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getModalContent()
    {
        return __('Random block content for %1',
            $this->_storeManager->getStore()->getName(),
        );
    }

    public function getButtonData()
    {
        return [
            'label' => __('Your button label here'),
            'on_click' => "alert('it works')",
            'sort_order' => 100
        ];
    }
}
