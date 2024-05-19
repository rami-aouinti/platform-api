<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Form;

use App\Crm\Domain\Entity\CustomerRate;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CustomerRateForm
 *
 * @package App\Crm\Transport\Form
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class CustomerRateForm extends AbstractRateForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currency = null;

        if (!empty($options['data'])) {
            /** @var CustomerRate $rate */
            $rate = $options['data'];

            if (null !== $customer = $rate->getCustomer()) {
                $currency = $customer->getCurrency();
            }
        }

        $this->addFields($builder, $currency);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerRate::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'admin_customer_rate_edit',
            'attr' => [
                'data-form-event' => 'kimai.customerUpdate'
            ],
        ]);
    }
}