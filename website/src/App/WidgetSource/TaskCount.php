<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\ProcessBundle\Form\Options\Status;

class TaskCount extends AbstractSource
{
    /**
     * @var Status
     */
    private Status $statusOptions;

    /**
     * @param Status $statusOptions
     */
    public function __construct(Status $statusOptions)
    {
        $this->statusOptions = $statusOptions;
    }

    /**
     * @return Source\SourceSql
     */
    public function getDefinition(): Source\SourceSql
    {
        return (new Source\SourceSql("task-count", 'spipu_process_task'))
            ->addFilter(
                new Source\SourceFilter(
                    'status',
                    'spipu.configuration.scope.label',
                    'status',
                    $this->statusOptions,
                    true
                )
            );
    }
}
