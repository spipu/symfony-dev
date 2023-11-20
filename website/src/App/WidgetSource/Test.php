<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\ConfigurationBundle\Form\Options\ScopeOptions;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\Widget\WidgetRequest;
use Spipu\DashboardBundle\Source\SourceDataDefinitionInterface;

class Test extends AbstractSource implements SourceDataDefinitionInterface
{
    private ScopeOptions $scopeOptions;
    private ConfigurationManager $configurationManager;

    public function __construct(ScopeOptions $scopeOptions, ConfigurationManager $configurationManager)
    {
        $this->scopeOptions = $scopeOptions;
        $this->configurationManager = $configurationManager;
    }

    public function getDefinition(): Source\SourceFromDefinition
    {
        return (new Source\SourceFromDefinition("test", $this))
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

    public function getValue(WidgetRequest $request): float
    {
        return 0.;
    }

    public function getPreviousValue(WidgetRequest $request): float
    {
        return 0.;
    }

    public function getValues(WidgetRequest $request): array
    {
        return [];
    }

    public function getSpecificValues(WidgetRequest $request): array
    {
        $scope = $request->getFilterValueString('scope');

        return [
            'conf' => $this->configurationManager->get('test.type.text', $scope),
            'scope' => $scope,
        ];
    }
}
