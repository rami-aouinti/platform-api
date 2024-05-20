<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Form;

use App\Quiz\Domain\Entity\School;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SchoolType extends AbstractType
{
    private $translator;
    private $param;
    private $tokenStorage;

    public function __construct(TranslatorInterface $translator, ParameterBagInterface $param, TokenStorageInterface $tokenStorage)
    {
        $this->translator = $translator;
        $this->param = $param;
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('groups')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => School::class,
        ]);
    }
}
