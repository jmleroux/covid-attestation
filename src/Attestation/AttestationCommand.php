<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use Symfony\Component\Validator\Constraints as Assert;

class AttestationCommand
{
    public function __construct()
    {
        $this->date = new \DateTime();
        // Let’s say you did that 10 minutes ago, ok ?
        $this->date->sub(new \DateInterval('PT10M'));
    }

    /**
     * @Assert\NotBlank
     */
    public array $justifications;
    public UserData $userData;
    /**
     * @Assert\NotBlank
     */
    public \DateTime $date;
}
