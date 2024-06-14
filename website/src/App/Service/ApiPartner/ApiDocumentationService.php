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

namespace App\Service\ApiPartner;

use Spipu\ApiPartnerBundle\Service\AbstractApiDocumentationService;

class ApiDocumentationService extends AbstractApiDocumentationService
{
    public const API_VERSION = '1.0.0';

    public function getVersion(): string
    {
        return self::API_VERSION;
    }

    protected function build(): void
    {
        $this
            ->addDocument(
                'general',
                'General Notes',
                'api_partner/doc/general.html.twig'
            )
        ;
    }
}
