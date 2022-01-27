<?php

declare(strict_types=1);

namespace Th3Mouk\SimpleAPI\Symfony\Attribute;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class DTO extends ParamConverter
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        string $class,
        string $parameter,
        string $converter = 'fos_rest.request_body',
        array $options = [],
    ) {
        $options = array_merge($options, [
            'deserializationContext' => [DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true],
        ]);
        parent::__construct($parameter, $class, $options, false, $converter);
    }
}
