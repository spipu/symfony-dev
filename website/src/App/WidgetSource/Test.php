<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\DashboardBundle\Entity\Source as Source;

class Test extends AbstractSource
{
    /**
     * @return Source\SourceSql
     */
    public function getDefinition(): Source\SourceSql
    {
        return (new Source\SourceSql("test", ''))
            ->setDateField(null)
            ->setSpecificDisplay('spider', 'dashboard/widget/test.html.twig');
    }
}
