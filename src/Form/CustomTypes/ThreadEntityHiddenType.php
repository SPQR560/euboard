<?php


namespace App\Form\CustomTypes;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class ThreadEntityHiddenType extends EntityHiddenType
{
    
    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct($doctrine);
    }

    /**
     *
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Set class, eg: App\Entity\RuleSet
        $this->entityClass = sprintf('App\Model\Thread\Entity\%s', ucfirst($builder->getName()));
        $builder->addModelTransformer($this);
    }

}