<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Fixtures\Order;

trait OrderFixtures
{
    public function getOrderFixture(string $name): array
    {
        return \json_decode(\file_get_contents(__DIR__."/data/$name.json"), true);
    }
}
