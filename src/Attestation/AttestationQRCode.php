<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use chillerlan\QRCode\QRCode;
use Twig\Environment;

class AttestationQRCode
{
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function fromCommand(AttestationCommand $attestationCommand): string
    {
        return (new QRCode)->render(
            $this->twig->render('attestation-text.html.twig', [
                'user_data' => $attestationCommand->userData->normalize(),
                'justifications' => $attestationCommand->justifications,
                'date' => $attestationCommand->date,
            ])
        );
    }
}
