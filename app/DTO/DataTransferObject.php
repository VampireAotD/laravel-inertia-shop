<?php

namespace App\DTO;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class DataTransferObject
{
    /**
     * Converts object to Array, JSON or XML
     *
     * @param string $type
     * @return array|\ArrayObject|bool|\Countable|float|int|string|\Traversable|null
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function convert(string $type = 'array')
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        switch ($type){
            case 'array':
                return $serializer->normalize($this);
            case 'json':
                return  $serializer->serialize($this, 'json');
            case 'xml':
                return  $serializer->serialize($this, 'xml');
        }

        return $serializer->normalize($this);
    }
}
