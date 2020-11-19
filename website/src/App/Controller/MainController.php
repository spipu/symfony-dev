<?php
declare(strict_types = 1);

namespace App\Controller;

use Spipu\ConfigurationBundle\Exception\ConfigurationException;
use Spipu\ConfigurationBundle\Service\Manager as ConfigurationManager;
use Spipu\CoreBundle\Service\EncryptorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     * @param ConfigurationManager $configurationManager
     * @param EncryptorInterface $encryptor
     * @return Response
     * @throws ConfigurationException
     */
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
}
