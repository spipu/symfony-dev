<?php

/**
 * This file is a demo file for Spipu Bundles
 *
 * (c) Laurent Minguet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Ui;

use App\Entity\DemoOrder;
use Spipu\UiBundle\Entity\EntityInterface;
use Spipu\UiBundle\Entity\Form\Field;
use Spipu\UiBundle\Entity\Form\FieldSet;
use Spipu\UiBundle\Entity\Form\Form;
use Spipu\UiBundle\Service\Ui\Definition\EntityDefinitionInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormInterface;

class DemoOrderForm implements EntityDefinitionInterface
{
    private ?Form $definition = null;

    public function getDefinition(): Form
    {
        if (!$this->definition) {
            $this->prepareForm();
        }

        return $this->definition;
    }

    private function prepareForm(): void
    {
        $this->definition = (new Form('demo_order', DemoOrder::class))
            ->addFieldSet(
                (new FieldSet('order', 'app.demo_order.fieldset.order', 10))
                    ->setCssClass('col-12 col-md-6')
                    ->addField($this->createField('reference', Type\TextType::class, 10, 'reference'))
                    ->addField($this->createField('status', Type\TextType::class, 20, 'status'))
                    ->addField($this->createField('amount', Type\TextType::class, 30, 'amount'))
                    ->addField($this->createField('city', Type\TextType::class, 40, 'city'))
                    ->addField(
                        $this->createField(
                            'personalDataHiddenAt',
                            Type\DateTimeType::class,
                            50,
                            'personal_data_hidden_at'
                        )
                    )
            )
            ->addFieldSet(
                (new FieldSet('customer', 'app.demo_order.fieldset.customer', 20))
                    ->setCssClass('col-12 col-md-6')
                    ->addField($this->createField('customer.name', Type\TextType::class, 10, 'customer_name'))
                    ->addField($this->createField('customer.email', Type\TextType::class, 20, 'customer_email'))
                    ->addField($this->createField('customer.phone', Type\TextType::class, 30, 'customer_phone'))
            )
        ;
    }

    private function createField(string $code, string $type, int $position, string $label): Field
    {
        return new Field($code, $type, $position, ['label' => 'app.demo_order.field.' . $label]);
    }

    /**
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function setSpecificFields(FormInterface $form, ?EntityInterface $resource = null): void
    {
    }
}
