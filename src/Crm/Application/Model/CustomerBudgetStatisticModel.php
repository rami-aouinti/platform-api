<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Model;

use App\Crm\Domain\Entity\Customer;

/**
 * Object used to unify the access to budget data in charts.
 *
 * @internal do not use in plugins, no BC promise given!
 * @method Customer getEntity()
 */
class CustomerBudgetStatisticModel extends BudgetStatisticModel
{
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);
    }

    public function getCustomer(): Customer
    {
        return $this->getEntity();
    }
}
