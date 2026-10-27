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
use Spipu\UiBundle\Entity\Grid;
use Spipu\UiBundle\Service\Ui\Definition\GridDefinitionInterface;

class DemoCustomerGrid implements GridDefinitionInterface
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
        $this->definition = (new Grid\Grid('demo_customer', DemoCustomer::class))
            ->setPager(new Grid\Pager([10, 20, 50], 20))
            ->addColumn(
                (new Grid\Column('id', 'app.demo_customer.field.id', 'id', 10))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('firstname', 'app.demo_customer.field.firstname', 'firstname', 20))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true, true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('lastname', 'app.demo_customer.field.lastname', 'lastname', 30))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true, true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('email', 'app.demo_customer.field.email', 'email', 40))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true, true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('phone', 'app.demo_customer.field.phone', 'phone', 50))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_TEXT))
                    ->setFilter(new Grid\ColumnFilter(true))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('birthday', 'app.demo_customer.field.birthday', 'birthday', 60))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_DATE))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('last_ordered_at', 'app.demo_customer.field.last_ordered_at', 'lastOrderedAt', 70))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_DATETIME))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('created_at', 'app.demo_customer.field.created_at', 'createdAt', 80))
                    ->setType(new Grid\ColumnType(Grid\ColumnType::TYPE_DATETIME))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->setDefaultSort('id')
            ->addRowAction(
                (new Grid\Action('show', 'spipu.ui.action.show', 10, 'app_demo_customer_show'))
                    ->setCssClass('primary')
                    ->setIcon('eye')
            )
            ->addRowAction(
                (new Grid\Action('edit', 'spipu.ui.action.edit', 20, 'app_demo_customer_edit'))
                    ->setCssClass('success')
                    ->setIcon('pen-to-square')
                    ->setDeniedIfPersonalDataHidden(true)
            )
            ->addMassAction(
                (new Grid\Action('count', 'app.demo_customer.action.count', 10, 'app_demo_customer_mass_count'))
                    ->setCssClass('info')
                    ->setIcon('calculator')
            )
        ;
    }
}
