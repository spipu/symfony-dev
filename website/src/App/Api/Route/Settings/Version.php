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

namespace App\Api\Route\Settings;

use App\Api\Route\AbstractRoute;
use Symfony\Component\HttpFoundation\Request;

// phpcs:disable Generic.Files.LineLength.TooLong
class Version extends AbstractRoute
{
    public function getDescription(): ?string
    {
        return "
            <p>Return the current version of the API.</p>
        ";
    }

    public function getRoutePattern(): string
    {
        return '/version';
    }

    public function getHttpMethod(): string
    {
        return Request::METHOD_GET;
    }
}
