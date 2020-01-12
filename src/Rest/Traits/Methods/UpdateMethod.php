<?php
declare(strict_types = 1);
/**
 * /src/Rest/Traits/Methods/UpdateMethod.php
 */

namespace App\Rest\Traits\Methods;

use App\DTO\Interfaces\RestDtoInterface;
use App\Rest\ResponseHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Trait UpdateMethod
 *
 * @package App\Rest\Traits\Methods
 *
 * @method ResponseHandler getResponseHandler()
 */
trait UpdateMethod
{
    // Traits
    use AbstractGenericMethods;

    /**
     * Generic 'updateMethod' method for REST resources.
     *
     * @param Request          $request
     * @param RestDtoInterface $restDto
     * @param string           $id
     * @param array|null       $allowedHttpMethods
     *
     * @throws Throwable
     *
     * @return Response
     */
    public function updateMethod(
        Request $request,
        RestDtoInterface $restDto,
        string $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $resource = $this->validateRestMethodAndGetResource($request, $allowedHttpMethods ?? ['PUT']);

        try {
            $data = $resource->update($id, $restDto, true);

            return $this->getResponseHandler()->createResponse($request, $data, $resource);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }
}
