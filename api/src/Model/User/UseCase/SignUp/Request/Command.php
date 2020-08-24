<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $lastName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\EqualTo(propertyPath="password", message="Введенные пароли должны совпадать.")
     */
    public $passwordConfirmation;
}
