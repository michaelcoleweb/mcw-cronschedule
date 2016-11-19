<?php

namespace MCW\CronSchedule\Block\Adminhtml;

use Magento\Backend\Block\Template;

/**
 * Cron Time line Block
 *
 * @package MCW\CronSchedule
 * @author  Michael Cole <https://github.com/michaelcoleweb>
 */
class Timeline extends Template
{
    /**
     * This method get the url used to refresh the cron events chart.
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('*/*/ajax');
    }
}
