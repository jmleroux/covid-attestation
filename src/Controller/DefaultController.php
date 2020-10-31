<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Controller;

use Jmleroux\CovidAttestation\Attestation\AttestationCommand;
use Jmleroux\CovidAttestation\Attestation\AttestationForm;
use Jmleroux\CovidAttestation\Attestation\AttestationHandler;
use Jmleroux\CovidAttestation\Attestation\UserData;
use Jmleroux\CovidAttestation\Attestation\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function userData(Request $request): Response
    {
        $userData = new UserData();
        $userForm = $this->createForm(UserForm::class, $userData);

        $url = '';
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $url = $this->get('router')->generate(
                'attestation',
                [
                    'user_form' => $userData->normalize(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        return $this->render('form.html.twig', [
            'user_form' => $userForm->createView(),
            'url' => $url,
        ]);
    }

    /**
     * @Route("/attestation", name="attestation", methods={"GET"})
     */
    public function attestationChoice(Request $request): Response
    {
        $userData = new UserData();
        $userForm = $this->createForm(UserForm::class, $userData);

        $userForm->handleRequest($request);
        if (!$userForm->isValid()) {
            $this->redirectToRoute('index');
        }

        $attestationCommand = new AttestationCommand();
        $attestationCommand->userData = $userData;
        $attestationForm = $this->createForm(AttestationForm::class, $attestationCommand, [
            'action' => $this->generateUrl('generate'),
        ]);
        $attestationForm->handleRequest($request);

        return $this->render('generate_attestation.html.twig', [
            'user_data' => $userData->normalize(),
            'attestation_form' => $attestationForm->createView(),
        ]);
    }

    /**
     * @Route("/generate", name="generate", methods={"POST"})
     */
    public function generateAttestation(
        Request $request,
        AttestationHandler $attestationHandler
    ): Response {
        $attestationCommand = new AttestationCommand();
        $attestationForm = $this->createForm(AttestationForm::class, $attestationCommand);
        $attestationForm->handleRequest($request);

        if ($attestationForm->isSubmitted() && $attestationForm->isValid()) {
            $attestationHandler->generate($attestationCommand);
        }

        return $this->redirectToRoute('index');
    }
}
