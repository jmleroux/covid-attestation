<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use Symfony\Component\Validator\Constraints as Assert;

class UserData
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=50)
     */
    public ?string $firstname;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=50)
     */
    public ?string $lastname;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=10)
     */
    public ?string $birthday;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=50)
     */
    public ?string $birthcity;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=100)
     */
    public ?string $street;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=5)
     */
    public ?string $postalCode;
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=50)
     */
    public ?string $city;

    public function normalize(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'birthday' => $this->birthday,
            'birthcity' => $this->birthcity,
            'street' => $this->street,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
        ];
    }
}
