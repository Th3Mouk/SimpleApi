<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Service;

use Th3Mouk\SimpleAPI\Customer\Customer;
use Th3Mouk\SimpleAPI\Customer\Entity\Customer as CustomerEntity;
use Th3Mouk\SimpleAPI\Customer\Entity\Society as SocietyEntity;
use Th3Mouk\SimpleAPI\Customer\Model\Contact;
use Th3Mouk\SimpleAPI\Customer\Model\CustomerIdentifier;
use Th3Mouk\SimpleAPI\Customer\Model\SocietyIdentifier;
use Th3Mouk\SimpleAPI\Customer\Society;
use Th3Mouk\SimpleAPI\Persistence\CannotPersistObject;

final class SocietyCreation
{
    public function __construct(
        private Society $society,
        private Customer $customer,
    ) {
    }

    /**
     * @throws CannotPersistObject
     */
    public function withContactAdministrator(
        SocietyIdentifier $societyIdentifier,
        string $societyName,
        CustomerIdentifier $customerIdentifier,
        Contact $contact,
    ): void {
        $this->society->persist(SocietyEntity::create($societyIdentifier, $societyName));
        $this->customer->persist(CustomerEntity::fromProspection(
            $customerIdentifier,
            $contact->gender,
            $contact->lastName,
            $contact->email,
            $contact->firstName,
            $contact->phoneNumber,
        ));
    }
}
