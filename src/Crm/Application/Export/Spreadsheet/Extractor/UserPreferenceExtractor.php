<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export\Spreadsheet\Extractor;

use App\Crm\Application\Export\Spreadsheet\ColumnDefinition;
use App\Crm\Transport\Event\UserPreferenceDisplayEvent;
use App\User\Domain\Entity\User;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @internal
 */
final class UserPreferenceExtractor implements ExtractorInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @param UserPreferenceDisplayEvent $value
     * @return ColumnDefinition[]
     * @throws ExtractorException
     */
    public function extract($value): array
    {
        if (!($value instanceof UserPreferenceDisplayEvent)) {
            throw new ExtractorException('UserPreferenceExtractor needs a UserPreferenceDisplayEvent instance for work');
        }

        $columns = [];

        $this->eventDispatcher->dispatch($value);

        foreach ($value->getPreferences() as $field) {
            if (!$field->isEnabled()) {
                continue;
            }

            $columns[] = new ColumnDefinition(
                $field->getLabel(),
                'string',
                function (User $user) use ($field) {
                    $meta = $user->getPreference($field->getName());
                    if ($meta === null) {
                        return null;
                    }

                    return $meta->getValue();
                }
            );
        }

        return $columns;
    }
}
