<?php

/**
 * This file is part of a Spipu Bundle
 *
 * (c) Laurent Minguet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Step;

use Doctrine\ORM\EntityManagerInterface;
use Spipu\ProcessBundle\Entity\Process\ParametersInterface;
use Spipu\ProcessBundle\Exception\StepException;
use Spipu\ProcessBundle\Service\FileExportManager;
use Spipu\ProcessBundle\Service\LoggerInterface;
use Spipu\ProcessBundle\Step\StepInterface;

class ExportUsers implements StepInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(ParametersInterface $parameters, LoggerInterface $logger): string
    {
        $fileExport = $parameters->get('file_export');
        if (!($fileExport instanceof FileExportManager)) {
            throw new StepException('The parameter [file_export] must be the result of the step [PrepareExportFile]');
        }

        $query = "SELECT * FROM spipu_user LIMIT 1000";
        $rows = $this->entityManager->getConnection()->executeQuery($query)->fetchAllAssociative();
        $first = true;

        $filename = $fileExport->getLocalFilePath();
        $file = fopen($filename, 'w');
        try {
            foreach ($rows as $row) {
                if ($first) {
                    fputcsv($file, array_keys($row));
                    $first = false;
                }
                fputcsv($file, array_values($row));
            }
        } finally {
            fclose($file);
        }

        return $filename;
    }
}
