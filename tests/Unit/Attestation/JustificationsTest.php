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

        $expected = [
            'travail',
            'culture',
            'sante',
            'famille',
            'handicap',
            'sport_animaux',
            'convocation',
            'missions',
            'enfants',
        ];

        $this->assertEquals($expected, $justifications->justificationsKeys());
    }

    public function testShortText()
    {
        $justifications = new Justifications();

        $this->assertEquals("Déplacement scolaire", $justifications->justificationShortText('enfants'));
    }

    public function testText()
    {
        $justifications = new Justifications();

        $this->assertEquals(
            "9. Déplacements pour chercher les enfants à l’école et à l’occasion de leurs activités périscolaires",
            $justifications->justificationText('enfants')
        );
    }

    public function testChoices()
    {
        $justifications = new Justifications();

        $expected = [
            'Déplacement professionnel' => 'travail',
            'Culture' => 'culture',
            'Santé' => 'sante',
            'Motif familial impérieux' => 'famille',
            'Déplacement des personnes en situation de handicap' => 'handicap',
            'Déplacement bref' => 'sport_animaux',
            'Convocation justice ou service public' => 'convocation',
            'Mission administrative' => 'missions',
            'Déplacement scolaire' => 'enfants',
        ];

        $this->assertSame($expected, $justifications->getChoices());
    }
}
