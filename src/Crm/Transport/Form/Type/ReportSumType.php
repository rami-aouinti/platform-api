<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class ReportSumType extends AbstractType
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => true,
            'multiple' => false,
            'expanded' => true,
        ]);

        $resolver->setDefault('choices', function (Options $options) {
            $choices = [
                'stats.durationTotal' => 'duration',
            ];

            if ($this->authorizationChecker->isGranted('view_rate_other_timesheet')) {
                $choices['stats.amountTotal'] = 'rate';
                $choices['internalRate'] = 'internalRate';
            }

            return $choices;
        });
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
