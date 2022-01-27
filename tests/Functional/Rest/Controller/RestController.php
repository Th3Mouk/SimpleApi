<?php

declare(strict_types=1);

namespace Tests\Functional\Rest\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View as ViewAtt;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Tests\Functional\Rest\Command\Failing;
use Tests\Functional\Rest\Command\Full;
use Tests\Functional\Rest\SpyRestService;
use Th3Mouk\SimpleAPI\Symfony\Attribute\DTO;

#[AsController]
final class RestController
{
    public function __construct(private SpyRestService $spyService)
    {
    }

    #[DTO(Full::class, 'dto')]
    #[ViewAtt]
    #[Post('/test/command-deserialization-without-validation-attributes')]
    public function testCommandWithoutValidation(Full $dto): View
    {
        $this->spyService->spy($dto);

        return View::create(statusCode: 204);
    }

    #[DTO(Failing::class, 'dto')]
    #[ViewAtt]
    #[Post('/test/command-deserialization-with-validation-attributes')]
    public function testCommandWithValidation(Failing $dto, ConstraintViolationListInterface $validationErrors): View
    {
        $this->spyService->spy($dto);

        return View::create($validationErrors, 400);
    }
}
