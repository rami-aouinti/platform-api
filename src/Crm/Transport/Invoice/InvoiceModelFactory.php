<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Invoice;

use App\Crm\Domain\Entity\Customer;
use App\Crm\Domain\Entity\InvoiceTemplate;
use App\Crm\Domain\Repository\Query\InvoiceQuery;
use App\Crm\Transport\Activity\ActivityStatisticService;
use App\Crm\Transport\Customer\CustomerStatisticService;
use App\Crm\Transport\Project\ProjectStatisticService;

/**
 * @package App\Crm\Transport\Invoice
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class InvoiceModelFactory
{
    public function __construct(
        private CustomerStatisticService $customerStatisticService,
        private ProjectStatisticService $projectStatisticService,
        private ActivityStatisticService $activityStatisticService
    ) {
    }

    public function createModel(InvoiceFormatter $formatter, Customer $customer, InvoiceTemplate $template, InvoiceQuery $query): InvoiceModel
    {
        $model = new InvoiceModel($formatter, $this->customerStatisticService, $this->projectStatisticService, $this->activityStatisticService);

        $model->setCustomer($customer);
        $model->setTemplate($template);
        $model->setQuery($query);

        return $model;
    }
}
