<?php

declare(strict_types=1);

namespace Webauthn;

use JsonSerializable;
use ParagonIE\ConstantTime\Base64;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Webauthn\Exception\InvalidDataException;
use function array_key_exists;
use function is_string;

/**
 * @see https://www.w3.org/TR/webauthn/#sec-attested-credential-data
 */
class AttestedCredentialData implements JsonSerializable
{
    public function __construct(
        public AbstractUid $aaguid,
        public readonly string $credentialId,
        public readonly ?string $credentialPublicKey
    ) {
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getAaguid(): AbstractUid
    {
        return $this->aaguid;
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function setAaguid(AbstractUid $aaguid): void
    {
        $this->aaguid = $aaguid;
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getCredentialId(): string
    {
        return $this->credentialId;
    }

    /**
     * @deprecated since 4.7.0. Please use the property directly.
     */
    public function getCredentialPublicKey(): ?string
    {
        return $this->credentialPublicKey;
    }

    /**
     * @param mixed[] $json
     */
    public static function createFromArray(array $json): self
    {
        array_key_exists('aaguid', $json) || throw InvalidDataException::create(
            $json,
            'Invalid input. "aaguid" is missing.'
        );
        $aaguid = $json['aaguid'];
        is_string($aaguid) || throw InvalidDataException::create(
            $json,
            'Invalid input. "aaguid" shall be a string of 36 characters'
        );
        mb_strlen($aaguid, '8bit') === 36 || throw InvalidDataException::create(
            $json,
            'Invalid input. "aaguid" shall be a string of 36 characters'
        );
        $uuid = Uuid::fromString($aaguid);

        array_key_exists('credentialId', $json) || throw InvalidDataException::create(
            $json,
            'Invalid input. "credentialId" is missing.'
        );
        $credentialId = $json['credentialId'];
        is_string($credentialId) || throw InvalidDataException::create(
            $json,
            'Invalid input. "credentialId" shall be a string'
        );
        $credentialId = Base64::decode($credentialId, true);

        $credentialPublicKey = null;
        if (isset($json['credentialPublicKey'])) {
            $credentialPublicKey = Base64::decode($json['credentialPublicKey'], true);
        }

        return new self($uuid, $credentialId, $credentialPublicKey);
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        $result = [
            'aaguid' => $this->aaguid->__toString(),
            'credentialId' => base64_encode($this->credentialId),
        ];
        if ($this->credentialPublicKey !== null) {
            $result['credentialPublicKey'] = base64_encode($this->credentialPublicKey);
        }

        return $result;
    }
}
