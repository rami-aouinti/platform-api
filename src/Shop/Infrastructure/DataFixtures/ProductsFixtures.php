<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\DataFixtures;

use App\Shop\Domain\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @package App\Shop\Infrastructure\DataFixtures
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class ProductsFixtures extends Fixture
{
    public function __construct(
        private readonly SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        for ($prod = 1; $prod <= 10; $prod++) {
            $product = new Products();
            $product->setName($faker->text(15));
            $product->setDescription($faker->text());
            $product->setSlug((string)$this->slugger->slug($product->getName())->lower());
            $product->setPrice($faker->numberBetween(900, 150000));
            $product->setStock($faker->numberBetween(0, 10));

            //On va chercher une référence de catégorie
            $category = $this->getReference('cat-' . rand(1, 8));
            $product->setCategories($category);

            $this->setReference('prod-' . $prod, $product);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
