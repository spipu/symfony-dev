<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\ConfigurationBundle\Exception\ConfigurationException;
use Spipu\ConfigurationBundle\Form\Options\ScopeOptions;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\WidgetRequest;
use Spipu\DashboardBundle\Source\SourceDataDefinitionInterface;

class Test extends AbstractSource implements SourceDataDefinitionInterface
{
    /**
     * @var ScopeOptions
     */
    private ScopeOptions $scopeOptions;

    /**
     * @var ConfigurationManager
     */
    private ConfigurationManager $configurationManager;

    /**
     * @param ScopeOptions $scopeOptions
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ScopeOptions $scopeOptions, ConfigurationManager $configurationManager)
    {
        $this->scopeOptions = $scopeOptions;
        $this->configurationManager = $configurationManager;
    }

    /**
     * @return Source\SourceFromDefinition
     */
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

    /**
     * @param WidgetRequest $request
     * @return float
     */
    public function getValue(WidgetRequest $request): float
    {
        return 0.;
    }

    /**
     * @param WidgetRequest $request
     * @return float
     */
    public function getPreviousValue(WidgetRequest $request): float
    {
        return 0.;
    }

    /**
     * @param WidgetRequest $request
     * @return array
     */
    public function getValues(WidgetRequest $request): array
    {
        return [];
    }

    /**
     * @param WidgetRequest $request
     * @return array
     * @throws ConfigurationException
     */
    public function getSpecificValues(WidgetRequest $request): array
    {
        $scope = $request->getFilterValueString('scope');

        return [
            'conf' => $this->configurationManager->get('test.type.text', $scope),
            'scope' => $scope,
        ];
    }
}
