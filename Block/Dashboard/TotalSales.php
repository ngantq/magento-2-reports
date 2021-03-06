<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Reports
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Reports\Block\Dashboard;

/**
 * Class TotalSales
 * @package Mageplaza\Reports\Block\Dashboard
 */
class TotalSales extends AbstractClass
{
    const NAME = 'totalSales';

    /**
     * @var string
     */
    protected $_template = 'dashboard/chart.phtml';

    /**
     * @return float|int|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getTotal()
    {
        $date   = $this->_helperData->getDateRange();
        $totals = $this->_helperData->getTotalsByDateRange($date[0], $date[1]);

        return $this->getBaseCurrency()->format($totals->getRevenue() ? $totals->getRevenue() : 0);
    }

    /**
     * @return float|int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getRate()
    {
        $dates         = $this->_helperData->getDateRange();
        $totals        = $this->_helperData->getTotalsByDateRange($dates[0], $dates[1]);
        $compareTotals = $this->_helperData->getTotalsByDateRange($dates[2], $dates[3]);
        if ($totals->getRevenue() == 0 && $compareTotals->getRevenue() == 0) {
            return 0;
        } else if ($compareTotals->getRevenue() == 0) {
            return 100;
        } else if ($totals->getRevenue() == 0) {
            return -100;
        }

        return round(((($totals->getRevenue() - $compareTotals->getRevenue()) / $compareTotals->getRevenue()) * 100), 2);
    }

    /**
     * @param $date
     * @param null $endDate
     * @return float|int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getDataByDate($date, $endDate = null)
    {
        $totals = $this->_helperData->getTotalsByDateRange($date, $endDate);

        return round($totals->getRevenue() ? $totals->getRevenue() : 0, 2);
    }

    /**
     * @return string
     */
    protected function getYUnit()
    {
        return $this->getBasePriceFormat();
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getTitle()
    {
        return __('Totals');
    }
}