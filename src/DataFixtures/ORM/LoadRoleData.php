<?php
declare(strict_types = 1);
/**
 * /src/DataFixtures/ORM/LoadRoleData.php
 */

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Persistence\ObjectManager;
use App\Security\Interfaces\RolesServiceInterface;
use App\Entity\Role;
use BadMethodCallException;

/**
 * Class LoadRoleData
 *
 * @package App\DataFixtures\ORM
 */
final class LoadRoleData extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private ContainerInterface $container;
    private ObjectManager $manager;
    private RolesServiceInterface $roles;


    /**
     * Setter for container.
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(?ContainerInterface $container = null): void
    {
        if ($container !== null) {
            $this->container = $container;
        }
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var RolesServiceInterface $rolesService */
        $rolesService = $this->container->get('test.app.security.roles_service');
        $this->roles = $rolesService;
        $this->manager = $manager;
        $iterator = function (string $role): void {
            $this->createRole($role);
        };
        // Create entities
        array_map($iterator, $this->roles->getRoles());
        // Flush database changes
        $this->manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * Method to create, persist and flush Role entity to database.
     *
     * @param string $role
     *
     * @throws BadMethodCallException
     */
    private function createRole(string $role): void
    {
        // Create new Role entity
        $entity = new Role($role);
        $entity->setDescription('Description - ' . $role);
        // Persist entity
        $this->manager->persist($entity);
        // Create reference for later usage
        $this->addReference('Role-' . $this->roles->getShort($role), $entity);
    }
}
