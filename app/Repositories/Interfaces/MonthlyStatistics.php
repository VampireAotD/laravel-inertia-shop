<?php

namespace App\Repositories\Interfaces;

interface MonthlyStatistics
{
    /**
     * Return array of activities for current instance
     *
     * @return array
     */
    public function getActivityPerMonth(): array;
}