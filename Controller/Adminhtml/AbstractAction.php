<?php

namespace MCW\CronSchedule\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result;
use Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory;

/**
 * Adminhtml Abstract Action
 *
 * @package MCW\CronSchedule
 * @author  Michael Cole <https://github.com/michaelcoleweb>
 */
abstract class AbstractAction extends Action
{
    /**
     * ACL Resource
     */
    const SCHEDULE_RESOURCE = 'MCW_CronSchedule::schedule';

    /**
     * Result Forward Factory
     * 
     * @var ForwardFactory
     */
    protected $forwardFactory;

    /**
     * Registry
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Layout Factory
     *
     * @var Result\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * Page Factory
     *
     * @var Result\LayoutFactory
     */
    protected $pageFactory;

    /**
     * Cron Schedule Collection Factory
     *
     * @var CollectionFactory
     */
    protected $scheduleCollectionFactory;

    /**
     * Result Json Factory
     *
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * Abstract Action constructor
     *
     * @param Context              $context                   Backend Controller Context
     * @param ForwardFactory       $forwardFactory            Forward Factory
     * @param Registry             $registry                  Registry Model
     * @param Result\LayoutFactory $layoutFactory             Layout Factory
     * @param Result\PageFactory   $pageFactory               Page Factory
     * @param CollectionFactory    $scheduleCollectionFactory Cron Collection Factory
     * @param JsonFactory          $jsonFactory               Result Json Factory
     */
    public function __construct(
        Context              $context,
        ForwardFactory       $forwardFactory,
        Registry             $registry,
        Result\LayoutFactory $layoutFactory,
        Result\PageFactory   $pageFactory,
        CollectionFactory    $scheduleCollectionFactory,
        JsonFactory          $jsonFactory
    ) {
        parent::__construct($context);
        $this->forwardFactory = $forwardFactory;
        $this->registry = $registry;
        $this->layoutFactory = $layoutFactory;
        $this->pageFactory = $pageFactory;
        $this->scheduleCollectionFactory = $scheduleCollectionFactory;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Check permission for user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::SCHEDULE_RESOURCE);
    }
}