<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\AdminUi\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TemplatesTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testBaseTemplate(): void
    {
        $this->client->request('GET', '/base');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('title', '');
    }

    public function testIndexTemplate(): void
    {
        $this->client->request('GET', '/books');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('[data-test-title]', 'Books');
        self::assertSelectorTextContains('[data-test-description]', 'List of books');
        self::assertSelectorTextContains('[data-test-book-name-shinning]', 'Shinning');
        self::assertSelectorTextContains('[data-test-book-name-carrie]', 'Carrie');
    }

    public function testCreateTemplate(): void
    {
        $this->client->request('GET', '/books/new');

        self::assertResponseIsSuccessful();
    }

    public function testUpdateTemplate(): void
    {
        $this->client->request('GET', '/books/shinning/edit');

        self::assertResponseIsSuccessful();
    }
}
