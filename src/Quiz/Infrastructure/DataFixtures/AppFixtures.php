<?php

declare(strict_types=1);

namespace App\Quiz\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures
 *
 * @package App\Quiz\Infrastructure\DataFixtures
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
