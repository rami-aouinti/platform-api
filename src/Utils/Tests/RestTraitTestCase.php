<?php
declare(strict_types = 1);
/**
 * /src/Utils/Tests/RestTraitTestCase.php
 */

namespace App\Utils\Tests;

use Generator;
use App\Rest\UuidHelper;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class RestTraitTestCase
 *
 * @codeCoverageIgnore
 *
 * @package App\Utils\Tests
 */
abstract class RestTraitTestCase extends WebTestCase
{
    /**
     * @var string;
     */
    private const END_POINT_COUNT = '/count';

    /**
     * @var string
     */
    protected static string $route;

    /**
     * @return Generator<array<int, string|null>>
     */
    abstract public function getValidUsers(): Generator;

    /**
     * @return Generator<array<int, string|null>>
     */
    abstract public function getInvalidUsers(): Generator;

    /**
     * @dataProvider dataProviderTestThatCountRouteDoesNotAllowNotSupportedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatCountRouteDoesNotAllowNotSupportedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . self::END_POINT_COUNT);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(405, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatCountRouteWorksWithAllowedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatCountRouteWorksWithAllowedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . self::END_POINT_COUNT);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(500, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatCountRouteDoesNotAllowInvalidUser
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatCountRouteDoesNotAllowInvalidUser(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . self::END_POINT_COUNT);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(
            $username === null ? 401 : 403,
            $response->getStatusCode(),
            (string)$response->getContent()
        );
    }

    /**
     * @dataProvider dataProviderTestThatRootRouteDoesNotAllowNotSupportedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatRootRouteDoesNotAllowNotSupportedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(405, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatRootRouteWorksWithAllowedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatRootRouteWorksWithAllowedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(500, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatRootRouteDoesNotAllowInvalidUser
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatRootRouteDoesNotAllowInvalidUser(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(
            $username === null ? 401 : 403,
            $response->getStatusCode(),
            (string)$response->getContent()
        );
    }

    /**
     * @dataProvider dataProviderTestThatRootRouteWithIdDoesNotAllowNotSupportedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatRootRouteWithIdDoesNotAllowNotSupportedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $uuid = UuidHelper::getFactory()->uuid1()->toString();
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . '/' . $uuid);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(405, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatRootRouteWithIdWorksWithAllowedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatRootRouteWithIdWorksWithAllowedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $uuid = UuidHelper::getFactory()->uuid1()->toString();
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . '/' . $uuid);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(500, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatRootRouteWithIdDoesNotAllowInvalidUser
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatRootRouteWithIdDoesNotAllowInvalidUser(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $uuid = UuidHelper::getFactory()->uuid1()->toString();
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . '/' . $uuid);
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(
            $username === null ? 401 : 403,
            $response->getStatusCode(),
            (string)$response->getContent()
        );
    }

    /**
     * @dataProvider dataProviderTestThatIdsRouteDoesNotAllowNotSupportedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatIdsRouteDoesNotAllowNotSupportedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . '/ids');
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(405, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatIdsRouteWorksWithAllowedHttpMethods
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatIdsRouteWorksWithAllowedHttpMethods(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . '/ids');
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(500, $response->getStatusCode(), (string)$response->getContent());
    }

    /**
     * @dataProvider dataProviderTestThatIdsRouteDoesNotAllowInvalidUser
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $method
     *
     * @throws Throwable
     */
    public function testThatIdsRouteDoesNotAllowInvalidUser(
        ?string $username = null,
        ?string $password = null,
        ?string $method = null
    ): void {
        $method ??= 'GET';
        $client = $this->getTestClient($username, $password);
        $client->request($method, static::$route . '/ids');
        /** @var Response $response */
        $response = $client->getResponse();
        static::assertSame(
            $username === null ? 401 : 403,
            $response->getStatusCode(),
            (string)$response->getContent()
        );
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatCountRouteDoesNotAllowNotSupportedHttpMethods(): Generator
    {
        $methods = [
            ['POST'],
            ['HEAD'],
            ['PUT'],
            ['DELETE'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatCountRouteWorksWithAllowedHttpMethods(): Generator
    {
        $methods = [
            ['GET'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatCountRouteDoesNotAllowInvalidUser(): Generator
    {
        $methods = [
            ['GET'],
        ];

        return $this->createDataForTest($this->getInvalidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatRootRouteDoesNotAllowNotSupportedHttpMethods(): Generator
    {
        $methods = [
            ['PUT'],
            ['DELETE'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatRootRouteWorksWithAllowedHttpMethods(): Generator
    {
        $methods = [
            ['GET'],
            ['POST'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatRootRouteDoesNotAllowInvalidUser(): Generator
    {
        $methods = [
            ['GET'],
            ['POST'],
        ];

        return $this->createDataForTest($this->getInvalidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatRootRouteWithIdDoesNotAllowNotSupportedHttpMethods(): Generator
    {
        $methods = [
            ['POST'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatRootRouteWithIdWorksWithAllowedHttpMethods(): Generator
    {
        $methods = [
            ['DELETE'],
            ['GET'],
            ['PUT'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatRootRouteWithIdDoesNotAllowInvalidUser(): Generator
    {
        $methods = [
            ['DELETE'],
            ['GET'],
            ['PUT'],
        ];

        return $this->createDataForTest($this->getInvalidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatIdsRouteDoesNotAllowNotSupportedHttpMethods(): Generator
    {
        $methods = [
            ['POST'],
            ['PUT'],
            ['DELETE'],
            ['OPTIONS'],
            ['CONNECT'],
            ['foobar'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatIdsRouteWorksWithAllowedHttpMethods(): Generator
    {
        $methods = [
            ['GET'],
        ];

        return $this->createDataForTest($this->getValidUsers(), $methods);
    }

    /**
     * @return Generator<array<int, string|null>>
     */
    public function dataProviderTestThatIdsRouteDoesNotAllowInvalidUser(): Generator
    {
        $methods = [
            ['GET'],
        ];

        return $this->createDataForTest($this->getInvalidUsers(), $methods);
    }

    /**
     * @param Generator<array<int, string|null>> $users
     * @param array<int, array<int, string>>     $methods
     *
     * @return Generator<array<int, string|null>>
     */
    private function createDataForTest(Generator $users, array $methods): Generator
    {
        foreach ($users as $userData) {
            foreach ($methods as $method) {
                yield array_merge($userData, $method);
            }
        }
    }
}
