<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Th3Mouk\SimpleAPI\Customer\Command\AddSocietyWithContact as Command;
use Th3Mouk\SimpleAPI\Customer\Controller\Dto\AddSocietyWithContact;
use Th3Mouk\SimpleAPI\Customer\View\SocietyCreatedWithAdministrator;
use Th3Mouk\SimpleAPI\Symfony\Attribute\DTO;

#[AsController]
#[Route('/society', 'society_')]
final class Society
{
    /**
     * @Security(name="Bearer")
     * @OA\RequestBody(
     *     request="AddSocietyWithContact",
     *     description="Add a new society with contact",
     *     @Model(type=AddSocietyWithContact::class)
     * )
     * @OA\Response(
     *     response="200",
     *     description="Create a new society with an administrator",
     *     @Model(type=SocietyCreatedWithAdministrator::class)
     * )
     */
    #[DTO(AddSocietyWithContact::class, 'dto'), View, Post(path: '/create', name: 'create')]
    public function create(AddSocietyWithContact $dto, MessageBusInterface $commandBus): SocietyCreatedWithAdministrator
    {
        $societyUuid       = Uuid::uuid4()->toString();
        $administratorUuid = Uuid::uuid4()->toString();

        $commandBus->dispatch(Command::fromAddSocietyWithContactDTO($societyUuid, $administratorUuid, $dto));

        return new SocietyCreatedWithAdministrator($societyUuid, $administratorUuid);
    }
}
