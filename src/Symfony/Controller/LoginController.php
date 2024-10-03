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

namespace Sylius\AdminUi\Symfony\Controller;

use Sylius\AdminUi\Symfony\Form\Type\LoginType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

final class LoginController
{
    public function __construct(
        private AuthenticationUtils $authenticationUtils,
        private FormFactoryInterface $formFactory,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $lastError = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        // TODO use a Twig component instead
        $formType = LoginType::class;
        $form = $this->formFactory->createNamed('', $formType);

        return new Response($this->twig->render('@SyliusAdminUi/security/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'last_error' => $lastError,
        ]));
    }
}
