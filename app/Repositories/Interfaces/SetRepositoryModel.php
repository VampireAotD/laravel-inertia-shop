<?php

namespace App\Repositories\Interfaces;

interface SetRepositoryModel
{
    /**
     * Identifies model for current repository
     *
     * @return string
     */
    public function setRepositoryModel() : string;
}