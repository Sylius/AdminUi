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

namespace Tests\Sylius\AdminUi\Integration\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sylius\AdminUi\Knp\Menu\MenuBuilder;
use Sylius\AdminUi\Knp\Menu\MenuBuilderInterface;
use Sylius\AdminUi\Symfony\DependencyInjection\Configuration;
use Sylius\AdminUi\Symfony\DependencyInjection\SyliusAdminUiExtension;
use Sylius\AdminUi\TwigHooks\Hookable\Metadata\RoutingHookableMetadataFactory;
use Symfony\Component\DependencyInjection\Reference;

final class SyliusAdminUiExtensionTest extends AbstractExtensionTestCase
{
    use ConfigurationTestCaseTrait;

    public function testItRegistersKnpMenuBuilder(): void
    {
        $this->load();

        $this->assertContainerBuilderHasService('sylius_admin_ui.knp.menu_builder', MenuBuilder::class);

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'sylius_admin_ui.knp.menu_builder',
            'knp_menu.menu_builder',
            [
                'method' => 'createMenu',
                'alias' => 'sylius_admin_ui.menu.sidebar',
            ],
        )
        ;

        $this->assertContainerBuilderHasAlias(MenuBuilderInterface::class, 'sylius_admin_ui.knp.menu_builder');
    }

    public function testItTwigHooksFactoryHookableMetadata(): void
    {
        $this->load();

        $this->assertContainerBuilderHasService('sylius_admin_ui.twig_hooks.factory.hookable_metadata', RoutingHookableMetadataFactory::class);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'sylius_admin_ui.twig_hooks.factory.hookable_metadata',
            0,
            new Reference('.inner'),
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'sylius_admin_ui.twig_hooks.factory.hookable_metadata',
            1,
            '%sylius_admin_ui.routing%',
        );
    }

    public function testItRegistersRoutingParameter(): void
    {
        $this->load([
            'routing' => [
                'login_path' => '/login',
                'logout_path' => '/logout',
                'dashboard_path' => '/admin',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius_admin_ui.routing', [
            'login_path' => '/login',
            'logout_path' => '/logout',
            'dashboard_path' => '/admin',
        ]);
    }

    public function testItThrowsAnErrorWhenTryingToRegisterNonStringRoutingPath(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'routing' => [
                        'login_path' => false,
                    ],
                ],
            ],
            'Invalid configuration for path "sylius_admin_ui.routing": Path must be a string. "bool" given.',
        );
    }

    protected function getContainerExtensions(): array
    {
        return [
            new SyliusAdminUiExtension(),
        ];
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
