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
use Spipu\UiBundle\Entity\Grid;
use Spipu\UiBundle\Service\Ui\Definition\GridDefinitionInterface;

class DemoOrderGrid implements GridDefinitionInterface
{
    private ?Grid\Grid $definition = null;

    public function getDefinition(): Grid\Grid
    {
        if (!$this->definition) {
            $this->prepareGrid();
        }

        return $this->definition;
    }

    private function prepareGrid(): void
    {
        $this->definition = (new Grid\Grid('demo_order', DemoOrder::class))
            ->setPager(new Grid\Pager([10, 20, 50], 20))
            ->addColumn(
                (new Grid\Column('id', 'app.demo_order.field.id', 'id', 10))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('reference', 'app.demo_order.field.reference', 'reference', 20))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true, true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('status', 'app.demo_order.field.status', 'status', 30))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('amount', 'app.demo_order.field.amount', 'amount', 40))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_FLOAT))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('city', 'app.demo_order.field.city', 'city', 50))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('customer_name', 'app.demo_order.field.customer_name', 'customer.name', 60))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true, true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('customer_email', 'app.demo_order.field.customer_email', 'customer.email', 70))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true, true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('customer_phone', 'app.demo_order.field.customer_phone', 'customer.phone', 80))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column(
                    'personal_data_hidden_at',
                    'app.demo_order.field.personal_data_hidden_at',
                    'personalDataHiddenAt',
                    90
                ))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_DATETIME))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->setDefaultSort('id')
            ->addRowAction(
                (new Grid\Action('show', 'spipu.ui.action.show', 10, 'app_demo_order_show'))
                    ->setCssClass('primary')
                    ->setIcon('eye')
            )
            ->addRowAction(
                (new Grid\Action('contact', 'app.demo_order.action.contact', 20, 'app_demo_order_show'))
                    ->setCssClass('secondary')
                    ->setIcon('envelope')
                    ->setConditions(['customer.email' => ['nin' => [null, '']]])
            )
        ;
    }
}
