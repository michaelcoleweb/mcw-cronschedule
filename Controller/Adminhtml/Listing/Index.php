<?php

namespace MCW\CronSchedule\Controller\Adminhtml\Listing;

use Magento\Framework\View\Result\Page;
use MCW\CronSchedule\Controller\Adminhtml\AbstractAction;

/**
 * Cron Listing Action
 *
 * @package MCW\CronSchedule
 * @author  Michael Cole <https://github.com/michaelcoleweb>
 */
class Index extends AbstractAction
{
    /**
     * Render Index Action
     * 
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu(self::SCHEDULE_RESOURCE);
        $resultPage->addBreadcrumb(__('Cron Schedule'), __('Cron Schedule'));
        $resultPage->addBreadcrumb(__('Cron Schedule Listing'), __('Cron Schedule Listing'));

        return $resultPage;
    }
}
