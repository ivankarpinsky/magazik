<?php

namespace App\Factory;

use App\Entity\Order;
use App\Repository\OrderDeliveryTypeRepository;
use App\Repository\OrderStatusRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Order>
 */
final class OrderFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private OrderDeliveryTypeRepository $orderDeliveryTypeRepository, private OrderStatusRepository $orderStatusRepository)
    {
    }

    public static function class(): string
    {
        return Order::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'amount' => self::faker()->randomNumber(),
            'created_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'deliveryType' => $this->orderDeliveryTypeRepository->findOneBy([]),
            'phone' => self::faker()->text(64),
            'status' => $this->orderStatusRepository->findOneBy([]),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Order $order): void {})
        ;
    }
}
