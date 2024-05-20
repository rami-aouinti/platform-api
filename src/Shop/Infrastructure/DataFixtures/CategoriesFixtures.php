<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\DataFixtures;

use App\Shop\Domain\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class CategoriesFixtures
 *
 * @package App\Shop\Infrastructure\DataFixtures
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class CategoriesFixtures extends Fixture
{
    private int $counter = 1;

    public function __construct(private readonly SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Informatique', null, $manager);

        $this->createCategory('Ordinateurs portables', $parent, $manager);
        $this->createCategory('Ecrans', $parent, $manager);
        $this->createCategory('Souris', $parent, $manager);

        $parent = $this->createCategory('Mode', null, $manager);

        $this->createCategory('Homme', $parent, $manager);
        $this->createCategory('Femme', $parent, $manager);
        $this->createCategory('Enfant', $parent, $manager);

        $manager->flush();
    }

    /**
     * @param string          $name
     * @param Categories|null $parent
     * @param ObjectManager   $manager
     *
     * @return Categories
     */
    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager): Categories
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug((string)$this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
