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

use App\Entity\DemoCustomer;
use Spipu\UiBundle\Entity\EntityInterface;
use Spipu\UiBundle\Entity\Form\Field;
use Spipu\UiBundle\Entity\Form\FieldSet;
use Spipu\UiBundle\Entity\Form\Form;
use Spipu\UiBundle\Service\Ui\Definition\EntityDefinitionInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormInterface;

class DemoCustomerForm implements EntityDefinitionInterface
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
        $this->definition = (new Form('demo_customer', DemoCustomer::class))
            ->addFieldSet(
                (new FieldSet('customer', 'app.demo_customer.fieldset.customer', 10))
                    ->setCssClass('col-12 col-md-6')
                    ->addField($this->createField('firstname', Type\TextType::class, 10, 'firstname'))
                    ->addField($this->createField('lastname', Type\TextType::class, 20, 'lastname'))
                    ->addField($this->createField('email', Type\EmailType::class, 30, 'email'))
                    ->addField($this->createField('phone', Type\TextType::class, 40, 'phone'))
                    ->addField(
                        $this->createField(
                            'birthday',
                            Type\DateType::class,
                            50,
                            'birthday',
                            ['required' => false, 'widget' => 'single_text', 'input' => 'datetime_immutable']
                        )
                    )
            )
            ->addFieldSet(
                (new FieldSet('activity', 'app.demo_customer.fieldset.activity', 20))
                    ->setCssClass('col-12 col-md-6')
                    ->addField(
                        $this->createField('lastOrderedAt', Type\DateTimeType::class, 10, 'last_ordered_at')
                            ->useHiddenInForm()
                    )
                    ->addField(
                        $this->createField('createdAt', Type\DateTimeType::class, 20, 'created_at')
                            ->useHiddenInForm()
                    )
                    ->addField(
                        $this->createField('updatedAt', Type\DateTimeType::class, 30, 'updated_at')
                            ->useHiddenInForm()
                    )
            )
        ;
    }

    private function createField(
        string $code,
        string $type,
        int $position,
        string $label,
        array $options = ['required' => true, 'trim' => true]
    ): Field {
        return new Field($code, $type, $position, ['label' => 'app.demo_customer.field.' . $label] + $options);
    }

    /**
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function setSpecificFields(FormInterface $form, ?EntityInterface $resource = null): void
    {
    }
}
