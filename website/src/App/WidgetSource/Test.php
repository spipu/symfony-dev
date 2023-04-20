<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\ConfigurationBundle\Form\Options\ScopeOptions;
use Spipu\DashboardBundle\Entity\Source as Source;

class Test extends AbstractSource
{
    /**
     * @var ScopeOptions
     */
    private ScopeOptions $scopeOptions;

    /**
     * @param ScopeOptions $scopeOptions
     */
    public function __construct(ScopeOptions $scopeOptions)
    {
        $this->scopeOptions = $scopeOptions;
    }

    /**
     * @return Source\SourceSql
     */
    public function getDefinition(): Source\SourceSql
    {
        return (new Source\SourceSql("test", ''))
            ->setDateField(null)
            ->setSpecificDisplay('spider', 'dashboard/widget/test.html.twig')
            ->addFilter(
                new Source\SourceFilter(
                    'scope',
                    'spipu.configuration.scope.label',
                    '',
                    $this->scopeOptions
                )
            );
    }
}
