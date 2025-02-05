<?php

declare(strict_types=1);

namespace Webauthn\Tests\Unit\AttestationStatement;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Webauthn\AttestationStatement\AttestationObject;
use Webauthn\AttestationStatement\AttestationStatement;
use Webauthn\AuthenticatorData;
use Webauthn\TrustPath\EmptyTrustPath;

/**
 * @internal
 */
final class AttestationObjectTest extends TestCase
{
    #[Test]
    public function anAttestationObjectCanBeCreated(): void
    {
        $attestationStatement = new AttestationStatement('', [], '', emptyTrustPath::create());
        $authenticatorData = new AuthenticatorData('', '', '', 0, null, null);

        $object = new AttestationObject('rawAttestationObject', $attestationStatement, $authenticatorData);

        static::assertSame('rawAttestationObject', $object->rawAttestationObject);
        static::assertInstanceOf(AttestationStatement::class, $object->attStmt);
        static::assertInstanceOf(AuthenticatorData::class, $object->authData);
    }
}
