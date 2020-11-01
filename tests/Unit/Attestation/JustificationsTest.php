<?php

namespace Jmleroux\CovidAttestation\Tests\Unit\Attestation;

use Jmleroux\CovidAttestation\Attestation\Justifications;
use PHPUnit\Framework\TestCase;

/**
 * @author JM Leroux <jmleroux.pro@gmail.com>
 */
class JustificationsTest extends TestCase
{
    public function testKeys()
    {
        $justifications = new Justifications();

        $expectedKeys = [
            0 => 'pro',
            1 => 'shopping',
            2 => 'health',
            3 => 'family',
            4 => 'handicap',
            5 => 'leasure',
            6 => 'justice',
            7 => 'administrative',
            8 => 'school',
        ];

        $this->assertEquals($expectedKeys, $justifications->justificationsKeys());
    }

    public function testShortText()
    {
        $justifications = new Justifications();

        $this->assertEquals("Déplacement scolaire", $justifications->justificationShortText('school'));
    }

    public function testText()
    {
        $justifications = new Justifications();

        $this->assertEquals(
            "Déplacement pour chercher les enfants à l’école et à l’occasion de leurs activités périscolaires",
            $justifications->justificationText('school')
        );
    }

    public function testChoices()
    {
        $justifications = new Justifications();

        $expected = [
            'Déplacement professionnel' => 'pro',
            'Achats de première nécessité' => 'shopping',
            'Santé' => 'health',
            'Motif familial impérieux' => 'family',
            'Déplacement des personnes en situation de handicap' => 'handicap',
            'Déplacement bref' => 'leasure',
            'Justice ou service public' => 'justice',
            'Mission administrative' => 'administrative',
            'Déplacement scolaire' => 'school',
        ];
        $this->assertEquals($expected, $justifications->getChoices());
    }
}
