<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Customer;

use App\Crm\Application\Utils\NumberGenerator;
use App\Crm\Application\Validator\ValidationFailedException;
use App\Crm\Domain\Entity\Customer;
use App\Crm\Domain\Repository\CustomerRepository;
use App\Crm\Domain\Repository\Query\CustomerQuery;
use App\Crm\Transport\Configuration\SystemConfiguration;
use App\Crm\Transport\Event\CustomerCreateEvent;
use App\Crm\Transport\Event\CustomerCreatePostEvent;
use App\Crm\Transport\Event\CustomerCreatePreEvent;
use App\Crm\Transport\Event\CustomerMetaDefinitionEvent;
use App\Crm\Transport\Event\CustomerUpdatePostEvent;
use App\Crm\Transport\Event\CustomerUpdatePreEvent;
use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @package App\Crm\Transport\Customer
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class CustomerService
{
    public function __construct(
        private readonly CustomerRepository $repository,
        private readonly SystemConfiguration $configuration,
        private readonly ValidatorInterface $validator,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function createNewCustomer(string $name): Customer
    {
        $customer = new Customer($name);
        $customer->setTimezone($this->getDefaultTimezone());
        $customer->setCountry($this->configuration->getCustomerDefaultCountry());
        $customer->setCurrency($this->configuration->getCustomerDefaultCurrency());
        $customer->setNumber($this->calculateNextCustomerNumber());

        $this->dispatcher->dispatch(new CustomerMetaDefinitionEvent($customer));
        $this->dispatcher->dispatch(new CustomerCreateEvent($customer));

        return $customer;
    }

    public function saveNewCustomer(Customer $customer): Customer
    {
        if ($customer->getId() !== null) {
            throw new InvalidArgumentException('Cannot create customer, already persisted');
        }

        $this->validateCustomer($customer);

        $this->dispatcher->dispatch(new CustomerCreatePreEvent($customer));
        $this->repository->saveCustomer($customer);
        $this->dispatcher->dispatch(new CustomerCreatePostEvent($customer));

        return $customer;
    }

    public function updateCustomer(Customer $customer): Customer
    {
        $this->validateCustomer($customer);

        $this->dispatcher->dispatch(new CustomerUpdatePreEvent($customer));
        $this->repository->saveCustomer($customer);
        $this->dispatcher->dispatch(new CustomerUpdatePostEvent($customer));

        return $customer;
    }

    public function findCustomerByName(string $name): ?Customer
    {
        return $this->repository->findOneBy([
            'name' => $name,
        ]);
    }

    public function findCustomerByNumber(string $number): ?Customer
    {
        return $this->repository->findOneBy([
            'number' => $number,
        ]);
    }

    /**
     * @return iterable<Customer>
     */
    public function findCustomer(CustomerQuery $query): iterable
    {
        return $this->repository->getCustomersForQuery($query);
    }

    public function countCustomer(bool $visible = true): int
    {
        return $this->repository->countCustomer($visible);
    }

    private function getDefaultTimezone(): string
    {
        $timezone = $this->configuration->getCustomerDefaultTimezone();
        if (null === ($timezone)) {
            $timezone = date_default_timezone_get();
        }

        return $timezone;
    }

    /**
     * @param string[] $groups
     * @throws ValidationFailedException
     */
    private function validateCustomer(Customer $customer, array $groups = []): void
    {
        $errors = $this->validator->validate($customer, null, $groups);

        if ($errors->count() > 0) {
            throw new ValidationFailedException($errors, 'Validation Failed');
        }
    }

    private function calculateNextCustomerNumber(): ?string
    {
        $format = $this->configuration->find('customer.number_format');
        if (empty($format) || !\is_string($format)) {
            return null;
        }

        // we cannot use max(number) because a varchar column returns unexpected results
        $start = $this->repository->countCustomer();
        $i = 0;

        do {
            $start++;

            $numberGenerator = new NumberGenerator($format, function (string $originalFormat, string $format, int $increaseBy) use ($start): string|int {
                return match ($format) {
                    'cc' => $start + $increaseBy,
                    default => $originalFormat,
                };
            });

            $number = $numberGenerator->getNumber();
            $customer = $this->findCustomerByNumber($number);
        } while ($customer !== null && $i++ < 100);

        if ($customer !== null) {
            return null;
        }

        return $number;
    }
}
