<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mock")
 */
class MockController extends AbstractController
{
    /**
     * @Route(
     *     "/stock/",
     *     methods="POST"
     * )
     * @param Request $request
     * @return Response
     */
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
