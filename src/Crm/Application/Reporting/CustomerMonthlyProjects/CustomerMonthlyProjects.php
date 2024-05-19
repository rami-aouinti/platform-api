<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Reporting\CustomerMonthlyProjects;

use App\Crm\Application\Reporting\AbstractUserList;
use App\Crm\Domain\Entity\Customer;

final class CustomerMonthlyProjects extends AbstractUserList
{
    private ?Customer $customer = null;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }
}
