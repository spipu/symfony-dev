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

namespace App\Tests\Functional;

use App\Controller\UserGridActionController;
use App\Event\UserGridSubscriber;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\CoversClass;
use Spipu\CoreBundle\Tests\WebTestCase;
use Spipu\UiBundle\Tests\UiWebTestCaseTrait;

#[AllowMockObjectsWithoutExpectations]
#[CoversClass(UserGridActionController::class)]
#[CoversClass(UserGridSubscriber::class)]
class UserGridActionTest extends WebTestCase
{
    use UiWebTestCaseTrait;

    public function testGridActionsAddedBySubscriber(): void
    {
        $client = static::createClient();
        $this->adminLogin($client, 'Users');

        $crawler = $client->clickLink('Users');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Row and mass actions are injected by App\Event\UserGridSubscriber, not shipped by the bundle
        $this->assertGreaterThan(0, $crawler->filter('span[data-grid-role="action"]:contains("Enable")')->count());
        $this->assertGreaterThan(0, $crawler->filter('span[data-grid-role="action"]:contains("Disable")')->count());
    }

    public function testRowEnableDisable(): void
    {
        $client = static::createClient();
        $this->adminLogin($client, 'Users');

        $client->request('GET', '/user/grid/enable/999999999');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->request('GET', '/user/grid/enable/1');
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCrawlerHasAlert($crawler, 'You can not enable yourself');

        $client->request('GET', '/user/grid/enable/2');
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertCrawlerHasAlert($crawler, 'The item has been enabled');

        $client->request('GET', '/user/grid/disable/2');
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertCrawlerHasAlert($crawler, 'The item has been disabled');
    }

    public function testMassEnableDisable(): void
    {
        $client = static::createClient();
        $this->adminLogin($client, 'Users');

        $userIds = [2, 3];

        $client->request('POST', '/user/grid/mass-enable', ['selected' => json_encode($userIds)]);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertEquals(
            1,
            $crawler->filter('div[role="alert"]:contains("' . count($userIds) . ' items have been enabled")')->count()
        );

        $client->request('POST', '/user/grid/mass-disable', ['selected' => json_encode($userIds)]);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertEquals(
            1,
            $crawler->filter('div[role="alert"]:contains("' . count($userIds) . ' items have been disabled")')->count()
        );

        // Can not disable yourself through a mass action
        $client->request('POST', '/user/grid/mass-disable', ['selected' => json_encode([1])]);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div[role="alert"]:contains("You can not disable yourself!")')->count());

        // An empty selection is rejected
        $client->request('POST', '/user/grid/mass-enable', ['selected' => json_encode([])]);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div[role="alert"]:contains("You must select at least one item")')->count());
    }
}
