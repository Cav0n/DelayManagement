<?php
/*************************************************************************************/
/*      This file is part of the module CreditNote                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace DelayManagement\Loop;

use Carousel\Model\CarouselQuery;
use CreditNote\Model\CreditNoteQuery;
use CreditNote\Model\CreditNote as CreditNoteModel;
use CreditNote\Model\Map\CreditNoteStatusI18nTableMap;
use CreditNote\Model\Map\CreditNoteStatusTableMap;
use CreditNote\Model\Map\CreditNoteTypeI18nTableMap;
use CreditNote\Model\Map\CreditNoteTypeTableMap;
use DelayManagement\Model\OrderDelayQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Event\Image\ImageEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Log\Tlog;
use Thelia\Model\Map\CustomerTableMap;
use Thelia\Model\Map\OrderTableMap;
use Thelia\Type\EnumListType;
use Thelia\Type\EnumType;
use Thelia\Type\TypeCollection;

/**
 * Class DelayLoop
 * @package DelayManagement\Loop
 * @author Florian Bernard <fbernard@openstudio.fr>
 *
 * @method string[] getOrderId()
 * @method string[] getOrder()
 */
class DelayLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected $timestampable = true;

    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('order_id'),
            Argument::createEnumListTypeArgument(
                "order",
                [
                    'created_at', 'created_at_reverse'
                ],
                "created_at_reverse"
            )
        );
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \DelayManagement\Model\OrderDelay $delay */
        foreach ($loopResult->getResultDataCollection() as $delay) {
            $loopResultRow = new LoopResultRow($delay);

            $loopResultRow
                ->set('ID', $delay->getId())
                ->set('ORDER_ID', $delay->getOrderId())
                ->set('CREATED_AT', $delay->getCreatedAt())
                ->set('UPDATED_AT', $delay->getUpdatedAt())
                ->set('TYPE', $delay->getType())
                ->set('PRETTY_TYPE', $delay->getPrettyType())
                ->set('EMAIL_OBJECT', $delay->getEmailObject())
                ->set('EMAIL_TEXT', $delay->getEmailText())
                ->set('EMAIL_SENT_AT', $delay->getEmailSentAt())
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $search = OrderDelayQuery::create()->filterByOrderId($this->getOrderId());

        $search = $search->orderByCreatedAt(Criteria::DESC);

        return $search;
    }

    /**
     * @param OrderDelayQuery $query
     */
    protected function buildModelCriteriaOrder($query)
    {
        foreach ($this->getOrder() as $order) {
            switch ($this->getOrder()) {
                case "created_at":
                    return $query->orderByCreatedAt();
                case "created_at_reverse":
                    return $query->orderByCreatedAt(Criteria::DESC);
            }
        }

        return $query;
    }
}
