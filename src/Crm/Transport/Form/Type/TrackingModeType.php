<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form\Type;

use App\Crm\Transport\Timesheet\TrackingModeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form field type to select the timesheet mode.
 */
final class TrackingModeType extends AbstractType
{
    public function __construct(
        private TrackingModeService $service
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = [];

        foreach ($this->service->getModes() as $mode) {
            $id = $mode->getId();
            $choices['timesheet.mode_' . $id] = $id;
        }

        $resolver->setDefaults([
            'label' => 'timesheet.mode',
            'choices' => $choices,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
