<?php

declare(strict_types=1);

namespace App\Quiz\Transport\Form;

use App\Quiz\Domain\Entity\Category;
use App\Quiz\Domain\Entity\Quiz;
use App\Quiz\Domain\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuizType extends AbstractType
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
        $builder->add('title', TextType::class, [
            'label' => $this->translator->trans('Name'),
        ]);
        $builder->add('summary');
        $builder->add('number_of_questions');
        if ($options['isAdmin']) {
            $builder->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')->andWhere('c.language = :language')->setParameter('language', $this->param->get('locale'))->orderBy('c.shortname', 'ASC');
                },
                'choice_label' => 'longname',
                'multiple' => true,
                'attr' => [
                    'size' => 30,
                ],
                // 'expanded' => true, // render check-boxes
            ]);
        } else {
            $builder->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')->andWhere('c.created_by = :created_by')->setParameter('created_by', $this->tokenStorage->getToken()->getUser())->andWhere('c.language = :language')->setParameter('language', $this->param->get('locale'))->orderBy('c.shortname', 'ASC');
                },
                'choice_label' => 'longname',
                'multiple' => true,
                'attr' => [
                    'size' => 30,
                ],
                // 'expanded' => true, // render check-boxes
            ]);
        }

        $builder->add('start_quiz_comment');
        $builder->add('show_result_question');
        $builder->add('result_quiz_comment');
        $builder->add('allow_anonymous_workout');
        $builder->add('default_question_max_duration', IntegerType::class, [
            'label' => $this->translator->trans('Default question max duration (seconds)'),
        ]);
        $builder->add('active');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
            'form_type' => 'student_questioning',
            'isTeacher' => false,
            'isAdmin' => false,
        ]);
    }
}
