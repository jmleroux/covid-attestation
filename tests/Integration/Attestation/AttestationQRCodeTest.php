<?php

namespace Jmleroux\CovidAttestation\Tests\Integration\Attestation;

use Jmleroux\CovidAttestation\Attestation\AttestationCommand;
use Jmleroux\CovidAttestation\Attestation\AttestationQRCode;
use Jmleroux\CovidAttestation\Attestation\UserData;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author JM Leroux <jmleroux.pro@gmail.com>
 */
class AttestationQRCodeTest extends KernelTestCase
{
    public function testGenerateQRCode()
    {
        self::bootKernel();
        $qrcode = new AttestationQRCode(self::$container->get('twig'));

        $userData = new UserData();
        $userData->firstname = 'JM';
        $userData->lastname = 'LEROUX';
        $userData->birthday = '15/01/1980';
        $userData->birthcity = 'Paris';
        $userData->street = '21 Jump Street';
        $userData->postalCode = '75000';
        $userData->city = 'Paris';

        $command = new AttestationCommand();
        $command->userData = $userData;
        $command->justification = 'school';

        $result = $qrcode->fromCommand($command);

        // Not super strict test, but I don't know how to do better
        $this->assertStringStartsWith(
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAR0AAAEdCAIAAAC+CCQsAAAABnRSTlMA/wD/',
            $result
        );
    }
}
