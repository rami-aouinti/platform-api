<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Repository\Query;

use App\Crm\Domain\Entity\Team;

/**
 * Can be used for advanced queries with the: UserRepository
 */
class UserQuery extends BaseQuery implements VisibilityInterface
{
    use VisibilityTrait;

    public const USER_ORDER_ALLOWED = ['username', 'alias', 'title', 'email', 'systemAccount'];

    private ?string $role = null;
    /**
     * @var Team[]
     */
    private array $searchTeams = [];
    private ?bool $systemAccount = null;

    public function __construct()
    {
        $this->setDefaults([
            'orderBy' => 'username',
            'searchTeams' => [],
            'visibility' => VisibilityInterface::SHOW_VISIBLE,
            'systemAccount' => null,
        ]);
    }

    /**
     * @return Team[]
     */
    public function getSearchTeams(): array
    {
        return $this->searchTeams;
    }

    /**
     * @param Team[] $searchTeams
     */
    public function setSearchTeams(array $searchTeams): void
    {
        $this->searchTeams = $searchTeams;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getSystemAccount(): ?bool
    {
        return $this->systemAccount;
    }

    public function setSystemAccount(?bool $systemAccount): void
    {
        $this->systemAccount = $systemAccount;
    }
}
