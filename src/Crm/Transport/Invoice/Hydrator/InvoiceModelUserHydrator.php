<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Invoice\Hydrator;

use App\Crm\Transport\Invoice\InvoiceModel;
use App\Crm\Transport\Invoice\InvoiceModelHydrator;

final class InvoiceModelUserHydrator implements InvoiceModelHydrator
{
    public function hydrate(InvoiceModel $model): array
    {
        $user = $model->getUser();

        if ($user === null) {
            return [];
        }

        $values = [
            'user.name' => $user->getUserIdentifier(),
            'user.email' => $user->getEmail(),
            'user.title' => $user->getTitle() ?? '',
            'user.alias' => $user->getAlias() ?? '',
            'user.display' => $user->getDisplayName(),
        ];

        foreach ($user->getPreferences() as $metaField) {
            $values = array_merge($values, [
                'user.meta.' . $metaField->getName() => $metaField->getValue(),
            ]);
        }

        return $values;
    }
}
