<?php

namespace App\Serializer;

use App\Entity\Book\Score;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ScoreNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer
    ) {
    }

    public function normalize($score, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($score, $format, $context);

        return $data['value'] ?? null;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Score;
    }
}