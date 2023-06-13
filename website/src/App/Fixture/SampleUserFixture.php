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

namespace App\Fixture;

use Doctrine\DBAL\Connection;
use Spipu\CoreBundle\Fixture\FixtureInterface;
use Spipu\UserBundle\Entity\UserInterface;
use Spipu\UserBundle\Repository\UserRepository;
use Spipu\UserBundle\Service\ModuleConfigurationInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Users Creation
 */
class SampleUserFixture implements FixtureInterface
{
    private UserPasswordHasherInterface $hasher;
    private ModuleConfigurationInterface $moduleConfiguration;
    private Connection $connection;
    private UserRepository $userRepository;
    private int $maxSteps = 500;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        ModuleConfigurationInterface $moduleConfiguration,
        Connection $connection,
        UserRepository $userRepository
    ) {
        $this->hasher = $hasher;
        $this->moduleConfiguration = $moduleConfiguration;
        $this->connection = $connection;
        $this->userRepository = $userRepository;
    }

    public function getCode(): string
    {
        return 'sample-user';
    }

    public function load(OutputInterface $output): void
    {
        $output->writeln("Add Sample Users");

        $data = $this->getData(1, 'password');
        if ($this->findObject($data['username'])) {
            $output->writeln("  => Already added");
            return;
        }

        $object = $this->moduleConfiguration->getNewEntity();
        $object
            ->setUsername($data['username'])
            ->setEmail($data['email'])
            ->setPassword($this->hasher->hashPassword($object, $data['password']));
        $password = $object->getPassword();
        unset($object);

        $maxPerStep = 1000;

        $progressBar = new ProgressBar($output, $this->maxSteps);
        $progressBar->minSecondsBetweenRedraws(5);
        $progressBar->start();

        for ($step = 0; $step < $this->maxSteps; $step++) {
            $progressBar->advance();
            $list = [];
            for ($key = 1; $key <= $maxPerStep; $key++) {
                $list[] = $this->getData($step * $maxPerStep + $key, $password);
            }
            $this->insertUsers($list);
        }
        $progressBar->finish();
        $output->writeln('');
    }

    public function remove(OutputInterface $output): void
    {
        $output->writeln("Remove Sample Users");

        $queryBuilder = $this->userRepository->createQueryBuilder('u');

        $queryBuilder->delete($this->moduleConfiguration->getEntityName(), 'u');
        $queryBuilder->where('u.username like :username');
        $queryBuilder->setParameter('username', 'user_%');

        $queryBuilder->getQuery()->execute();
    }

    private function findObject(string $identifier): ?UserInterface
    {
        return $this->userRepository->findOneBy(['username' => $identifier]);
    }

    protected function getData(int $key, string $password): array
    {
        return [
            'email'        => 'user_' . $key . '@test.fr',
            'username'     => 'user_' . $key,
            'password'     => $password,
            'first_name'   => 'User ' . $key,
            'last_name'    => 'User ' . $key,
            'roles'        => json_encode(['ROLE_USER']),
            'nb_login'     => 0,
            'nb_try_login' => 0,
            'active'       => 0,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];
    }

    public function getOrder(): int
    {
        return 9999;
    }

    private function insertUsers(array $list): void
    {
        $keys = array_keys($list[0]);
        foreach ($keys as &$key) {
            $key = $this->connection->quoteIdentifier($key);
        }

        foreach ($list as &$row) {
            foreach ($row as &$value) {
                if (is_string($value)) {
                    $value = $this->connection->quote($value);
                }
            }
            $row = '(' . implode(',', $row) . ')';
        }

        $query = sprintf(
            'INSERT INTO %1$s (%2$s) VALUES %3$s;',
            $this->connection->quoteIdentifier('spipu_user'),
            implode(',', $keys),
            implode(',', $list)
        );

        $this->connection->executeQuery($query);
    }

    public function setMaxSteps(int $maxSteps): self
    {
        $this->maxSteps = $maxSteps;

        return $this;
    }
}
