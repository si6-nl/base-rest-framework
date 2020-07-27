<?php

namespace Si6\Base\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Carbon;

/**
 * Class Entity
 *
 * @package Si6\Base\Domain\Entities
 * @ORM\HasLifecycleCallbacks
 */
abstract class Entity
{
    /**
     * @var int $id
     * @ORM\Id
     * @ORM\Column(type="bigint", options="{unsigned=true}")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;
    /**
     * @var array
     */
    private $events = [];
    /**
     * @var Carbon $created
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var Carbon $updated
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * EntityWithEvents constructor.
     *
     * @param int $id
     */
    public function __construct(int $id = 0)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function releaseEvents(): array
    {
        $events       = $this->events;
        $this->events = [];

        return $events;
    }

    /**
     * @param array $events
     */
    public function addEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->record($event);
        }
    }

    /**
     * @param $event
     */
    protected function record($event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $now = now();
        $this->setUpdatedAt($now);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($now);
        }
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $createdAt
     */
    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @param Carbon $updatedAt
     */
    public function setUpdatedAt(Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
