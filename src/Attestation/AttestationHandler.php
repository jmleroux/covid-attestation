<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

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

        $pdf->SetFont('times', '', 10);
        $txt = "En application des mesures générales nécessaires pour faire face à l’épidémie de covid-19 dans le cadre de l’état d’urgence sanitaire.";
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        $pdf->Ln();

        $pdf->SetFont('times', '', 12);
        $txt = "Je soussigné(e),";
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $txt = sprintf("Mme/M. : %s %s", $attestationCommand->userData->firstname,
            $attestationCommand->userData->lastname);
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $txt = sprintf("Né(e) le : %s à : %s", $attestationCommand->userData->birthday,
            $attestationCommand->userData->birthcity);
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $txt = sprintf(
            "Demeurant : %s %s %s",
            $attestationCommand->userData->street,
            $attestationCommand->userData->postalCode,
            $attestationCommand->userData->city
        );
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->Ln();

        $pdf->SetFont('times', '', 12);
        $txt = "certifie que mon déplacement est lié au motif suivant (cocher la case) autorisé en application des mesures générales nécessaires pour faire face à l'épidémie de Covid19 dans le cadre de l'état d'urgence sanitaire ¹ :";
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->Ln();

        foreach ($this->justifications->justificationsKeys() as $justificationsKey) {
            $this->writeJustification($justificationsKey, $attestationCommand, $pdf);
        }

        $y = $pdf->GetY();
        $qrcode = $this->attestationQRCode->fromCommand($attestationCommand);
        $img = '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $qrcode) . '" width="150">';
        $pdf->writeHTML($img, true, false, true, false, 'R');

        $pdf->SetY($y);
        $txt = sprintf("Fait à : %s", $attestationCommand->userData->city);
        $pdf->Write(0, $txt, '', 0, 'L', true);
        $txt = sprintf("Le : %s à %s", date('d/m/Y'), date('H:i'));
        $pdf->Write(0, $txt, '', 0, 'L', true);
        $txt = "(Date et heure de début de sortie à mentionner obligatoirement)";
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->Ln();

        $txt = "Signature :";
        $pdf->Write(0, $txt, '', 0, 'L', true);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('times', 'I', 8);
        $txt = "¹ : Les personnes souhaitant bénéficier de l'une de ces exceptions doivent se munir s'il y a lieu, lors de leurs déplacements hors de leur domicile, d'un document leur permettant de justifier que le déplacement considéré entre dans le champ de l'une de ces exceptions.
² : A utiliser par les travailleurs non-salariés, lorsqu'ils ne peuvent disposer d'un justificatif de déplacement établi par leur employeur.
³ : Y compris les acquisitions à titre gratuit (distribution de denrées alimentaires...) et les déplacements liés à la perception de prestations sociales et au retrait d'espèces.";
        $pdf->Write(0, $txt, '', 0, 'L', true);

        $pdf->AddPage();

        $qrcode = $this->attestationQRCode->fromCommand($attestationCommand);
        $img = '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '',
                $qrcode) . '" width="400" align="center">';
        $pdf->writeHTML($img, true, false, true, false, 'C');

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
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetFont('times', 'BI', 20);

        return $pdf;
    }

    private function writeJustification(string $justification, AttestationCommand $attestationCommand, TCPDF $pdf)
    {
        $imagePath = realpath(__DIR__ . '/../../public/images/unchecked.jpg');
        if ($justification === $attestationCommand->justification) {
            $imagePath = realpath(__DIR__ . '/../../public/images/checked.jpg');
        }
        $pdf->Image($imagePath, $pdf->GetX(), $pdf->GetY(), 5, 5, 'JPG', '', 'L', true);

        $pdf->SetFont('times', '', 10);
        $pdf->setCellPaddings($left = '10', $top = '', $right = '', $bottom = '');
        $pdf->Write(0, $this->justifications->justificationText($justification), '', $fill = false, 'L', $ln = true);
        $pdf->Ln();
    }

    private function filename(): string
    {
        return sprintf('attestation-%s_%s.pdf', date('Y-m-d'), date('H-i'));
    }
}
