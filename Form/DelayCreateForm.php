<?php
/*************************************************************************************/
/*      This file is part of the module AdminOrderCreation                           */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace DelayManagement\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Form\BaseForm;
use Thelia\Type\AlphaNumStringType;

/**
 * Class DelayCreateForm
 * @package DelayManagement\Form
 * @author Florian Bernard <fbernard@openstudio.fr>
 */
class DelayCreateForm extends BaseForm
{
    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'delay-create';
    }

    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add('order_id', HiddenType::class, array(
                'required' => true
            ))
            ->add('type', HiddenType::class, array(
                'required' => true
            ))
            ->add('send_email', HiddenType::class)
        ;

        $this->formBuilder
            ->add('email_object', 'text', array(
                'required' => false,
                'label' => 'Objet de l\'email'
            ))
            ->add('email_text', 'textarea', array(
                'required' => false,
                'label' => 'Texte de l\'email'
            ))
        ;
    }
}
