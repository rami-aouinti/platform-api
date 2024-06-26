<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Repository;

use App\Crm\Domain\Entity\Bookmark;
use App\User\Domain\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * @extends \Doctrine\ORM\EntityRepository<Bookmark>
 */
class BookmarkRepository extends EntityRepository
{
    private array $userCache = [];

    public function saveBookmark(Bookmark $bookmark)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($bookmark);
        $entityManager->flush();

        $this->clearCache($bookmark->getUser());
    }

    public function deleteBookmark(Bookmark $bookmark)
    {
        $em = $this->getEntityManager();
        $em->remove($bookmark);
        $em->flush();
        $this->clearCache($bookmark->getUser());
    }

    public function getSearchDefaultOptions(User $user, string $name): ?Bookmark
    {
        return $this->findBookmark($user, Bookmark::SEARCH_DEFAULT, $name);
    }

    public function findBookmark(User $user, string $type, string $name): ?Bookmark
    {
        $name = mb_substr($name, 0, 50);
        $key = 'user_' . $user->getId();

        if (!\array_key_exists($key, $this->userCache)) {
            $this->userCache[$key] = [];
            $all = $this->findBy([
                'user' => $user->getId(),
            ]);
            foreach ($all as $item) {
                $this->userCache[$key][$item->getType()][mb_substr($item->getName(), 0, 50)] = $item;
            }
        }

        if (!\array_key_exists($type, $this->userCache[$key])) {
            return null;
        }

        if (!\array_key_exists($name, $this->userCache[$key][$type])) {
            return null;
        }

        return $this->userCache[$key][$type][$name];
    }

    private function clearCache(User $user): void
    {
        $key = 'user_' . $user->getId();
        if (\array_key_exists($key, $this->userCache)) {
            unset($this->userCache[$key]);
        }
    }
}
