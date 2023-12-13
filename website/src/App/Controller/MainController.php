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

use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Spipu\CoreBundle\Service\EncryptorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route(path: '/', name: 'app_home', methods: 'GET')]
    public function home(
        ConfigurationManager $configurationManager,
        EncryptorInterface $encryptor
    ): Response {
        $originalValue  = 'My String To Encrypt';
        $encryptedValue = $encryptor->encrypt($originalValue);
        $decryptedValue = $encryptor->decrypt($encryptedValue);

        return $this->render(
            'main/home.html.twig',
            [
                'configuration' => [
                    'goodPassword' => $configurationManager->isPasswordValid('test.type.password', 'password'),
                    'encrypted'    => $configurationManager->get('test.type.encrypted'),
                    'decrypted'    => $configurationManager->getEncrypted('test.type.encrypted'),
                ],
                'encryptor' => [
                    'original'  => $originalValue,
                    'encrypted' => $encryptedValue,
                    'decrypted' => $decryptedValue,
                ],
            ]
        );
    }

    #[Route(path: '/test', name: 'app_test', methods: 'GET')]
    public function test(): Response
    {
        return $this->render('main/test.html.twig');
    }
}
