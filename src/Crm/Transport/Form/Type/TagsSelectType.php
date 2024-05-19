<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form\Type;

use App\Crm\Application\Utils\Color;
use App\Crm\Domain\Entity\Tag;
use App\Crm\Domain\Repository\Query\TagFormTypeQuery;
use App\Crm\Domain\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form field type to select a tag.
 */
final class TagsSelectType extends AbstractType
{
    public function __construct(
        private readonly TagRepository $tagRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['allow_create']) {
            return;
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            /** @var array<string> $tagIds */
            $tagIds = $event->getData();
            if (!\is_array($tagIds)) {
                return;
            }

            $tags = [];
            foreach ($tagIds as $tagId) {
                $tag = null;

                if (is_numeric($tagId)) {
                    $tag = $this->tagRepository->find($tagId);
                }

                if ($tag === null) {
                    $tag = $this->tagRepository->findTagByName($tagId);
                }

                if ($tag === null) {
                    $tag = new Tag();
                    $tag->setName(mb_substr($tagId, 0, 100));
                    $this->tagRepository->saveTag($tag);
                }

                $tags[] = $tag->getId();
            }

            $event->setData($tags);
        }, 1000);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multiple' => true,
            'class' => Tag::class,
            'label' => 'tag',
            'allow_create' => false,
            'choice_attr' => function (Tag $tag) {
                $color = $tag->getColor();
                if ($color === null) {
                    $color = (new Color())->getRandom($tag->getName());
                }

                return [
                    'data-color' => $color,
                ];
            },
            'choice_label' => function (Tag $tag) {
                return $tag->getName();
            },
            'attr' => [
                'data-renderer' => 'color',
            ],
        ]);

        $resolver->setDefault('query_builder', function (Options $options) {
            return function (TagRepository $repo) use ($options) {
                $query = new TagFormTypeQuery();
                $query->setUser($options['user']);

                return $repo->getQueryBuilderForFormType($query);
            };
        });

        $resolver->setAllowedTypes('allow_create', 'bool');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['allow_create']) {
            $view->vars['attr'] = array_merge($view->vars['attr'], [
                'data-create' => 'post_tag',
            ]);
        }
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
