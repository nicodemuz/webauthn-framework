<?php

declare(strict_types=1);

namespace Webauthn\Event;

use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\PublicKeyCredentialRequestOptions;

class AuthenticatorAssertionResponseValidationFailedEvent
{
    public function __construct(
        private readonly string $credentialId,
        private readonly AuthenticatorAssertionResponse $authenticatorAssertionResponse,
        private readonly PublicKeyCredentialRequestOptions $publicKeyCredentialRequestOptions,
        public readonly ServerRequestInterface|string $host,
        private readonly ?string $userHandle,
        private readonly Throwable $throwable
    ) {
        if ($host instanceof ServerRequestInterface) {
            trigger_deprecation(
                'web-auth/webauthn-lib',
                '4.5.0',
                sprintf(
                    'The class "%s" is deprecated since 4.5.0 and will be removed in 5.0.0. Please inject the host as a string instead.',
                    self::class
                )
            );
        }
    }

    public function getCredentialId(): string
    {
        return $this->credentialId;
    }

    public function getAuthenticatorAssertionResponse(): AuthenticatorAssertionResponse
    {
        return $this->authenticatorAssertionResponse;
    }

    public function getPublicKeyCredentialRequestOptions(): PublicKeyCredentialRequestOptions
    {
        return $this->publicKeyCredentialRequestOptions;
    }

    /**
     * @deprecated since 4.5.0 and will be removed in 5.0.0. Please use the `host` property instead
     */
    public function getRequest(): ServerRequestInterface|string
    {
        return $this->host;
    }

    public function getUserHandle(): ?string
    {
        return $this->userHandle;
    }

    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }
}
