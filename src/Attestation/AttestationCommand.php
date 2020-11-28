<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use Symfony\Component\Validator\Constraints as Assert;

class AttestationCommand
{
    public function __construct()
    {
        $this->date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
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
