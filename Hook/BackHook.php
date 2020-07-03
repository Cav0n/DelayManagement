<?php

namespace DelayManagement\Hook;

use DelayManagement\Model\Base\OrderDelay;
use DelayManagement\Model\Base\OrderDelayQuery;
use PayPal\Model\PaypalOrderQuery;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class BackHook extends BaseHook
{
    public function onOrderEditBottom(HookRenderEvent $event)
    {
        $orderId = $event->getArgument('order_id');

        $params = ['order_id' => $orderId];

        $event->add(
            $this->render(
                "order-bottom.html",
                $params
            )
        );
    }

    public function onOrderEditJs(HookRenderEvent $event)
    {
        $templateData = $event->getArguments();

        $event->add(
            $this->render(
                'order-edit-js.html',
                $templateData
            )
        );
    }
}
