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
use App\Crm\Application\Model\DailyStatistic;
use App\Crm\Application\Reporting\WeeklyUserList\WeeklyUserList;
use App\Crm\Application\Reporting\WeeklyUserList\WeeklyUserListForm;
use App\Crm\Domain\Repository\Query\TimesheetStatisticQuery;
use App\Crm\Domain\Repository\Query\UserQuery;
use App\Crm\Domain\Repository\Query\VisibilityInterface;
use App\Crm\Transport\Controller\Api\v1\AbstractController;
use App\Crm\Transport\Timesheet\TimesheetStatisticService;
use App\User\Infrastructure\Repository\UserRepository;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/reporting/users')]
#[IsGranted('report:other')]
final class ReportUsersWeekController extends AbstractController
{
    #[Route(path: '/week', name: 'report_weekly_users', methods: ['GET', 'POST'])]
    public function report(Request $request, TimesheetStatisticService $statisticService, UserRepository $userRepository): Response
    {
        return $this->render(
            'reporting/report_user_list.html.twig',
            $this->getData($request, $statisticService, $userRepository)
        );
    }

    #[Route(path: '/week_export', name: 'report_weekly_users_export', methods: ['GET', 'POST'])]
    public function export(Request $request, TimesheetStatisticService $statisticService, UserRepository $userRepository): Response
    {
        $data = $this->getData($request, $statisticService, $userRepository);

        $content = $this->renderView('reporting/report_user_list_export.html.twig', $data);

        $reader = new Html();
        $spreadsheet = $reader->loadFromString($content);

        $writer = new BinaryFileResponseWriter(new XlsxWriter(), 'kimai-export-users-weekly');

        return $writer->getFileResponse($spreadsheet);
    }

    private function getData(Request $request, TimesheetStatisticService $statisticService, UserRepository $userRepository): array
    {
        $currentUser = $this->getUser();
        $dateTimeFactory = $this->getDateTimeFactory();

        $values = new WeeklyUserList();
        $values->setDate($dateTimeFactory->getStartOfWeek());

        $form = $this->createFormForGetRequest(WeeklyUserListForm::class, $values, [
            'timezone' => $dateTimeFactory->getTimezone()->getName(),
            'start_date' => $values->getDate(),
        ]);

        $form->submit($request->query->all(), false);

        $query = new UserQuery();
        $query->setVisibility(VisibilityInterface::SHOW_BOTH);
        $query->setSystemAccount(false);
        $query->setCurrentUser($currentUser);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $values->setDate($dateTimeFactory->getStartOfWeek());
            } else {
                if ($values->getTeam() !== null) {
                    $query->setSearchTeams([$values->getTeam()]);
                }
            }
        }

        $allUsers = $userRepository->getUsersForQuery($query);

        if ($values->getDate() === null) {
            $values->setDate($dateTimeFactory->getStartOfWeek());
        }

        $start = $dateTimeFactory->getStartOfWeek($values->getDate());
        $end = $dateTimeFactory->getEndOfWeek($values->getDate());

        $previous = clone $start;
        $previous->modify('-1 week');

        $next = clone $start;
        $next->modify('+1 week');

        $dayStats = [];
        $hasData = true;

        if (!empty($allUsers)) {
            $statsQuery = new TimesheetStatisticQuery($start, $end, $allUsers);
            $statsQuery->setProject($values->getProject());
            $dayStats = $statisticService->getDailyStatistics($statsQuery);
        }

        if (empty($dayStats)) {
            $dayStats = [new DailyStatistic($start, $end, $currentUser)];
            $hasData = false;
        }

        return [
            'period_attribute' => 'days',
            'dataType' => $values->getSumType(),
            'report_title' => 'report_weekly_users',
            'box_id' => 'weekly-user-list-reporting-box',
            'export_route' => 'report_weekly_users_export',
            'form' => $form->createView(),
            'current' => $start,
            'next' => $next,
            'previous' => $previous,
            'decimal' => $values->isDecimal(),
            'subReportDate' => $values->getDate(),
            'subReportRoute' => 'report_user_week',
            'stats' => $dayStats,
            'hasData' => $hasData,
        ];
    }
}
