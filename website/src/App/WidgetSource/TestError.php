<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\DashboardBundle\Entity\Source as Source;

class TestError extends AbstractSource
{
    public function getDefinition(): Source\SourceDql
    {
        return (new Source\SourceDql("test-error", 'App:User'))
            ->setDateField('fake_field')
        ;
    }

    /**
     * @return string[]
     */
    public function getRolesNeeded(): array
    {
        return ['ROLE_ADMIN'];
    }
}
