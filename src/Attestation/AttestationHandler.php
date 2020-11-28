<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use DateInterval;
use TCPDF;

class AttestationHandler
{
    private AttestationQRCode $attestationQRCode;
    private Justifications $justifications;

    public function __construct(AttestationQRCode $attestationQRCode, Justifications $justifications)
    {
        $this->attestationQRCode = $attestationQRCode;
        $this->justifications = $justifications;
    }

    public function generate(AttestationCommand $attestationCommand): void
    {
        date_default_timezone_set('Europe/Paris');

        $pdf = $this->createPdf();

        $pdf->AddPage();

        $txt = "ATTESTATION DE DÉPLACEMENT DÉROGATOIRE";
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
        $pdf->Ln(3);

        $pdf->SetFont('helvetica', 'I', 10);
        $txt = "En application du décret no 2020-1310 du 29 octobre 2020 prescrivant les mesures générales nécessaires
        pour faire face à l’épidémie de COVID-19 dans le cadre de l’état d’urgence sanitaire";
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 11);
        $txt = sprintf("Mme/M. : %s %s", $attestationCommand->userData->firstname,
            $attestationCommand->userData->lastname);
        $pdf->Write(0, $txt, '', 0, 'L', true);
        $pdf->Ln(1);

        $txt = sprintf("Né(e) le : %s à : %s", $attestationCommand->userData->birthday,
            $attestationCommand->userData->birthcity);
        $pdf->Write(0, $txt, '', 0, 'L', true);
        $pdf->Ln(1);

        $txt = sprintf(
            "Demeurant : %s %s %s",
            $attestationCommand->userData->street,
            $attestationCommand->userData->postalCode,
            $attestationCommand->userData->city
        );
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 11);
        $txt = "certifie que mon déplacement est lié au motif suivant (cocher la case) autorisé par le décret n°2020-1310 du 29 octobre 2020 prescrivant les mesures générales nécessaires pour faire face à l’épidémie de COVID-19 dans le cadre de l’état d’urgence sanitaire :";
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->Ln(1);
        $pdf->SetFont('helvetica', 'I', 8);
        $txt = "Note : Les personnes souhaitant bénéficier de l’une de ces exceptions doivent se munir s’il y a lieu, lors de leurs déplacements hors de leur domicile, d’un document leur permettant de justifier que le déplacement considéré entre dans le champ de l’une de ces exceptions";
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->Ln();

        foreach ($this->justifications->justificationsKeys() as $justificationsKey) {
            $this->writeJustification($justificationsKey, $attestationCommand, $pdf);
        }

        $pdf->setCellPaddings($left = 0, $top = '', $right = '', $bottom = '');

        $pdf->SetY(265);
        $txt = sprintf("Fait à : %s", $attestationCommand->userData->city);
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $attestationCommand->date->add(new DateInterval('PT1H'));
        $txt = sprintf("Le : %s", $attestationCommand->date->format('d/m/Y à H:i'));
        $pdf->Write(0, $txt, '', 0, 'L', true);
        $txt = "(Date et heure de début de sortie à mentionner obligatoirement)";
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->SetY(238);
        $pdf->setCellPaddings($left = 0, $top = 0, $right = 0, $bottom = 0);
        $qrcode = $this->attestationQRCode->fromCommand($attestationCommand);
        $img = '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $qrcode) . '" width="150">';
        $pdf->writeHTML($img, true, false, true, false, 'R');

        $pdf->AddPage();

        $qrcode = $this->attestationQRCode->fromCommand($attestationCommand);
        $img = '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '',
                $qrcode) . '" width="400">';
        $pdf->writeHTML($img, true, false, true, false, 'L');

        $pdf->Output($this->filename(), 'I');
    }

    private function createPdf(): TCPDF
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('JM Leroux');
        $pdf->SetTitle('Attestation de déplacement dérogatoire');
        $pdf->SetSubject('Attestation COVID19');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetFont('helvetica', 'B', 15);

        return $pdf;
    }

    private function writeJustification(string $justification, AttestationCommand $attestationCommand, TCPDF $pdf)
    {
        $imagePath = realpath(__DIR__ . '/../../public/images/unchecked.jpg');
        if (in_array($justification, $attestationCommand->justifications)) {
            $imagePath = realpath(__DIR__ . '/../../public/images/checked.jpg');
        }
        $pdf->Image($imagePath, $pdf->GetX(), $pdf->GetY(), 5, 5, 'JPG', '', 'L', true);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->setCellPaddings($left = '10', $top = '', $right = '', $bottom = '');
        $pdf->Write(0, $this->justifications->justificationText($justification), '', $fill = false, 'L', $ln = true);
        $pdf->Ln();
    }

    private function filename(): string
    {
        return sprintf('attestation-%s_%s.pdf', date('Y-m-d'), date('H-i'));
    }
}
