<?php

declare(strict_types=1);

namespace Webauthn\Tests\Unit\AttestationStatement;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Webauthn\AttestationStatement\AttestationStatement;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AuthenticatorData;
use Webauthn\TrustPath\EmptyTrustPath;

/**
 * @internal
 */
final class NoneAttestationStatementSupportTest extends TestCase
{
    #[Test]
    public function theAttestationStatementIsNotValid(): void
    {
        $support = new NoneAttestationStatementSupport();

        $attestationStatement = new AttestationStatement('none', [], '', EmptyTrustPath::create());
        $authenticatorData = new AuthenticatorData('', '', '', 0, null, null);

        static::assertSame('none', $support->name());
        static::assertTrue($support->isValid('FOO', $attestationStatement, $authenticatorData));
    }

    #[Test]
    public function theAttestationStatementIsValid(): void
    {
        $support = new NoneAttestationStatementSupport();

        $attestationStatement = new AttestationStatement('none', [
            'x5c' => ['FOO'],
        ], '', EmptyTrustPath::create());
        $authenticatorData = new AuthenticatorData('', '', '', 0, null, null);

        static::assertSame('none', $support->name());
        static::assertFalse($support->isValid('FOO', $attestationStatement, $authenticatorData));
    }
}
