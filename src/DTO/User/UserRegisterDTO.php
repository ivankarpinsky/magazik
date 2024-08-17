<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDTO {
    public function __construct(

        #[Assert\NotBlank(message: "Name cannot be blank.")]
        #[Assert\Length(max: 255, maxMessage: "Name cannot be longer than 255 characters.")]
        public readonly string $name,

        #[Assert\NotBlank(message: "Phone cannot be blank.")]
        #[Assert\Length(max: 20, maxMessage: "Phone number cannot be longer than 20 characters.")]
        #[Assert\Regex(pattern: "/^\+?[0-9]*$/", message: "Phone number can only contain numbers and an optional leading '+'.")]
        public readonly string $phone,

        #[Assert\NotBlank(message: "Email cannot be blank.")]
        #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
        public readonly string $email,

        #[Assert\NotBlank(message: "Password cannot be blank.")]
        #[Assert\Length(min: 6, minMessage: "Password must be at least 6 characters long.")]
        public readonly string $password,

        #[Assert\NotBlank(message: "Password confirmation cannot be blank.")]
        #[Assert\Expression("this.password === this.passwordConfirmation", message: "Password confirmation does not match the password.")]
        public readonly string $passwordConfirmation,
    ) {
    }
}