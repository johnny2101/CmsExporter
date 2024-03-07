<?php

namespace Cosmobile\CmsExporter\Block\Adminhtml\CmsBlock\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Catalog\Block\Adminhtml\Category\AbstractCategory;

/**
 * Class ClearCacheButton
 */
class Import extends AbstractCategory implements ButtonProviderInterface
{
    /**
     * Clear Cache button
     *
     * @return array
     */
    public function getButtonData()
    {

        return [
            'id' => 'clear_cache',
            'label' => __('Clear Category Cache'),
            'on_click' => "alert('ok')",
            'class' => 'delete',
        ];
    }
}
