<?php

namespace Jmleroux\CovidAttestation\Tests\Integration\Controller;

use Jmleroux\CovidAttestation\Attestation\UserData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author JM Leroux <jmleroux.pro@gmail.com>
 */
class DefaultControllerTest extends WebTestCase
{
    public function testIndexEmpty()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertStringNotContainsString(
            "Vous pouvez générer vos attestations de déplacement en suivant cette url d'accès personnalisée",
            $response->getContent()
        );
        $this->assertStringContainsString(
            "En application du décret n°2020-1310 du 29 octobre 2020 prescrivant les mesures
        générales nécessaires pour faire face à l'épidémie de Covid19 dans le cadre de l'état d'urgence sanitaire",
            $response->getContent()
        );
    }

    public function testIndexWithData()
    {
        $userData = new UserData();
        $userData->firstname = 'JM';
        $userData->lastname = 'LEROUX';
        $userData->birthday = '15/01/1980';
        $userData->birthcity = 'Paris';
        $userData->street = '21 Jump Street';
        $userData->postalCode = '75000';
        $userData->city = 'Paris';

        $queryString = http_build_query([
            'user_form' => $userData->normalize(),
        ]);

        $client = static::createClient();

        $client->request('GET', '/?' . $queryString);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString(
            "Vous pouvez générer vos attestations de déplacement en suivant cette url d'accès personnalisée",
            $response->getContent()
        );
        $this->assertStringContainsString(
            "http://localhost/attestation?user_form%5Bfirstname%5D=JM&amp;user_form%5Blastname%5D=LEROUX&amp;user_form%5Bbirthday%5D=15/01/1980&amp;user_form%5Bbirthcity%5D=Paris&amp;user_form%5Bstreet%5D=21%20Jump%20Street&amp;user_form%5BpostalCode%5D=75000&amp;user_form%5Bcity%5D=Paris",
            $response->getContent()
        );
    }

    public function testAttestationEmpty()
    {
        $client = static::createClient();

        $client->request('GET', '/attestation');
        $this->assertResponseRedirects('/');
    }


    public function testAttestationWithData()
    {
        $userData = new UserData();
        $userData->firstname = 'JM';
        $userData->lastname = 'LEROUX';
        $userData->birthday = '15/01/1980';
        $userData->birthcity = 'Paris';
        $userData->street = '21 Jump Street';
        $userData->postalCode = '75000';
        $userData->city = 'Paris';

        $queryString = http_build_query([
            'user_form' => $userData->normalize(),
        ]);

        $client = static::createClient();

        $client->request('GET', '/attestation?' . $queryString);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString(
            "Modifier vos informations et votre URL personnalisée",
            $response->getContent()
        );
    }

}
