<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Controller\Api\v1\Reporting;

use App\Crm\Application\Export\Spreadsheet\Writer\BinaryFileResponseWriter;
use App\Crm\Application\Export\Spreadsheet\Writer\XlsxWriter;
use App\Crm\Application\Reporting\CustomerMonthlyProjects\CustomerMonthlyProjects;
use App\Crm\Application\Reporting\CustomerMonthlyProjects\CustomerMonthlyProjectsForm;
use App\Crm\Application\Reporting\CustomerMonthlyProjects\CustomerMonthlyProjectsRepository;
use App\Crm\Domain\Repository\Query\UserQuery;
use App\Crm\Domain\Repository\Query\VisibilityInterface;
use App\Crm\Transport\Controller\Api\v1\AbstractController;
use App\User\Infrastructure\Repository\UserRepository;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @package App\Crm\Transport\Controller\Api\v1\Reporting
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[Route(path: '/reporting/customer/monthly_projects')]
#[IsGranted('report:customer')]
#[IsGranted('report:other')]
final class CustomerMonthlyProjectsController extends AbstractController
{
    #[Route(path: '/view', name: 'report_customer_monthly_projects', methods: ['GET', 'POST'])]
    public function report(Request $request, CustomerMonthlyProjectsRepository $repository, UserRepository $userRepository): Response
    {
        return $this->render(
            'reporting/customer/monthly_projects.html.twig',
            $this->getData($request, $repository, $userRepository)
        );
    }

    #[Route(path: '/export', name: 'report_customer_monthly_projects_export', methods: ['GET', 'POST'])]
    public function export(Request $request, CustomerMonthlyProjectsRepository $repository, UserRepository $userRepository): Response
    {
        $data = $this->getData($request, $repository, $userRepository);

        $content = $this->render('reporting/customer/monthly_projects_export.html.twig', $data)->getContent();

        $reader = new Html();
        $spreadsheet = $reader->loadFromString($content);

        $writer = new BinaryFileResponseWriter(new XlsxWriter(), 'kimai-export-users-monthly');

        return $writer->getFileResponse($spreadsheet);
    }

    private function getData(Request $request, CustomerMonthlyProjectsRepository $repository, UserRepository $userRepository): array
    {
        $currentUser = $this->getUser();
        $dateTimeFactory = $this->getDateTimeFactory();

        $query = new UserQuery();
        $query->setVisibility(VisibilityInterface::SHOW_BOTH);
        $query->setSystemAccount(false);
        $query->setCurrentUser($currentUser);
        $allUsers = $userRepository->getUsersForQuery($query);

        $values = new CustomerMonthlyProjects();
        $values->setDate($dateTimeFactory->getStartOfMonth());

        $form = $this->createFormForGetRequest(CustomerMonthlyProjectsForm::class, $values, [
            'timezone' => $dateTimeFactory->getTimezone()->getName(),
            'start_date' => $values->getDate(),
        ]);

        $form->submit($request->query->all(), false);

        if ($form->isSubmitted() && !$form->isValid()) {
            $values->setDate($dateTimeFactory->getStartOfMonth());
        }

        if ($values->getDate() === null) {
            $values->setDate($dateTimeFactory->getStartOfMonth());
        }

        $start = $values->getDate();
        $start = $dateTimeFactory->getStartOfMonth($start);
        $end = $dateTimeFactory->getEndOfMonth($start);

        $previous = clone $start;
        $previous->modify('-1 month');

        $next = clone $start;
        $next->modify('+1 month');

        $stats = $repository->getGroupedByCustomerProjectActivityUser($start, $end, $allUsers, $values->getCustomer());

        return [
            'dataType' => $values->getSumType(),
            'report_title' => 'report_customer_monthly_projects',
            'export_route' => 'report_customer_monthly_projects_export',
            'form' => $form->createView(),
            'current' => $start,
            'next' => $next,
            'previous' => $previous,
            'decimal' => $values->isDecimal(),
            'stats' => $stats,
        ];
    }
}
