<?php

declare(strict_types=1);

namespace App\Quiz\Infrastructure\DataFixtures;

use App\General\Domain\Enum\Language;
use App\General\Domain\Enum\Locale;
use App\User\Domain\Entity\Address;
use App\User\Domain\Entity\Enum\SexEnum;
use App\User\Domain\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures
 *
 * @package App\Quiz\Infrastructure\DataFixtures
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class UserFixtures extends Fixture
{

    public const BASIC_USER_REFERENCE = 'basic_user';
    public const TEACHER_USER_REFERENCE = 'teacher_user';
    public const ADMIN_USER_REFERENCE = 'admin_user';
    public const SUPER_ADMIN_USER_REFERENCE = 'super_admin_user';


    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $address = $this->createAddress();
        $superadmin = (new User())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setTitle('Ing')
            ->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).')
            ->setPhone('+4999999999999')
            ->setBirthday(new DateTime('now'))
            ->setSex(SexEnum::Male)
            ->setAddress($address)
            ->setGoogleId('google_id')
            ->setInstagramId('instagram_id')
            ->setFacebookId('facebook_id')
            ->setTwitterId('twitter_id')
            ->setImage('image.png')
            ->setLanguage(Language::EN)
            ->setLocale(Locale::EN)
            ->setBirthday(new DateTime('now'));
        $superadmin->setUsername('superadmin');
        $superadmin->setEmail('superadmin@domain.tld');
        $superadmin->setPlainPassword('superadmin');
        $password = $this->userPasswordHasher->hashPassword($superadmin, $superadmin->getPlainPassword());
        $superadmin->setPlainPassword($password);
        $superadmin->setFirstName('John');
        $superadmin->setLastName('Doe');
        $superadmin->setTitle('Ing');
        $superadmin->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).');
        $superadmin->setPhone('+4999999999999');
        $superadmin->setBirthday(new DateTime('now'));
        $superadmin->setSex(SexEnum::Male);
        $superadmin->setAddress($address);
        $superadmin->setGoogleId('google_id');
        $superadmin->setInstagramId('instagram_id');
        $superadmin->setFacebookId('facebook_id');
        $superadmin->setTwitterId('twitter_id');
        $superadmin->setImage('image.png');
        $superadmin->setLanguage(Language::EN);
        $superadmin->setLocale(Locale::EN);
        $superadmin->setLastQuizAccess(new \DateTimeImmutable('now'));
        $manager->persist($superadmin);
        $this->addReference(self::SUPER_ADMIN_USER_REFERENCE, $superadmin);

        $admin = (new User())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setTitle('Ing')
            ->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).')
            ->setPhone('+4999999999999')
            ->setBirthday(new DateTime('now'))
            ->setSex(SexEnum::Male)
            ->setAddress($address)
            ->setGoogleId('google_id')
            ->setInstagramId('instagram_id')
            ->setFacebookId('facebook_id')
            ->setTwitterId('twitter_id')
            ->setImage('image.png')
            ->setLanguage(Language::EN)
            ->setLocale(Locale::EN)
            ->setBirthday(new DateTime('now'));
        $admin->setUsername('admin');
        $admin->setEmail('admin@domain.tld');
        $admin->setPlainPassword('admin');
        $password = $this->userPasswordHasher->hashPassword($admin, $admin->getPlainPassword());
        $admin->setPlainPassword($password);
        $superadmin->setFirstName('John');
        $superadmin->setLastName('Doe');
        $superadmin->setTitle('Ing');
        $superadmin->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).');
        $superadmin->setPhone('+4999999999999');
        $superadmin->setBirthday(new DateTime('now'));
        $superadmin->setSex(SexEnum::Male);
        $superadmin->setAddress($address);
        $superadmin->setGoogleId('google_id');
        $superadmin->setInstagramId('instagram_id');
        $superadmin->setFacebookId('facebook_id');
        $superadmin->setTwitterId('twitter_id');
        $superadmin->setImage('image.png');
        $superadmin->setLanguage(Language::EN);
        $superadmin->setLocale(Locale::EN);
        $admin->setLastQuizAccess(new \DateTimeImmutable('now'));
        $manager->persist($admin);
        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);

        $teacher = (new User())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setTitle('Ing')
            ->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).')
            ->setPhone('+4999999999999')
            ->setBirthday(new DateTime('now'))
            ->setSex(SexEnum::Male)
            ->setAddress($address)
            ->setGoogleId('google_id')
            ->setInstagramId('instagram_id')
            ->setFacebookId('facebook_id')
            ->setTwitterId('twitter_id')
            ->setImage('image.png')
            ->setLanguage(Language::EN)
            ->setLocale(Locale::EN)
            ->setBirthday(new DateTime('now'));
        $teacher->setUsername('teacher');
        $teacher->setEmail('teacher@domain.tld');
        $teacher->setPlainPassword('teacher');
        $password = $this->userPasswordHasher->hashPassword($teacher, $teacher->getPlainPassword());
        $teacher->setPlainPassword($password);
        $superadmin->setFirstName('John');
        $superadmin->setLastName('Doe');
        $superadmin->setTitle('Ing');
        $superadmin->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).');
        $superadmin->setPhone('+4999999999999');
        $superadmin->setBirthday(new DateTime('now'));
        $superadmin->setSex(SexEnum::Male);
        $superadmin->setAddress($address);
        $superadmin->setGoogleId('google_id');
        $superadmin->setInstagramId('instagram_id');
        $superadmin->setFacebookId('facebook_id');
        $superadmin->setTwitterId('twitter_id');
        $superadmin->setImage('image.png');
        $superadmin->setLanguage(Language::EN);
        $superadmin->setLocale(Locale::EN);
        $teacher->setLastQuizAccess(new \DateTimeImmutable('now'));
        $manager->persist($teacher);
        $this->addReference(self::TEACHER_USER_REFERENCE, $teacher);

        $user = (new User())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setTitle('Ing')
            ->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).')
            ->setPhone('+4999999999999')
            ->setBirthday(new DateTime('now'))
            ->setSex(SexEnum::Male)
            ->setAddress($address)
            ->setGoogleId('google_id')
            ->setInstagramId('instagram_id')
            ->setFacebookId('facebook_id')
            ->setTwitterId('twitter_id')
            ->setImage('image.png')
            ->setLanguage(Language::EN)
            ->setLocale(Locale::EN)
            ->setBirthday(new DateTime('now'));
        $user->setUsername('user');
        $user->setEmail('user@domain.tld');
        $user->setPlainPassword('user');
        $password = $this->userPasswordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPlainPassword($password);
        $superadmin->setFirstName('John');
        $superadmin->setLastName('Doe');
        $superadmin->setTitle('Ing');
        $superadmin->setDescription('Hi, I’m john.doe, Decisions: If you can’t decide, the answer is no.
             If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).');
        $superadmin->setPhone('+4999999999999');
        $superadmin->setBirthday(new DateTime('now'));
        $superadmin->setSex(SexEnum::Male);
        $superadmin->setAddress($address);
        $superadmin->setGoogleId('google_id');
        $superadmin->setInstagramId('instagram_id');
        $superadmin->setFacebookId('facebook_id');
        $superadmin->setTwitterId('twitter_id');
        $superadmin->setImage('image.png');
        $superadmin->setLanguage(Language::EN);
        $superadmin->setLocale(Locale::EN);
        $user->setLastQuizAccess(new \DateTimeImmutable('now'));
        $manager->persist($user);
        $this->addReference(self::BASIC_USER_REFERENCE, $user);

        $manager->flush();
    }

    private function createAddress(): Address
    {
        return new Address(
            'Germany',
            'Köln',
            '50859',
            'Widdersdorder landstr',
            '11'
        );
    }
}
