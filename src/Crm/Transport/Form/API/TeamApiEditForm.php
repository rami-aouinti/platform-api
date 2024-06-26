<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form\API;

use App\Crm\Transport\Form\TeamEditForm;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @package App\Crm\Transport\Form\API
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TeamApiEditForm extends TeamEditForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->remove('users');
    }
}
