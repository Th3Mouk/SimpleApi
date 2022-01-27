<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Handler;

use Th3Mouk\SimpleAPI\Customer\Command\AddSocietyWithContact as Command;
use Th3Mouk\SimpleAPI\Customer\Model\Contact;
use Th3Mouk\SimpleAPI\Customer\Model\CustomerIdentifier;
use Th3Mouk\SimpleAPI\Customer\Model\SocietyIdentifier;
use Th3Mouk\SimpleAPI\Customer\Service\SocietyCreation;
use Th3Mouk\SimpleAPI\Persistence\CannotPersistObject;
use Th3Mouk\SimpleAPI\Symfony\Messenger\CommandHandler;

final class PersistNewSocietyWithAdministrator implements CommandHandler
{
    public function __construct(private SocietyCreation $societyCreation)
    {
    }

    /**
     * @throws CannotPersistObject
     */
    public function __invoke(Command $command): void
    {
        $this->societyCreation->withContactAdministrator(
            SocietyIdentifier::fromString($command->societyUuid),
            $command->societyName,
            CustomerIdentifier::fromString($command->contactUuid),
            Contact::create(
                $command->gender,
                $command->lastName,
                $command->email,
                $command->firstName,
                $command->phoneNumber,
            )
        );
    }
}
