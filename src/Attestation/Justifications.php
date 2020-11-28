<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

class Justifications
{
    private array $justifications = [
        'travail' => [
            'label' => "Déplacement professionnel",
            'description' => "1. Déplacements entre le domicile et le lieu d’exercice de l’activité professionnelle ou un établissement
d’enseignement ou de formation ; déplacements professionnels ne pouvant être différés ; déplacements pour un concours ou un examen ;
Note : A utiliser par les travailleurs non-salariés, lorsqu’ils ne peuvent disposer d’un justificatif de déplacement établi par leur employeur.",
        ],
        'culture' => [
            'label' => "Culture",
            'description' => "2. Déplacements pour se rendre dans un établissement culturel autorisé ou un lieu de culte ;
déplacements pour effectuer des achats de biens, pour des services dont la fourniture est autorisée, pour les retraits de commandes et les livraisons à domicile ;",
        ],
        'sante' => [
            'label' => "Santé",
            'description' => "3. Consultations, examens et soins ne pouvant être assurés à distance et achats de médicaments",
        ],
        'famille' => [
            'label' => "Motif familial impérieux",
            'description' => "4. Déplacements pour motif familial impérieux, pour l’assistance aux personnes vulnérables et précaires ou la garde d’enfants",
        ],
        'handicap' => [
            'label' => "Déplacement des personnes en situation de handicap",
            'description' => "5. Déplacement des personnes en situation de handicap et leur accompagnant.",
        ],
        'sport_animaux' => [
            'label' => "Déplacement bref",
            'description' => "6. Déplacements en plein air ou vers un lieu de plein air, sans changement du lieu de résidence, dans la limite de trois heures quotidiennes et dans un rayon maximal de vingt kilomètres autour du domicile, liés
soit à l’activité physique ou aux loisirs individuels, à l’exclusion de toute pratique sportive collective et de toute proximité avec d’autres personnes, soit à la promenade avec les seules personnes regroupées dans un même domicile, soit aux besoins des animaux de compagnie",
        ],
        'convocation' => [
            'label' => "Convocation justice ou service public",
            'description' => "7. Convocations judiciaires ou administratives et déplacements pour se rendre dans un service public",
        ],
        'missions' => [
            'label' => "Mission administrative",
            'description' => "8. Participation à des missions d’intérêt général sur demande de l’autorité administrative",
        ],
        'enfants' => [
            'label' => "Déplacement scolaire",
            'description' => "9. Déplacements pour chercher les enfants à l’école et à l’occasion de leurs activités périscolaires",
        ],
    ];

    public function justificationsKeys(): array
    {
        return array_keys($this->justifications);
    }

    public function justificationShortText(string $justification): string
    {
        return $this->justifications[$justification]['label'];
    }

    public function justificationText(string $justification): string
    {
        return $this->justifications[$justification]['description'];
    }

    public function getChoices(): array
    {
        $choices = [];

        foreach ($this->justifications as $key => $justification) {
            $choices[$justification['label']] = $key;
        }

        return $choices;
    }
}
