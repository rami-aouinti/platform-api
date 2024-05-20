<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Form;

use App\Quiz\Domain\Entity\Category;
use App\Quiz\Domain\Entity\Question;
use App\Quiz\Domain\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuestionType extends AbstractType
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
        switch ($options['form_type']) {
            case 'student_questioning':
            case 'student_marking':
                $builder->add('text', TextareaType::class, [
                    'label' => false,
                    'disabled' => true,
                    'attr' => [
                        'rows' => '7',
                    ],
                ]);
                $builder->add('answers', CollectionType::class, [
                    'label' => false,
                    'entry_type' => AnswerType::class,
                    'entry_options' => [
                        'label' => false,
                        'form_type' => $options['form_type'],
                    ],
                ]);
                break;
            case 'teacher':
                $builder->add('text', TextareaType::class, [
                    'label' => $this->translator->trans('Question wording'),
                ]);
                $builder->add('max_duration', IntegerType::class, [
                    'required' => false,
                    'label' => $this->translator->trans('Question max duration (seconds)'),
                ]);
                $builder->add('categories', EntityType::class, [
                    'class' => Category::class,
                    'query_builder' => function (CategoryRepository $repository) {
                        return $repository->createQueryBuilder('c')->andWhere('c.created_by = :created_by')->setParameter('created_by', $this->tokenStorage->getToken()->getUser())->andWhere('c.language = :language')->setParameter('language', $this->param->get('locale'))->orderBy('c.shortname', 'ASC');
                    },
                    'choice_label' => 'longname',
                    'multiple' => true,
                    'attr' => [
                        'size' => 30,
                    ],
                    // 'expanded' => true, // render check-boxes
                ]);
                $builder->add('answers', CollectionType::class, [
                    'entry_type' => AnswerType::class,
                    'entry_options' => [
                        'label' => false,
                        'form_type' => $options['form_type'],
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]);
                break;
            case 'admin':
                $builder->add('text', TextareaType::class, [
                    'label' => $this->translator->trans('Question wording'),
                ]);
                $builder->add('max_duration', IntegerType::class, [
                    'required' => false,
                    'label' => $this->translator->trans('Question max duration (seconds)'),
                ]);
                $builder->add('categories', EntityType::class, [
                    'class' => Category::class,
                    'query_builder' => function (CategoryRepository $repository) {
                        return $repository->createQueryBuilder('c')->andWhere('c.language = :language')->setParameter('language', $this->param->get('locale'))->orderBy('c.shortname', 'ASC');
                    },
                    'choice_label' => 'longname',
                    'multiple' => true,
                    'attr' => [
                        'size' => 3,
                    ],
                    //'expanded' => true, // render check-boxes
                ]);
                $builder->add('answers', CollectionType::class, [
                    'entry_type' => AnswerType::class,
                    'entry_options' => [
                        'label' => false,
                        'form_type' => $options['form_type'],
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]);
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'form_type' => 'student_questioning',
        ]);
    }
}
