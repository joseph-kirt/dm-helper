<?php

namespace App\Facades;

use Mockery;
use Mockery\Expectation as MockException;
use Mockery\MockInterface;
use RuntimeException;
use Symfony\Component\HttpKernel\KernelInterface;
use function func_get_args;
use function get_class;
use function is_object;
use function tap;

abstract class Facade
{
    /**
     * The application instance being facaded.
     */
    protected static KernelInterface $app;

    /**
     * The resolved object instances.
     */
    protected static array $resolvedInstance;

    /**
     * Convert the facade into a Mockery spy.
     */
    public static function spy(): void
    {
        if (!static::isMock()) {
            $class = static::getMockableClass();

            static::swap($class ? Mockery::spy($class) : Mockery::spy());
        }
    }

    /**
     * Initiate a mock expectation on the facade.
     */
    public static function shouldReceive(): MockException
    {
        $name = static::getFacadeAccessor();

        $mock = static::isMock()
            ? static::$resolvedInstance[$name]
            : static::createFreshMockInstance();

        return $mock->shouldReceive(...func_get_args());
    }

    /**
     * Hotswap the underlying instance behind the facade.
     */
    public static function swap(mixed $instance): void
    {
        static::$resolvedInstance[static::getFacadeAccessor()] = $instance;

        if (isset(static::$app)) {
            static::$app->getContainer()->set(static::getFacadeAccessor(), $instance);
        }
    }

    public static function getFacadeRoot(): mixed
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    public static function clearResolvedInstance(string $name): void
    {
        unset(static::$resolvedInstance[$name]);
    }

    public static function clearResolvedInstances(): void
    {
        static::$resolvedInstance = [];
    }

    public static function getFacadeApplication(): KernelInterface
    {
        return static::$app;
    }

    public static function setFacadeApplication(KernelInterface $app): void
    {
        static::$app = $app;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @throws RuntimeException
     */
    public static function __callStatic(string $method, array $args): mixed
    {
        $instance = static::getFacadeRoot();

        if (!$instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }

    protected static function createFreshMockInstance(): MockException
    {
        return tap(static::createMock(), static function ($mock) {
            static::swap($mock);

            $mock->shouldAllowMockingProtectedMethods();
        });
    }

    protected static function createMock(): MockInterface
    {
        $class = static::getMockableClass();

        return $class ? Mockery::mock($class) : Mockery::mock();
    }

    protected static function isMock(): bool
    {
        $name = static::getFacadeAccessor();

        return isset(static::$resolvedInstance[$name]) &&
            static::$resolvedInstance[$name] instanceof MockInterface;
    }

    protected static function getMockableClass(): ?string
    {
        if ($root = static::getFacadeRoot()) {
            return get_class($root);
        }

        return null;
    }

    /**
     * Get the registered name of the component.
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    protected static function resolveFacadeInstance(object|string $name): mixed
    {
        if (is_object($name)) {
            return $name;
        }

        return static::$resolvedInstance[$name] ?? (static::$resolvedInstance[$name] = static::$app->getContainer()->get($name));
    }
}
