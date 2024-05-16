<?php

declare(strict_types=1);

namespace App\Setting\Transport\Controller\Api\v1\Setting;

use App\General\Domain\Utils\JSON;
use App\Setting\Domain\Entity\Setting;
use App\Setting\Domain\Repository\SettingRepository;
use App\User\Domain\Entity\User;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @package App\Setting
 */
#[AsController]
#[OA\Tag(name: 'Setting')]
readonly class IndexController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * Get current user profile data, accessible only for 'IS_AUTHENTICATED_FULLY' users.
     *
     * @throws JsonException
     */
    #[Route(
        path: '/v1/setting',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    #[OA\Response(
        response: 200,
        description: 'User setting data',
        content: new JsonContent(
            ref: new Model(
                type: Setting::class,
                groups: ['set.Setting'],
            ),
            type: 'object',
        ),
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid token (not found or expired)',
        content: new JsonContent(
            properties: [
                new Property(property: 'code', description: 'Error code', type: 'integer'),
                new Property(property: 'message', description: 'Error description', type: 'string'),
            ],
            type: 'object',
            example: [
                'code' => 401,
                'message' => 'JWT Token not found',
            ],
        ),
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied',
        content: new JsonContent(
            properties: [
                new Property(property: 'code', description: 'Error code', type: 'integer'),
                new Property(property: 'message', description: 'Error description', type: 'string'),
            ],
            type: 'object',
            example: [
                'code' => 403,
                'message' => 'Access denied',
            ],
        ),
    )]
    public function __invoke(User $loggedInUser, SettingRepository $settingRepository): JsonResponse
    {
        /** @var array<string, string|array<string, string>> $output */
        $output = JSON::decode(
            $this->serializer->serialize(
                $settingRepository->findOneBy(
                    [
                        'author' => $loggedInUser
                    ]
                ),
                'json',
                [
                    'groups' => Setting::SET_SETTING,
                ]
            ),
            true,
        );

        return new JsonResponse($output);
    }
}
