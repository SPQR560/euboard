<?php

namespace App\Form\CustomTypes;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EntityHiddenType extends HiddenType implements DataTransformerInterface
{
    private ManagerRegistry $dm;


    /**
     * @var class-string
     */
    protected string $entityClass;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->dm = $doctrine;
    }

    /**
     * @param mixed $data
     */
    public function transform($data): string
    {
        // Modified from comments to use instanceof so that base classes or interfaces can be specified
        if ($data === null || !$data instanceof $this->entityClass) {
            return '';
        }

        return $data->getId();
    }

    /**
     * @param mixed $data
     * @return mixed|object|null
     */
    public function reverseTransform($data)
    {
        if (!$data) {
            return null;
        }

        $res = null;
        try {
            $rep = $this->dm->getRepository($this->entityClass);
            $res = $rep->findOneBy([
                'id' => $data,
            ]);
        } catch (\Exception $exception) {
            throw new TransformationFailedException($exception->getMessage());
        }

        if (null === $res) {
            throw new TransformationFailedException(sprintf('A %s with id "%s" does not exist!', $this->entityClass, $data));
        }

        return $res;
    }
}
