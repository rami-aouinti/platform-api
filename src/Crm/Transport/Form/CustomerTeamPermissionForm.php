<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form;

use App\Crm\Domain\Entity\Customer;
use App\Crm\Transport\Form\Type\TeamType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @package App\Crm\Transport\Form
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class CustomerTeamPermissionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('teams', TeamType::class, [
            'multiple' => true,
            'expanded' => true,
            'by_reference' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'admin_customer_teams_edit',
            'attr' => [
                'data-form-event' => 'kimai.customerTeamUpdate',
            ],
        ]);
    }
}
