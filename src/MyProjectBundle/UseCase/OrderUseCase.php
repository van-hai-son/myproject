<?php

namespace MyProjectBundle\UseCase;

use MyProjectBundle\Entity\Order;
use MyProjectBundle\Event\OrderEvent;
use MyProjectBundle\Event\PaymentEvent;
use MyProjectBundle\MyProjectBundle;
use MyProjectBundle\Repository\ElasticSearch\ProductRepository;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;

class OrderUseCase
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var TraceableEventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var OrderEvent
     */
    private $orderEvent;

    /**
     * @var PaymentEvent
     */
    private $paymentEvent;

    /**
     * OrderUseCase constructor.
     * @param ProductRepository $productRepository
     * @param TraceableEventDispatcher $eventDispatcher
     * @param OrderEvent $orderEvent
     * @param PaymentEvent $paymentEvent
     */
    public function __construct(
        ProductRepository $productRepository,
        TraceableEventDispatcher $eventDispatcher,
        OrderEvent $orderEvent,
        PaymentEvent $paymentEvent
    ) {
        $this->productRepository = $productRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->orderEvent = $orderEvent;
        $this->paymentEvent = $paymentEvent;
    }

    /**
     * @param array $request
     */
    public function createOrderUseCase($request)
    {
        $totalPrice = $this->productRepository->calculatePrice($request['order']);
        $request['status'] = Order::NOT_PAY;
        if (isset($request['is_paid']) && isset($request['card_info']) && $request['is_paid'] == MyProjectBundle::ENABLE
        ) {
            $request['card_info']['amount'] = $totalPrice;
            $this->paymentEvent->setRequest($request['card_info']);
            $this->eventDispatcher->dispatch('event.payment.create', $this->paymentEvent);
            $request['status'] = Order::IS_PAID;
        }
        $this->orderEvent->setRequest($request);
        $this->eventDispatcher->dispatch('event.order.create', $this->orderEvent);
    }
}
