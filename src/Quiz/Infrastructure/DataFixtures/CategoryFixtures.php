<?php

declare(strict_types=1);

namespace App\Quiz\Infrastructure\DataFixtures;

use App\Quiz\Domain\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @package App\Quiz\Infrastructure\DataFixtures
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class CategoryFixtures extends Fixture
{
    public const SYMFONY_REFERENCE = 'Symfony';
    public const SYMFONY_V3_REFERENCE = 'Symfony 3';
    public const SYMFONY_V4_REFERENCE = 'Symfony 4';

    public function load(ObjectManager $manager): void
    {
        $filename = 'initial-prod-data-languages.mysql.sql';
        //$filename = 'initial-prod-data-languages.mysql.sql';

        $sql = file_get_contents($filename);  // Read file contents
        $manager->getConnection()->exec($sql);  // Execute native SQL
        $manager->flush();

        $this->loadCategories($manager);
    }

    private function loadCategories(ObjectManager $manager): void
    {
        // SYMFONY_REFERENCE
        $category = $manager->getRepository(Category::class)->create();
        $category->setShortname('Symfony');
        $category->setLongname('Symfony (all versions)');
        $manager->persist($category);
        $this->addReference(self::SYMFONY_REFERENCE, $category);

        // SYMFONY_V3_REFERENCE
        $category = $manager->getRepository(Category::class)->create();
        $category->setShortname('Symfony3');
        $category->setLongname('Symfony version 3');
        $manager->persist($category);
        $this->addReference(self::SYMFONY_V3_REFERENCE, $category);

        // SYMFONY_V4_REFERENCE
        $category = $manager->getRepository(Category::class)->create();
        $category->setShortname('Symfony4');
        $category->setLongname('Symfony version 4');
        $manager->persist($category);
        $this->addReference(self::SYMFONY_V4_REFERENCE, $category);

        $manager->flush();
    }
}
