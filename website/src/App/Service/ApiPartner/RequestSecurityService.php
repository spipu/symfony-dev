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

use Spipu\ApiPartnerBundle\Exception\SecurityException;
use Spipu\ApiPartnerBundle\Model\Request;
use Spipu\ApiPartnerBundle\Repository\PartnerRepositoryInterface;
use Spipu\ApiPartnerBundle\Service\RequestSecurityServiceInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestSecurityService implements RequestSecurityServiceInterface
{
    public const MAX_TIME_DELTA = 120;
    private PartnerRepositoryInterface $partnerRepository;

    public function __construct(PartnerRepositoryInterface $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
    }

    public function validate(Request $request, SymfonyRequest $symfonyRequest): void
    {
        $this->checkHeaders($request, $symfonyRequest);
        $this->validateRequest($request);
    }

    private function checkHeaders(Request $request, SymfonyRequest $symfonyRequest): void
    {
        $apiKey = (string) $symfonyRequest->headers->get('api-key', '');
        if ($apiKey === '') {
            throw new SecurityException(
                'API Key header is missing',
                SecurityException::ERROR_MISSING_API_KEY
            );
        }
        $request->setApiKey($apiKey);

        $requestTime = (int) $symfonyRequest->headers->get('api-request-time', '0');
        if ($requestTime < 1) {
            throw new SecurityException(
                'Request Time header is missing',
                SecurityException::ERROR_MISSING_REQUEST_TIME
            );
        }
        $request->setRequestTime($requestTime);

        $requestHash = (string) $symfonyRequest->headers->get('api-request-hash', '');
        if ($requestHash === '') {
            throw new SecurityException(
                'Request Hash header is missing',
                SecurityException::ERROR_MISSING_REQUEST_HASH
            );
        }
        $request->setRequestHash($requestHash);
    }

    private function validateRequest(Request $request): void
    {
        $partner = $this->partnerRepository->getPartnerByApiKey($request->getApiKey());
        if ($partner === null) {
            throw new SecurityException(
                'API Key header is invalid',
                SecurityException::ERROR_INVALID_API_KEY
            );
        }
        $request->setPartner($partner);

        if (abs($request->getRequestTime() - time()) > self::MAX_TIME_DELTA) {
            throw new SecurityException(
                'Request Time header is invalid',
                SecurityException::ERROR_INVALID_REQUEST_TIME
            );
        }

        $expectedHash = $this->calculateExpectedHash($request);
        if ($request->getRequestHash() !== $expectedHash) {
            throw new SecurityException(
                'Request Hash header is invalid',
                SecurityException::ERROR_INVALID_REQUEST_HASH
            );
        }

        if (!$partner->isApiEnabled()) {
            throw new SecurityException(
                'Partner API is not enabled',
                SecurityException::ERROR_INVALID_API_KEY
            );
        }
    }

    private function calculateExpectedHash(Request $request): string
    {
        $hashParts = [
            $request->getPartner()->getApiKey(),
            $request->getRequestTime(),
            $request->getPartner()->getApiSecretKey(),
        ];

        return hash('sha256', implode('', $hashParts));
    }
}
