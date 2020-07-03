<?php
/*************************************************************************************/
/*      This file is part of the module AdminOrderCreation                           */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace DelayManagement\Controller;

use AdminComment\Events\AdminCommentEvent;
use AdminComment\Events\AdminCommentEvents;
use AdminOrderCreation\AdminOrderCreation;
use AdminOrderCreation\Util\Calc;
use AdminOrderCreation\Util\CriteriaSearchTrait;
use ColissimoLabel\Exception\Exception;
use CreditNote\Model\CreditNote;
use CreditNote\Model\CreditNoteAddress;
use CreditNote\Model\CreditNoteDetail;
use CreditNote\Model\CreditNoteQuery;
use CreditNote\Model\CreditNoteStatusQuery;
use CreditNote\Model\CreditNoteTypeQuery;
use CreditNote\Model\OrderCreditNote;
use DelayManagement\Model\OrderDelay;
use InvoiceRef\EventListeners\OrderListener;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Propel;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Template\Loop\ProductSaleElements;
use Thelia\Model\AddressQuery;
use Thelia\Model\Cart;
use Thelia\Model\ConfigQuery;
use Thelia\Model\CountryQuery;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\Customer;
use Thelia\Model\CustomerQuery;
use Thelia\Model\Map\AddressTableMap;
use Thelia\Model\Map\OrderTableMap;
use Thelia\Model\Map\ProductI18nTableMap;
use AdminOrderCreation\Model\Order;
use Symfony\Component\Form\Form;
use Thelia\Model\OrderAddress;
use Thelia\Model\OrderProduct;
use Thelia\Model\OrderProductAttributeCombination;
use Thelia\Model\OrderProductTax;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatusQuery;
use Thelia\Model\Product;
use Thelia\Model\ProductI18n;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElementsQuery;
use Thelia\Model\TaxRuleI18n;
use Thelia\Tools\I18n;
use Thelia\Tools\URL;

class DelayController extends BaseAdminController
{
    public function ajaxModalCreateAction(Request $request)
    {
        $delay = new OrderDelay();

        $form = $this->createForm('delay-create', 'form', [], ['csrf_protection' => false]);
        $formValidate = $this->validateForm($form, 'post');
        $data = $formValidate->getData();

        $order = OrderQuery::create()->findOneById($data['order_id']);

        $delay->setOrderId($order->getId());
        $delay->setType($data['type']);

        if ($data['send_email']) {
            $delay->setEmailText(nl2br($data['email_text']));
            $delay->setEmailObject($data['email_object']);
            $delay->setEmailSentAt(date("Y-m-d H:i:s.u"));


            $this->getMailer()->sendEmailToCustomer(
                'delay',
                $order->getCustomer(),
                ['message' => nl2br($data['email_text']), 'object' => $data['email_object']]
            );
        }

        $delay->save();

        $adminComment = new AdminCommentEvent();
        $adminComment->setElementKey('order');
        $adminComment->setElementId($order->getId());
        $adminComment->setAdminId($this->getSession()->getAdminUser()->getId);
        $adminComment->setComment('Retard ajouté pour la commande : ' . $delay->getPrettyType());

        $this->dispatch(AdminCommentEvents::CREATE, $adminComment);

        return $this->generateRedirect('/admin/order/update/' . $delay->getOrderId() . '#modules');
    }
}
