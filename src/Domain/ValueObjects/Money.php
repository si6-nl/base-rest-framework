<?php

namespace Si6\Base\Domain\ValueObjects;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Class Money
 *
 * @package App\Domain\ValueObjects
 * @ORM\Embeddable
 */
class Money
{
    /**
     * @var int
     * @ORM\Column(type="bigint", options="{unsigned=true}")
     */
    private $amount;

    /**
     * Money constructor.
     *
     * @param int $amount
     */
    private function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param $amount
     * @return Money
     */
    public static function create($amount = 0): Money
    {
        return new static((int)$amount);
    }
    
    /**
     * @return int
     */
    public function value(): int
    {
        return $this->amount;
    }

    /**
     * @param Money $other
     * @return $this
     */
    public function sub(Money $other): Money
    {
        $this->amount -= $other->value();

        return $this;
    }

    /**
     * @param Money $other
     * @return Money
     */
    public function diff(Money $other): Money
    {
        $amount = $this->value() - $other->value();

        return Money::create($amount);
    }

    /**
     * @param Money $other
     * @return $this
     */
    public function add(Money $other): Money
    {
        $this->amount += $other->value();

        return $this;
    }

    /**
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    /**
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    /**
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    /**
     * @param Money $other
     * @return $this
     */
    public function min(Money $other): Money
    {
        return Money::create(min($this->amount, $other->value()));
    }
}
