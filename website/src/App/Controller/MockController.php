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

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/mock')]
class MockController extends AbstractController
{
    #[Route(path: '/stock/', name: 'app_mock_stock', methods: 'POST')]
    public function stock(Request $request): Response
    {
        sleep(1);
        $queryString = json_decode($request->getContent(), true);
        if (!is_array($queryString)) {
            $queryString = [];
        }

        $list = [];
        if (array_key_exists('products', $queryString) && is_array($queryString['products'])) {
            $list = $queryString['products'];
        }

        $agency = '';
        if (array_key_exists('agency', $queryString) && is_array($queryString['agency'])) {
            $agency = $queryString['agency'];
        }

        $products = [];
        foreach ($list as $id) {
            $products[] = [
                'id'  => (int) $id,
                'qty' => rand(0, 100),
            ];
        }

        return $this->json(['agency' => $agency, 'products' => $products]);
    }
}
