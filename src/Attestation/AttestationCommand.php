<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use Symfony\Component\Validator\Constraints as Assert;

class AttestationCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $justification;
    public UserData $userData;
}
