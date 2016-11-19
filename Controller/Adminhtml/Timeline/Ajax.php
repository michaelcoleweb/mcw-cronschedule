<?php

namespace MCW\CronSchedule\Controller\Adminhtml\Timeline;

use Magento\Cron\Model\ResourceModel\Schedule\Collection;
use Magento\Cron\Model\Schedule;
use Magento\Framework\View\Result\Page;
use MCW\CronSchedule\Controller\Adminhtml\AbstractAction;

/**
 * Cron Time line Ajax Action
 *
 * @package MCW\CronSchedule
 * @author  Michael Cole <https://github.com/michaelcoleweb>
 */
class Ajax extends AbstractAction
{
    /**
     * Cron Job Groups
     *
     * @var array
     */
    protected $jobGroups = [];

    /**
     * Render Index Action
     * 
     * @return Page
     */
    public function execute()
    {
        $jsonResult = $this->jsonFactory->create();
        try {
            if (!$this->getRequest()->isAjax()) {
                throw new \HttpRequestException(__('Invalid Request'));
            }

            $date = $this->getRequest()->getParam('date', null);
            if (!$date) {
                throw new \Exception(__('No Date Specified'));
            }

            $dateStart = $this->getDateStart($date);
            $dateEnd = $this->getDateEnd($date);
            $events = $this->getCronEvents($dateStart, $dateEnd);
            if (empty($events)) {
                throw new \Exception(__('No Events have occurred on %1', $date));
            }
            
            $groups = $this->getCronGroups();

            return $jsonResult->setData([
                'error'      => false,
                'success'    => true,
                'date_start' => $dateStart,
                'date_end'   => $dateEnd,
                'events'     => $events,
                'groups'     => $groups,
            ]);

        } catch (\Exception $e) {

            return $jsonResult->setData([
                'error'      => $e->getMessage(),
                'success'    => false,
                'date_start' => '',
                'date_end'   => '',
                'events'     => [],
                'groups'     => [],
            ]);
        }
    }

    /**
     * Returns the start date (i.e. specified date).
     *
     * @param string $date Date String
     *
     * @return string
     */
    protected function getDateStart($date)
    {
        $date = new \DateTime($date);

        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Returns the end date (i.e. one day after specified date).
     *
     * @param string $date Date String
     *
     * @return string
     */
    protected function getDateEnd($date)
    {
        $datetime = new \DateTime($date);
        $datetime->modify('+1 day');

        return $datetime->format('Y-m-d H:i:s');
    }

    /**
     * This method will get the cron events that have occurred on the specified date.
     * 
     * @param string $dateStart Start Date
     * @param string $dateEnd   End Date
     * 
     * @return array
     */
    protected function getCronEvents($dateStart, $dateEnd)
    {
        $events = [];
        $jobGroupCount = 1;
        /** @var Collection $collection */
        $collection = $this->scheduleCollectionFactory->create();
        $collection->addFieldToFilter('executed_at', [
            'from' => $dateStart,
            'to'   => $dateEnd,
            'date' => true
        ]);

        /** @var Schedule $item */
        foreach ($collection as $item) {
            if ($item->getExecutedAt() == null || $item->getFinishedAt() == null) {
                continue;
            }
            if (!in_array($item->getJobCode(), $this->jobGroups)) {
                $this->jobGroups[$jobGroupCount] = $item->getJobCode();
                $jobGroupCount++;
            }
            $events[] = [
                'id'      => (int) $item->getId(),
                'content' => $this->getEventContent($item),
                'start'   => $item->getExecutedAt(),
                'end'     => $item->getFinishedAt(),
                'group'   => (int) array_search($item->getJobCode(), $this->jobGroups),
                'style'   => $this->getEventType($item)
            ];
        }

        return $events;
    }

    /**
     * This method will get the html message that is shown on a cron event.
     *
     * @param Schedule $event Cron Event
     *
     * @return string
     */
    protected function getEventContent(Schedule $event)
    {
        return sprintf('<strong>%s</strong><br/>Executed On: <em>%s</em><br/>Finished On: <em>%s</em>',
            $event->getJobCode(),
            $this->getEventTime($event->getExecutedAt()),
            $this->getEventTime($event->getFinishedAt())
        );
    }

    /**
     * This method will get the time to display on an either an event start or finish time.
     *
     * @param string $date Event Time
     *
     * @return string
     */
    protected function getEventTime($date)
    {
        $date = new \DateTime($date);

        return $date->format('G:H:s');
    }

    /**
     * This method will get styles used on the event based on its status.
     * 
     * @param Schedule $event Cron Event
     *
     * @return string
     */
    protected function getEventType(Schedule $event)
    {
        switch ($event->getStatus()) {
            case Schedule::STATUS_SUCCESS:
                $background = '#d0e5a9';
                $border = '#5b8116';
                break;
            case Schedule::STATUS_ERROR:
                $background = '#f9d4d4';
                $border = '#e22626';
                break;
            default:
                $background = '#FFF785';
                $border = '#FFC200';
                break;
        }

        return sprintf('background-color:%s; border:1px solid %s',
            $background,
            $border
        );
    }

    /**
     * This method will retrieve the groups(job codes) to be displayed.
     *
     * @return array
     */
    protected function getCronGroups()
    {
        $groups = [];
        $i = 1;
        foreach ($this->jobGroups as $jobGroup) {
            $groups[] = [
                'id'      => $i,
                'content' => $jobGroup
            ];
            $i++;
        }

        return $groups;
    }
}
