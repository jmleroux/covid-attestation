<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

class Justifications
{
    private array $justifications = [
        'pro' => [
            'label' => "Déplacement professionnel",
            'description' => "Déplacements entre le domicile et le lieu d’exercice de l’activité professionnelle ou un établissement d’enseignement ou de formation, déplacements professionnels ne pouvant être différés, déplacements pour un concours ou un examen.",
        ],
        'shopping' => [
            'label' => "Achats de première nécessité",
            'description' => "Déplacements pour effectuer des achats de fournitures nécessaires à l'activité professionnelle, des achats de première nécessité³ dans des établissements dont les activités demeurent autorisées, le retrait de commande et les livraisons à domicile.",
        ],
        'health' => [
            'label' => "Santé",
            'description' => "Consultations, examens et soins ne pouvant être ni assurés à distance ni différés et l’achat de médicaments.",
        ],
        'family' => [
            'label' => "Motif familial impérieux",
            'description' => "Déplacements pour motif familial impérieux, pour l'assistance aux personnes vulnérables et précaires ou la garde d'enfants.",
        ],
        'handicap' => [
            'label' => "Déplacement des personnes en situation de handicap",
            'description' => "Déplacement des personnes en situation de handicap et leur accompagnant.",
        ],
        'leasure' => [
            'label' => "Déplacement bref",
            'description' => "Déplacements brefs, dans la limite d'une heure quotidienne et dans un rayon maximal d'un kilomètre autour du domicile, liés soit à l'activité physique individuelle des personnes, à l'exclusion de toute pratique sportive collective et de toute proximité avec d'autres personnes, soit à la promenade avec les seules personnes regroupées dans un même domicile, soit aux besoins des animaux de compagnie.",
        ],
        'justice' => [
            'label' => "Justice ou service public",
            'description' => "Convocation judiciaire ou administrative et pour se rendre dans un service public",
        ],
        'administrative' => [
            'label' => "Mission adminsitrative",
            'description' => "Participation à des missions d'intérêt général sur demande de l'autorité administrative",
        ],
        'school' => [
            'label' => "Déplacement scolaire",
            'description' => "Déplacement pour chercher les enfants à l’école et à l’occasion de leurs activités périscolaires",
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
