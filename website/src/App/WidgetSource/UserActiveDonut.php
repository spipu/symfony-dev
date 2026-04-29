<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Doctrine\ORM\EntityManagerInterface;
use Spipu\DashboardBundle\Entity\Source\SourceFromDefinition;
use Spipu\DashboardBundle\Service\Ui\WidgetRequest;
use Spipu\DashboardBundle\Source\SourceDataDefinitionInterface;
use Spipu\UiBundle\Form\Options\YesNo;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserActiveDonut extends AbstractSource implements SourceDataDefinitionInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly YesNo $yesNoOptions,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function getDefinition(): SourceFromDefinition
    {
        return (new SourceFromDefinition('user-active-donut', $this))
            ->setDonutDisplay();
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
        $rows = $this->entityManager->getConnection()
            ->executeQuery('SELECT active, COUNT(*) AS nb FROM spipu_user GROUP BY active')
            ->fetchAllAssociative();

        $counts = [];
        foreach ($rows as $row) {
            $counts[(int) $row['active']] = (int) $row['nb'];
        }

        $values = [];
        foreach ($this->yesNoOptions->getOptions() as $key => $label) {
            $values[] = [
                'label' => $this->translator->trans($label),
                'value' => $counts[(int) $key] ?? 0,
            ];
        }

        return $values;
    }
}
