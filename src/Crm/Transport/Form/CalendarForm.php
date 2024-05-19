<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form;

use App\Crm\Transport\Form\Type\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CalendarForm
 *
 * @package App\Crm\Transport\Form
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class CalendarForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('user', UserType::class, [
            'required' => false,
            'attr' => ['onchange' => 'this.form.submit()']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method' => 'GET',
        ]);
    }
}
