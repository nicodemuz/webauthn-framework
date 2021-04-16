<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2020 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Webauthn\TokenBinding;

use function array_key_exists;
use Assert\Assertion;
use Base64Url\Base64Url;
use JetBrains\PhpStorm\Pure;
use function Safe\sprintf;

class TokenBinding
{
    public const TOKEN_BINDING_STATUS_PRESENT = 'present';
    public const TOKEN_BINDING_STATUS_SUPPORTED = 'supported';
    public const TOKEN_BINDING_STATUS_NOT_SUPPORTED = 'not-supported';

    private string $status;

    private ?string $id;

    public function __construct(string $status, ?string $id)
    {
        Assertion::false(self::TOKEN_BINDING_STATUS_PRESENT === $status && null === $id, 'The member "id" is required when status is "present"');
        $this->status = $status;
        $this->id = $id;
    }

    public static function createFormArray(array $json): self
    {
        Assertion::keyExists($json, 'status', 'The member "status" is required');
        $status = $json['status'];
        Assertion::inArray(
            $status,
            self::getSupportedStatus(),
            sprintf('The member "status" is invalid. Supported values are: %s', implode(', ', self::getSupportedStatus()))
        );
        $id = array_key_exists('id', $json) ? Base64Url::decode($json['id']) : null;

        return new self($status, $id);
    }

    #[Pure]
    public function getStatus(): string
    {
        return $this->status;
    }

    #[Pure]
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    #[Pure]
    private static function getSupportedStatus(): array
    {
        return [
            self::TOKEN_BINDING_STATUS_PRESENT,
            self::TOKEN_BINDING_STATUS_SUPPORTED,
            self::TOKEN_BINDING_STATUS_NOT_SUPPORTED,
        ];
    }
}
