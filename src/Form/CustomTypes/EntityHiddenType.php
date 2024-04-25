<?php


namespace App\Form\CustomTypes;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EntityHiddenType extends HiddenType implements DataTransformerInterface
{

    /** @var ManagerRegistry $dm */
    private $dm;

    /** @var string $entityClass */
    protected $entityClass;

    
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->dm = $doctrine;
    }


    public function transform($data): string
    {
        // Modified from comments to use instanceof so that base classes or interfaces can be specified
        if (null === $data || !$data instanceof $this->entityClass) {
            return '';
        }

        return $data->getId();
    }

    public function reverseTransform($data)
    {
        if (!$data) {
            return null;
        }

        $res = null;
        try {
            $rep = $this->dm->getRepository($this->entityClass);
            $res = $rep->findOneBy(array(
                "id" => $data
            ));
        }
        catch (\Exception $exception) {
            throw new TransformationFailedException($exception->getMessage());
        }

        if ($res === null) {
            throw new TransformationFailedException(sprintf('A %s with id "%s" does not exist!', $this->entityClass, $data));
        }

        return $res;
    }
}