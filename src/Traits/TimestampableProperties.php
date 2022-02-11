<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TimestampableProperties
{
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;
}