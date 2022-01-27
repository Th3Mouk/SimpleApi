<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Customer\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Th3Mouk\SimpleAPI\Customer\View\CustomerProfile;

#[AsController]
final class PersonalInformation
{
    #[View]
    #[Route('/profile', name: 'customer_get_profile', methods: ['GET', 'HEAD'])]
    public function personalInfos(): CustomerProfile
    {
        return new CustomerProfile();
    }
}
