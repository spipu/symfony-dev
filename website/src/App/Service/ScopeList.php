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

namespace App\Service;

use Spipu\ConfigurationBundle\Entity\Scope;
use Spipu\ConfigurationBundle\Service\ScopeListInterface;

class ScopeList implements ScopeListInterface
{
    /**
     * @return Scope[]
     */
    public function getAll(): array
    {
        return [
            new Scope('fr', 'Français'),
            new Scope('en', 'English'),
        ];
    }
}
