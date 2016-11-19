<?php

namespace MCW\CronSchedule\Controller\Adminhtml\Timeline;

use Magento\Framework\View\Result\Page;
use MCW\CronSchedule\Controller\Adminhtml\AbstractAction;

/**
 * Cron Time line Action
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
        $resultPage->addBreadcrumb(__('Cron Time Line'), __('Cron Time Line'));

        return $resultPage;
    }
}
