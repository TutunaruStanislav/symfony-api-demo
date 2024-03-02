<?php

declare(strict_types=1);

namespace App\Core\Validator;

use App\Core\Exception\CountryNotFoundException;
use App\Core\Model\Country;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Validator
{
    public static function validatePhone(mixed $object, ExecutionContextInterface $context): ExecutionContextInterface
    {
        /** @var Country[] $countries */
        $countries = $context->getObject()->countries;
        if (empty($countries)) {
            throw new CountryNotFoundException('Countries list is empty');
        }

        $phone = preg_replace('/\D/', '', $object);
        $match = null;
        foreach ($countries as $country) {
            $pattern = sprintf('/^%s\d{%s}$/', $country->getPhoneMask(),
                $country->getPhoneLength() - \strlen($country->getPhoneMask()));

            if ('398' === $country->getIsoCode() && preg_match($pattern, $phone) && str_starts_with($phone, '77')) {
                $match = $country->getIsoCode();
            }
            if (preg_match($pattern, $phone) || preg_match('/^8\d{10}$/', $phone)) {
                $match = $country->getIsoCode();
            }
        }

        if (null === $match) {
            $context->buildViolation('Invalid phone format')->addViolation();
        }

        return $context;
    }
}
