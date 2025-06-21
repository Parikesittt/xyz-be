<?php
/* @noinspection ALL */
// @formatter:off
// phpcs:ignoreFile

/**
 * A helper file for Laravel, to provide autocomplete information to your IDE
 * Generated for Laravel 11.44.7.
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 * @see https://github.com/barryvdh/laravel-ide-helper
 */
namespace Illuminate\Support\Facades {
    /**
     * 
     *
     * @see \Illuminate\Foundation\Application
     */
    class App {
        /**
         * Begin configuring a new Laravel application instance.
         *
         * @param string|null $basePath
         * @return \Illuminate\Foundation\Configuration\ApplicationBuilder 
         * @static 
         */
        public static function configure($basePath = null)
        {
            return \Illuminate\Foundation\Application::configure($basePath);
        }

        /**
         * Infer the application's base directory from the environment.
         *
         * @return string 
         * @static 
         */
        public static function inferBasePath()
        {
            return \Illuminate\Foundation\Application::inferBasePath();
        }

        /**
         * Get the version number of the application.
         *
         * @return string 
         * @static 
         */
        public static function version()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->version();
        }

        /**
         * Run the given array of bootstrap classes.
         *
         * @param string[] $bootstrappers
         * @return void 
         * @static 
         */
        public static function bootstrapWith($bootstrappers)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->bootstrapWith($bootstrappers);
        }

        /**
         * Register a callback to run after loading the environment.
         *
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function afterLoadingEnvironment($callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->afterLoadingEnvironment($callback);
        }

        /**
         * Register a callback to run before a bootstrapper.
         *
         * @param string $bootstrapper
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function beforeBootstrapping($bootstrapper, $callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->beforeBootstrapping($bootstrapper, $callback);
        }

        /**
         * Register a callback to run after a bootstrapper.
         *
         * @param string $bootstrapper
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function afterBootstrapping($bootstrapper, $callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->afterBootstrapping($bootstrapper, $callback);
        }

        /**
         * Determine if the application has been bootstrapped before.
         *
         * @return bool 
         * @static 
         */
        public static function hasBeenBootstrapped()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->hasBeenBootstrapped();
        }

        /**
         * Set the base path for the application.
         *
         * @param string $basePath
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function setBasePath($basePath)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->setBasePath($basePath);
        }

        /**
         * Get the path to the application "app" directory.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function path($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->path($path);
        }

        /**
         * Set the application directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function useAppPath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->useAppPath($path);
        }

        /**
         * Get the base path of the Laravel installation.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function basePath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->basePath($path);
        }

        /**
         * Get the path to the bootstrap directory.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function bootstrapPath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->bootstrapPath($path);
        }

        /**
         * Get the path to the service provider list in the bootstrap directory.
         *
         * @return string 
         * @static 
         */
        public static function getBootstrapProvidersPath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getBootstrapProvidersPath();
        }

        /**
         * Set the bootstrap file directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function useBootstrapPath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->useBootstrapPath($path);
        }

        /**
         * Get the path to the application configuration files.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function configPath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->configPath($path);
        }

        /**
         * Set the configuration directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function useConfigPath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->useConfigPath($path);
        }

        /**
         * Get the path to the database directory.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function databasePath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->databasePath($path);
        }

        /**
         * Set the database directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function useDatabasePath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->useDatabasePath($path);
        }

        /**
         * Get the path to the language files.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function langPath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->langPath($path);
        }

        /**
         * Set the language file directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function useLangPath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->useLangPath($path);
        }

        /**
         * Get the path to the public / web directory.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function publicPath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->publicPath($path);
        }

        /**
         * Set the public / web directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function usePublicPath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->usePublicPath($path);
        }

        /**
         * Get the path to the storage directory.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function storagePath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->storagePath($path);
        }

        /**
         * Set the storage directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function useStoragePath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->useStoragePath($path);
        }

        /**
         * Get the path to the resources directory.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function resourcePath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->resourcePath($path);
        }

        /**
         * Get the path to the views directory.
         * 
         * This method returns the first configured path in the array of view paths.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function viewPath($path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->viewPath($path);
        }

        /**
         * Join the given paths together.
         *
         * @param string $basePath
         * @param string $path
         * @return string 
         * @static 
         */
        public static function joinPaths($basePath, $path = '')
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->joinPaths($basePath, $path);
        }

        /**
         * Get the path to the environment file directory.
         *
         * @return string 
         * @static 
         */
        public static function environmentPath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->environmentPath();
        }

        /**
         * Set the directory for the environment file.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function useEnvironmentPath($path)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->useEnvironmentPath($path);
        }

        /**
         * Set the environment file to be loaded during bootstrapping.
         *
         * @param string $file
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function loadEnvironmentFrom($file)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->loadEnvironmentFrom($file);
        }

        /**
         * Get the environment file the application is using.
         *
         * @return string 
         * @static 
         */
        public static function environmentFile()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->environmentFile();
        }

        /**
         * Get the fully qualified path to the environment file.
         *
         * @return string 
         * @static 
         */
        public static function environmentFilePath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->environmentFilePath();
        }

        /**
         * Get or check the current application environment.
         *
         * @param string|array $environments
         * @return string|bool 
         * @static 
         */
        public static function environment(...$environments)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->environment(...$environments);
        }

        /**
         * Determine if the application is in the local environment.
         *
         * @return bool 
         * @static 
         */
        public static function isLocal()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isLocal();
        }

        /**
         * Determine if the application is in the production environment.
         *
         * @return bool 
         * @static 
         */
        public static function isProduction()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isProduction();
        }

        /**
         * Detect the application's current environment.
         *
         * @param \Closure $callback
         * @return string 
         * @static 
         */
        public static function detectEnvironment($callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->detectEnvironment($callback);
        }

        /**
         * Determine if the application is running in the console.
         *
         * @return bool 
         * @static 
         */
        public static function runningInConsole()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->runningInConsole();
        }

        /**
         * Determine if the application is running any of the given console commands.
         *
         * @param string|array $commands
         * @return bool 
         * @static 
         */
        public static function runningConsoleCommand(...$commands)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->runningConsoleCommand(...$commands);
        }

        /**
         * Determine if the application is running unit tests.
         *
         * @return bool 
         * @static 
         */
        public static function runningUnitTests()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->runningUnitTests();
        }

        /**
         * Determine if the application is running with debug mode enabled.
         *
         * @return bool 
         * @static 
         */
        public static function hasDebugModeEnabled()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->hasDebugModeEnabled();
        }

        /**
         * Register a new registered listener.
         *
         * @param callable $callback
         * @return void 
         * @static 
         */
        public static function registered($callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->registered($callback);
        }

        /**
         * Register all of the configured providers.
         *
         * @return void 
         * @static 
         */
        public static function registerConfiguredProviders()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->registerConfiguredProviders();
        }

        /**
         * Register a service provider with the application.
         *
         * @param \Illuminate\Support\ServiceProvider|string $provider
         * @param bool $force
         * @return \Illuminate\Support\ServiceProvider 
         * @static 
         */
        public static function register($provider, $force = false)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->register($provider, $force);
        }

        /**
         * Get the registered service provider instance if it exists.
         *
         * @param \Illuminate\Support\ServiceProvider|string $provider
         * @return \Illuminate\Support\ServiceProvider|null 
         * @static 
         */
        public static function getProvider($provider)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getProvider($provider);
        }

        /**
         * Get the registered service provider instances if any exist.
         *
         * @param \Illuminate\Support\ServiceProvider|string $provider
         * @return array 
         * @static 
         */
        public static function getProviders($provider)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getProviders($provider);
        }

        /**
         * Resolve a service provider instance from the class name.
         *
         * @param string $provider
         * @return \Illuminate\Support\ServiceProvider 
         * @static 
         */
        public static function resolveProvider($provider)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->resolveProvider($provider);
        }

        /**
         * Load and boot all of the remaining deferred providers.
         *
         * @return void 
         * @static 
         */
        public static function loadDeferredProviders()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->loadDeferredProviders();
        }

        /**
         * Load the provider for a deferred service.
         *
         * @param string $service
         * @return void 
         * @static 
         */
        public static function loadDeferredProvider($service)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->loadDeferredProvider($service);
        }

        /**
         * Register a deferred provider and service.
         *
         * @param string $provider
         * @param string|null $service
         * @return void 
         * @static 
         */
        public static function registerDeferredProvider($provider, $service = null)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->registerDeferredProvider($provider, $service);
        }

        /**
         * Resolve the given type from the container.
         *
         * @template TClass of object
         * @param string|class-string<TClass> $abstract
         * @param array $parameters
         * @return ($abstract is class-string<TClass> ? TClass : mixed)
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         * @static 
         */
        public static function make($abstract, $parameters = [])
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->make($abstract, $parameters);
        }

        /**
         * Determine if the given abstract type has been bound.
         *
         * @param string $abstract
         * @return bool 
         * @static 
         */
        public static function bound($abstract)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->bound($abstract);
        }

        /**
         * Determine if the application has booted.
         *
         * @return bool 
         * @static 
         */
        public static function isBooted()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isBooted();
        }

        /**
         * Boot the application's service providers.
         *
         * @return void 
         * @static 
         */
        public static function boot()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->boot();
        }

        /**
         * Register a new boot listener.
         *
         * @param callable $callback
         * @return void 
         * @static 
         */
        public static function booting($callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->booting($callback);
        }

        /**
         * Register a new "booted" listener.
         *
         * @param callable $callback
         * @return void 
         * @static 
         */
        public static function booted($callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->booted($callback);
        }

        /**
         * {@inheritdoc}
         *
         * @return \Symfony\Component\HttpFoundation\Response 
         * @static 
         */
        public static function handle($request, $type = 1, $catch = true)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->handle($request, $type, $catch);
        }

        /**
         * Handle the incoming HTTP request and send the response to the browser.
         *
         * @param \Illuminate\Http\Request $request
         * @return void 
         * @static 
         */
        public static function handleRequest($request)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->handleRequest($request);
        }

        /**
         * Handle the incoming Artisan command.
         *
         * @param \Symfony\Component\Console\Input\InputInterface $input
         * @return int 
         * @static 
         */
        public static function handleCommand($input)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->handleCommand($input);
        }

        /**
         * Determine if the framework's base configuration should be merged.
         *
         * @return bool 
         * @static 
         */
        public static function shouldMergeFrameworkConfiguration()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->shouldMergeFrameworkConfiguration();
        }

        /**
         * Indicate that the framework's base configuration should not be merged.
         *
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function dontMergeFrameworkConfiguration()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->dontMergeFrameworkConfiguration();
        }

        /**
         * Determine if middleware has been disabled for the application.
         *
         * @return bool 
         * @static 
         */
        public static function shouldSkipMiddleware()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->shouldSkipMiddleware();
        }

        /**
         * Get the path to the cached services.php file.
         *
         * @return string 
         * @static 
         */
        public static function getCachedServicesPath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getCachedServicesPath();
        }

        /**
         * Get the path to the cached packages.php file.
         *
         * @return string 
         * @static 
         */
        public static function getCachedPackagesPath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getCachedPackagesPath();
        }

        /**
         * Determine if the application configuration is cached.
         *
         * @return bool 
         * @static 
         */
        public static function configurationIsCached()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->configurationIsCached();
        }

        /**
         * Get the path to the configuration cache file.
         *
         * @return string 
         * @static 
         */
        public static function getCachedConfigPath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getCachedConfigPath();
        }

        /**
         * Determine if the application routes are cached.
         *
         * @return bool 
         * @static 
         */
        public static function routesAreCached()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->routesAreCached();
        }

        /**
         * Get the path to the routes cache file.
         *
         * @return string 
         * @static 
         */
        public static function getCachedRoutesPath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getCachedRoutesPath();
        }

        /**
         * Determine if the application events are cached.
         *
         * @return bool 
         * @static 
         */
        public static function eventsAreCached()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->eventsAreCached();
        }

        /**
         * Get the path to the events cache file.
         *
         * @return string 
         * @static 
         */
        public static function getCachedEventsPath()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getCachedEventsPath();
        }

        /**
         * Add new prefix to list of absolute path prefixes.
         *
         * @param string $prefix
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function addAbsoluteCachePathPrefix($prefix)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->addAbsoluteCachePathPrefix($prefix);
        }

        /**
         * Get an instance of the maintenance mode manager implementation.
         *
         * @return \Illuminate\Contracts\Foundation\MaintenanceMode 
         * @static 
         */
        public static function maintenanceMode()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->maintenanceMode();
        }

        /**
         * Determine if the application is currently down for maintenance.
         *
         * @return bool 
         * @static 
         */
        public static function isDownForMaintenance()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isDownForMaintenance();
        }

        /**
         * Throw an HttpException with the given data.
         *
         * @param int $code
         * @param string $message
         * @param array $headers
         * @return never 
         * @throws \Symfony\Component\HttpKernel\Exception\HttpException
         * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
         * @static 
         */
        public static function abort($code, $message = '', $headers = [])
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->abort($code, $message, $headers);
        }

        /**
         * Register a terminating callback with the application.
         *
         * @param callable|string $callback
         * @return \Illuminate\Foundation\Application 
         * @static 
         */
        public static function terminating($callback)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->terminating($callback);
        }

        /**
         * Terminate the application.
         *
         * @return void 
         * @static 
         */
        public static function terminate()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->terminate();
        }

        /**
         * Get the service providers that have been loaded.
         *
         * @return array<string, bool> 
         * @static 
         */
        public static function getLoadedProviders()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getLoadedProviders();
        }

        /**
         * Determine if the given service provider is loaded.
         *
         * @param string $provider
         * @return bool 
         * @static 
         */
        public static function providerIsLoaded($provider)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->providerIsLoaded($provider);
        }

        /**
         * Get the application's deferred services.
         *
         * @return array 
         * @static 
         */
        public static function getDeferredServices()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getDeferredServices();
        }

        /**
         * Set the application's deferred services.
         *
         * @param array $services
         * @return void 
         * @static 
         */
        public static function setDeferredServices($services)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->setDeferredServices($services);
        }

        /**
         * Determine if the given service is a deferred service.
         *
         * @param string $service
         * @return bool 
         * @static 
         */
        public static function isDeferredService($service)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isDeferredService($service);
        }

        /**
         * Add an array of services to the application's deferred services.
         *
         * @param array $services
         * @return void 
         * @static 
         */
        public static function addDeferredServices($services)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->addDeferredServices($services);
        }

        /**
         * Remove an array of services from the application's deferred services.
         *
         * @param array $services
         * @return void 
         * @static 
         */
        public static function removeDeferredServices($services)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->removeDeferredServices($services);
        }

        /**
         * Configure the real-time facade namespace.
         *
         * @param string $namespace
         * @return void 
         * @static 
         */
        public static function provideFacades($namespace)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->provideFacades($namespace);
        }

        /**
         * Get the current application locale.
         *
         * @return string 
         * @static 
         */
        public static function getLocale()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getLocale();
        }

        /**
         * Get the current application locale.
         *
         * @return string 
         * @static 
         */
        public static function currentLocale()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->currentLocale();
        }

        /**
         * Get the current application fallback locale.
         *
         * @return string 
         * @static 
         */
        public static function getFallbackLocale()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getFallbackLocale();
        }

        /**
         * Set the current application locale.
         *
         * @param string $locale
         * @return void 
         * @static 
         */
        public static function setLocale($locale)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->setLocale($locale);
        }

        /**
         * Set the current application fallback locale.
         *
         * @param string $fallbackLocale
         * @return void 
         * @static 
         */
        public static function setFallbackLocale($fallbackLocale)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->setFallbackLocale($fallbackLocale);
        }

        /**
         * Determine if the application locale is the given locale.
         *
         * @param string $locale
         * @return bool 
         * @static 
         */
        public static function isLocale($locale)
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isLocale($locale);
        }

        /**
         * Register the core class aliases in the container.
         *
         * @return void 
         * @static 
         */
        public static function registerCoreContainerAliases()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->registerCoreContainerAliases();
        }

        /**
         * Flush the container of all bindings and resolved instances.
         *
         * @return void 
         * @static 
         */
        public static function flush()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->flush();
        }

        /**
         * Get the application namespace.
         *
         * @return string 
         * @throws \RuntimeException
         * @static 
         */
        public static function getNamespace()
        {
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getNamespace();
        }

        /**
         * Define a contextual binding.
         *
         * @param array|string $concrete
         * @return \Illuminate\Contracts\Container\ContextualBindingBuilder 
         * @static 
         */
        public static function when($concrete)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->when($concrete);
        }

        /**
         * Define a contextual binding based on an attribute.
         *
         * @param string $attribute
         * @param \Closure $handler
         * @return void 
         * @static 
         */
        public static function whenHasAttribute($attribute, $handler)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->whenHasAttribute($attribute, $handler);
        }

        /**
         * Returns true if the container can return an entry for the given identifier.
         * 
         * Returns false otherwise.
         * 
         * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
         * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
         *
         * @return bool 
         * @param string $id Identifier of the entry to look for.
         * @return bool 
         * @static 
         */
        public static function has($id)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->has($id);
        }

        /**
         * Determine if the given abstract type has been resolved.
         *
         * @param string $abstract
         * @return bool 
         * @static 
         */
        public static function resolved($abstract)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->resolved($abstract);
        }

        /**
         * Determine if a given type is shared.
         *
         * @param string $abstract
         * @return bool 
         * @static 
         */
        public static function isShared($abstract)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isShared($abstract);
        }

        /**
         * Determine if a given string is an alias.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function isAlias($name)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->isAlias($name);
        }

        /**
         * Register a binding with the container.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @param bool $shared
         * @return void 
         * @throws \TypeError
         * @static 
         */
        public static function bind($abstract, $concrete = null, $shared = false)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->bind($abstract, $concrete, $shared);
        }

        /**
         * Determine if the container has a method binding.
         *
         * @param string $method
         * @return bool 
         * @static 
         */
        public static function hasMethodBinding($method)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->hasMethodBinding($method);
        }

        /**
         * Bind a callback to resolve with Container::call.
         *
         * @param array|string $method
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function bindMethod($method, $callback)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->bindMethod($method, $callback);
        }

        /**
         * Get the method binding for the given method.
         *
         * @param string $method
         * @param mixed $instance
         * @return mixed 
         * @static 
         */
        public static function callMethodBinding($method, $instance)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->callMethodBinding($method, $instance);
        }

        /**
         * Add a contextual binding to the container.
         *
         * @param string $concrete
         * @param string $abstract
         * @param \Closure|string $implementation
         * @return void 
         * @static 
         */
        public static function addContextualBinding($concrete, $abstract, $implementation)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->addContextualBinding($concrete, $abstract, $implementation);
        }

        /**
         * Register a binding if it hasn't already been registered.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @param bool $shared
         * @return void 
         * @static 
         */
        public static function bindIf($abstract, $concrete = null, $shared = false)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->bindIf($abstract, $concrete, $shared);
        }

        /**
         * Register a shared binding in the container.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @return void 
         * @static 
         */
        public static function singleton($abstract, $concrete = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->singleton($abstract, $concrete);
        }

        /**
         * Register a shared binding if it hasn't already been registered.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @return void 
         * @static 
         */
        public static function singletonIf($abstract, $concrete = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->singletonIf($abstract, $concrete);
        }

        /**
         * Register a scoped binding in the container.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @return void 
         * @static 
         */
        public static function scoped($abstract, $concrete = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->scoped($abstract, $concrete);
        }

        /**
         * Register a scoped binding if it hasn't already been registered.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @return void 
         * @static 
         */
        public static function scopedIf($abstract, $concrete = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->scopedIf($abstract, $concrete);
        }

        /**
         * "Extend" an abstract type in the container.
         *
         * @param string $abstract
         * @param \Closure $closure
         * @return void 
         * @throws \InvalidArgumentException
         * @static 
         */
        public static function extend($abstract, $closure)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->extend($abstract, $closure);
        }

        /**
         * Register an existing instance as shared in the container.
         *
         * @template TInstance of mixed
         * @param string $abstract
         * @param TInstance $instance
         * @return TInstance 
         * @static 
         */
        public static function instance($abstract, $instance)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->instance($abstract, $instance);
        }

        /**
         * Assign a set of tags to a given binding.
         *
         * @param array|string $abstracts
         * @param array|mixed $tags
         * @return void 
         * @static 
         */
        public static function tag($abstracts, $tags)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->tag($abstracts, $tags);
        }

        /**
         * Resolve all of the bindings for a given tag.
         *
         * @param string $tag
         * @return iterable 
         * @static 
         */
        public static function tagged($tag)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->tagged($tag);
        }

        /**
         * Alias a type to a different name.
         *
         * @param string $abstract
         * @param string $alias
         * @return void 
         * @throws \LogicException
         * @static 
         */
        public static function alias($abstract, $alias)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->alias($abstract, $alias);
        }

        /**
         * Bind a new callback to an abstract's rebind event.
         *
         * @param string $abstract
         * @param \Closure $callback
         * @return mixed 
         * @static 
         */
        public static function rebinding($abstract, $callback)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->rebinding($abstract, $callback);
        }

        /**
         * Refresh an instance on the given target and method.
         *
         * @param string $abstract
         * @param mixed $target
         * @param string $method
         * @return mixed 
         * @static 
         */
        public static function refresh($abstract, $target, $method)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->refresh($abstract, $target, $method);
        }

        /**
         * Wrap the given closure such that its dependencies will be injected when executed.
         *
         * @param \Closure $callback
         * @param array $parameters
         * @return \Closure 
         * @static 
         */
        public static function wrap($callback, $parameters = [])
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->wrap($callback, $parameters);
        }

        /**
         * Call the given Closure / class@method and inject its dependencies.
         *
         * @param callable|string $callback
         * @param array<string, mixed> $parameters
         * @param string|null $defaultMethod
         * @return mixed 
         * @throws \InvalidArgumentException
         * @static 
         */
        public static function call($callback, $parameters = [], $defaultMethod = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->call($callback, $parameters, $defaultMethod);
        }

        /**
         * Get a closure to resolve the given type from the container.
         *
         * @template TClass of object
         * @param string|class-string<TClass> $abstract
         * @return ($abstract is class-string<TClass> ? \Closure(): TClass : \Closure(): mixed)
         * @static 
         */
        public static function factory($abstract)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->factory($abstract);
        }

        /**
         * An alias function name for make().
         *
         * @template TClass of object
         * @param string|class-string<TClass>|callable $abstract
         * @param array $parameters
         * @return ($abstract is class-string<TClass> ? TClass : mixed)
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         * @static 
         */
        public static function makeWith($abstract, $parameters = [])
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->makeWith($abstract, $parameters);
        }

        /**
         * {@inheritdoc}
         *
         * @template TClass of object
         * @param string|class-string<TClass> $id
         * @return ($id is class-string<TClass> ? TClass : mixed)
         * @static 
         */
        public static function get($id)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->get($id);
        }

        /**
         * Instantiate a concrete instance of the given type.
         *
         * @template TClass of object
         * @param \Closure(static, array):  TClass|class-string<TClass>  $concrete
         * @return TClass 
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         * @throws \Illuminate\Contracts\Container\CircularDependencyException
         * @static 
         */
        public static function build($concrete)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->build($concrete);
        }

        /**
         * Resolve a dependency based on an attribute.
         *
         * @param \ReflectionAttribute $attribute
         * @return mixed 
         * @static 
         */
        public static function resolveFromAttribute($attribute)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->resolveFromAttribute($attribute);
        }

        /**
         * Register a new before resolving callback for all types.
         *
         * @param \Closure|string $abstract
         * @param \Closure|null $callback
         * @return void 
         * @static 
         */
        public static function beforeResolving($abstract, $callback = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->beforeResolving($abstract, $callback);
        }

        /**
         * Register a new resolving callback.
         *
         * @param \Closure|string $abstract
         * @param \Closure|null $callback
         * @return void 
         * @static 
         */
        public static function resolving($abstract, $callback = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->resolving($abstract, $callback);
        }

        /**
         * Register a new after resolving callback for all types.
         *
         * @param \Closure|string $abstract
         * @param \Closure|null $callback
         * @return void 
         * @static 
         */
        public static function afterResolving($abstract, $callback = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->afterResolving($abstract, $callback);
        }

        /**
         * Register a new after resolving attribute callback for all types.
         *
         * @param string $attribute
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function afterResolvingAttribute($attribute, $callback)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->afterResolvingAttribute($attribute, $callback);
        }

        /**
         * Fire all of the after resolving attribute callbacks.
         *
         * @param \ReflectionAttribute[] $attributes
         * @param mixed $object
         * @return void 
         * @static 
         */
        public static function fireAfterResolvingAttributeCallbacks($attributes, $object)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->fireAfterResolvingAttributeCallbacks($attributes, $object);
        }

        /**
         * Get the container's bindings.
         *
         * @return array 
         * @static 
         */
        public static function getBindings()
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getBindings();
        }

        /**
         * Get the alias for an abstract if available.
         *
         * @param string $abstract
         * @return string 
         * @static 
         */
        public static function getAlias($abstract)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->getAlias($abstract);
        }

        /**
         * Remove all of the extender callbacks for a given type.
         *
         * @param string $abstract
         * @return void 
         * @static 
         */
        public static function forgetExtenders($abstract)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->forgetExtenders($abstract);
        }

        /**
         * Remove a resolved instance from the instance cache.
         *
         * @param string $abstract
         * @return void 
         * @static 
         */
        public static function forgetInstance($abstract)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->forgetInstance($abstract);
        }

        /**
         * Clear all of the instances from the container.
         *
         * @return void 
         * @static 
         */
        public static function forgetInstances()
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->forgetInstances();
        }

        /**
         * Clear all of the scoped instances from the container.
         *
         * @return void 
         * @static 
         */
        public static function forgetScopedInstances()
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->forgetScopedInstances();
        }

        /**
         * Get the globally available instance of the container.
         *
         * @return static 
         * @static 
         */
        public static function getInstance()
        {
            //Method inherited from \Illuminate\Container\Container 
            return \Illuminate\Foundation\Application::getInstance();
        }

        /**
         * Set the shared instance of the container.
         *
         * @param \Illuminate\Contracts\Container\Container|null $container
         * @return \Illuminate\Contracts\Container\Container|static 
         * @static 
         */
        public static function setInstance($container = null)
        {
            //Method inherited from \Illuminate\Container\Container 
            return \Illuminate\Foundation\Application::setInstance($container);
        }

        /**
         * Determine if a given offset exists.
         *
         * @param string $key
         * @return bool 
         * @static 
         */
        public static function offsetExists($key)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->offsetExists($key);
        }

        /**
         * Get the value at a given offset.
         *
         * @param string $key
         * @return mixed 
         * @static 
         */
        public static function offsetGet($key)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            return $instance->offsetGet($key);
        }

        /**
         * Set the value at a given offset.
         *
         * @param string $key
         * @param mixed $value
         * @return void 
         * @static 
         */
        public static function offsetSet($key, $value)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->offsetSet($key, $value);
        }

        /**
         * Unset the value at a given offset.
         *
         * @param string $key
         * @return void 
         * @static 
         */
        public static function offsetUnset($key)
        {
            //Method inherited from \Illuminate\Container\Container 
            /** @var \Illuminate\Foundation\Application $instance */
            $instance->offsetUnset($key);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Foundation\Application::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Foundation\Application::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Foundation\Application::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Illuminate\Foundation\Application::flushMacros();
        }

            }
    /**
     * 
     *
     * @method static \Illuminate\Contracts\Auth\Authenticatable|false loginUsingId(mixed $id, bool $remember = false)
     * @method static bool viaRemember()
     * @method static \Symfony\Component\HttpFoundation\Response|null basic(string $field = 'email', array $extraConditions = [])
     * @method static \Symfony\Component\HttpFoundation\Response|null onceBasic(string $field = 'email', array $extraConditions = [])
     * @method static bool attemptWhen(array $credentials = [], array|callable|null $callbacks = null, bool $remember = false)
     * @method static void logoutCurrentDevice()
     * @method static \Illuminate\Contracts\Auth\Authenticatable|null logoutOtherDevices(string $password)
     * @method static void attempting(mixed $callback)
     * @method static string getName()
     * @method static string getRecallerName()
     * @method static \Illuminate\Auth\SessionGuard setRememberDuration(int $minutes)
     * @method static \Illuminate\Contracts\Cookie\QueueingFactory getCookieJar()
     * @method static void setCookieJar(\Illuminate\Contracts\Cookie\QueueingFactory $cookie)
     * @method static \Illuminate\Contracts\Events\Dispatcher getDispatcher()
     * @method static void setDispatcher(\Illuminate\Contracts\Events\Dispatcher $events)
     * @method static \Illuminate\Contracts\Session\Session getSession()
     * @method static \Illuminate\Support\Timebox getTimebox()
     * @see \Illuminate\Auth\AuthManager
     * @see \Illuminate\Auth\SessionGuard
     */
    class Auth {
        /**
         * Attempt to get the guard from the local cache.
         *
         * @param string|null $name
         * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard 
         * @static 
         */
        public static function guard($name = null)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->guard($name);
        }

        /**
         * Create a session based authentication guard.
         *
         * @param string $name
         * @param array $config
         * @return \Illuminate\Auth\SessionGuard 
         * @static 
         */
        public static function createSessionDriver($name, $config)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->createSessionDriver($name, $config);
        }

        /**
         * Create a token based authentication guard.
         *
         * @param string $name
         * @param array $config
         * @return \Illuminate\Auth\TokenGuard 
         * @static 
         */
        public static function createTokenDriver($name, $config)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->createTokenDriver($name, $config);
        }

        /**
         * Get the default authentication driver name.
         *
         * @return string 
         * @static 
         */
        public static function getDefaultDriver()
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->getDefaultDriver();
        }

        /**
         * Set the default guard driver the factory should serve.
         *
         * @param string $name
         * @return void 
         * @static 
         */
        public static function shouldUse($name)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            $instance->shouldUse($name);
        }

        /**
         * Set the default authentication driver name.
         *
         * @param string $name
         * @return void 
         * @static 
         */
        public static function setDefaultDriver($name)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            $instance->setDefaultDriver($name);
        }

        /**
         * Register a new callback based request guard.
         *
         * @param string $driver
         * @param callable $callback
         * @return \Illuminate\Auth\AuthManager 
         * @static 
         */
        public static function viaRequest($driver, $callback)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->viaRequest($driver, $callback);
        }

        /**
         * Get the user resolver callback.
         *
         * @return \Closure 
         * @static 
         */
        public static function userResolver()
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->userResolver();
        }

        /**
         * Set the callback to be used to resolve users.
         *
         * @param \Closure $userResolver
         * @return \Illuminate\Auth\AuthManager 
         * @static 
         */
        public static function resolveUsersUsing($userResolver)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->resolveUsersUsing($userResolver);
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return \Illuminate\Auth\AuthManager 
         * @static 
         */
        public static function extend($driver, $callback)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->extend($driver, $callback);
        }

        /**
         * Register a custom provider creator Closure.
         *
         * @param string $name
         * @param \Closure $callback
         * @return \Illuminate\Auth\AuthManager 
         * @static 
         */
        public static function provider($name, $callback)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->provider($name, $callback);
        }

        /**
         * Determines if any guards have already been resolved.
         *
         * @return bool 
         * @static 
         */
        public static function hasResolvedGuards()
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->hasResolvedGuards();
        }

        /**
         * Forget all of the resolved guard instances.
         *
         * @return \Illuminate\Auth\AuthManager 
         * @static 
         */
        public static function forgetGuards()
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->forgetGuards();
        }

        /**
         * Set the application instance used by the manager.
         *
         * @param \Illuminate\Contracts\Foundation\Application $app
         * @return \Illuminate\Auth\AuthManager 
         * @static 
         */
        public static function setApplication($app)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->setApplication($app);
        }

        /**
         * Create the user provider implementation for the driver.
         *
         * @param string|null $provider
         * @return \Illuminate\Contracts\Auth\UserProvider|null 
         * @throws \InvalidArgumentException
         * @static 
         */
        public static function createUserProvider($provider = null)
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->createUserProvider($provider);
        }

        /**
         * Get the default user provider name.
         *
         * @return string 
         * @static 
         */
        public static function getDefaultUserProvider()
        {
            /** @var \Illuminate\Auth\AuthManager $instance */
            return $instance->getDefaultUserProvider();
        }

        /**
         * Get the currently authenticated user.
         *
         * @return \App\Models\Users|null 
         * @static 
         */
        public static function user()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->user();
        }

        /**
         * Get the currently authenticated user or throws an exception.
         *
         * @return \App\Models\Users 
         * @throws \Tymon\JWTAuth\Exceptions\UserNotDefinedException
         * @static 
         */
        public static function userOrFail()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->userOrFail();
        }

        /**
         * Validate a user's credentials.
         *
         * @param array $credentials
         * @return bool 
         * @static 
         */
        public static function validate($credentials = [])
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->validate($credentials);
        }

        /**
         * Attempt to authenticate the user using the given credentials and return the token.
         *
         * @param array $credentials
         * @param bool $login
         * @return bool|string 
         * @static 
         */
        public static function attempt($credentials = [], $login = true)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->attempt($credentials, $login);
        }

        /**
         * Create a token for a user.
         *
         * @param \Tymon\JWTAuth\Contracts\JWTSubject $user
         * @return string 
         * @static 
         */
        public static function login($user)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->login($user);
        }

        /**
         * Logout the user, thus invalidating the token.
         *
         * @param bool $forceForever
         * @return void 
         * @static 
         */
        public static function logout($forceForever = false)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            $instance->logout($forceForever);
        }

        /**
         * Refresh the token.
         *
         * @param bool $forceForever
         * @param bool $resetClaims
         * @return string 
         * @static 
         */
        public static function refresh($forceForever = false, $resetClaims = false)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->refresh($forceForever, $resetClaims);
        }

        /**
         * Invalidate the token.
         *
         * @param bool $forceForever
         * @return \Tymon\JWTAuth\JWT 
         * @static 
         */
        public static function invalidate($forceForever = false)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->invalidate($forceForever);
        }

        /**
         * Create a new token by User id.
         *
         * @param mixed $id
         * @return string|null 
         * @static 
         */
        public static function tokenById($id)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->tokenById($id);
        }

        /**
         * Log a user into the application using their credentials.
         *
         * @param array $credentials
         * @return bool 
         * @static 
         */
        public static function once($credentials = [])
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->once($credentials);
        }

        /**
         * Log the given User into the application.
         *
         * @param mixed $id
         * @return bool 
         * @static 
         */
        public static function onceUsingId($id)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->onceUsingId($id);
        }

        /**
         * Alias for onceUsingId.
         *
         * @param mixed $id
         * @return bool 
         * @static 
         */
        public static function byId($id)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->byId($id);
        }

        /**
         * Add any custom claims.
         *
         * @param array $claims
         * @return \Tymon\JWTAuth\JWTGuard 
         * @static 
         */
        public static function claims($claims)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->claims($claims);
        }

        /**
         * Get the raw Payload instance.
         *
         * @return \Tymon\JWTAuth\Payload 
         * @static 
         */
        public static function getPayload()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->getPayload();
        }

        /**
         * Alias for getPayload().
         *
         * @return \Tymon\JWTAuth\Payload 
         * @static 
         */
        public static function payload()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->payload();
        }

        /**
         * Set the token.
         *
         * @param \Tymon\JWTAuth\Token|string $token
         * @return \Tymon\JWTAuth\JWTGuard 
         * @static 
         */
        public static function setToken($token)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->setToken($token);
        }

        /**
         * Set the token ttl.
         *
         * @param int $ttl
         * @return \Tymon\JWTAuth\JWTGuard 
         * @static 
         */
        public static function setTTL($ttl)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->setTTL($ttl);
        }

        /**
         * Get the user provider used by the guard.
         *
         * @return \Illuminate\Contracts\Auth\UserProvider 
         * @static 
         */
        public static function getProvider()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->getProvider();
        }

        /**
         * Set the user provider used by the guard.
         *
         * @param \Illuminate\Contracts\Auth\UserProvider $provider
         * @return \Tymon\JWTAuth\JWTGuard 
         * @static 
         */
        public static function setProvider($provider)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->setProvider($provider);
        }

        /**
         * Return the currently cached user.
         *
         * @return \App\Models\Users|null 
         * @static 
         */
        public static function getUser()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->getUser();
        }

        /**
         * Get the current request instance.
         *
         * @return \Illuminate\Http\Request 
         * @static 
         */
        public static function getRequest()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->getRequest();
        }

        /**
         * Set the current request instance.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Tymon\JWTAuth\JWTGuard 
         * @static 
         */
        public static function setRequest($request)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->setRequest($request);
        }

        /**
         * Get the token's auth factory.
         *
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function factory()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->factory();
        }

        /**
         * Get the last user we attempted to authenticate.
         *
         * @return \App\Models\Users 
         * @static 
         */
        public static function getLastAttempted()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->getLastAttempted();
        }

        /**
         * Determine if the current user is authenticated. If not, throw an exception.
         *
         * @return \App\Models\Users 
         * @throws \Illuminate\Auth\AuthenticationException
         * @static 
         */
        public static function authenticate()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->authenticate();
        }

        /**
         * Determine if the guard has a user instance.
         *
         * @return bool 
         * @static 
         */
        public static function hasUser()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->hasUser();
        }

        /**
         * Determine if the current user is authenticated.
         *
         * @return bool 
         * @static 
         */
        public static function check()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->check();
        }

        /**
         * Determine if the current user is a guest.
         *
         * @return bool 
         * @static 
         */
        public static function guest()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->guest();
        }

        /**
         * Get the ID for the currently authenticated user.
         *
         * @return int|string|null 
         * @static 
         */
        public static function id()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->id();
        }

        /**
         * Set the current user.
         *
         * @param \Illuminate\Contracts\Auth\Authenticatable $user
         * @return \Tymon\JWTAuth\JWTGuard 
         * @static 
         */
        public static function setUser($user)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->setUser($user);
        }

        /**
         * Forget the current user.
         *
         * @return \Tymon\JWTAuth\JWTGuard 
         * @static 
         */
        public static function forgetUser()
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->forgetUser();
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Tymon\JWTAuth\JWTGuard::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Tymon\JWTAuth\JWTGuard::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Tymon\JWTAuth\JWTGuard::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Tymon\JWTAuth\JWTGuard::flushMacros();
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed 
         * @throws \BadMethodCallException
         * @static 
         */
        public static function macroCall($method, $parameters)
        {
            /** @var \Tymon\JWTAuth\JWTGuard $instance */
            return $instance->macroCall($method, $parameters);
        }

            }
    /**
     * 
     *
     * @see \Illuminate\Config\Repository
     */
    class Config {
        /**
         * Determine if the given configuration value exists.
         *
         * @param string $key
         * @return bool 
         * @static 
         */
        public static function has($key)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->has($key);
        }

        /**
         * Get the specified configuration value.
         *
         * @param array|string $key
         * @param mixed $default
         * @return mixed 
         * @static 
         */
        public static function get($key, $default = null)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->get($key, $default);
        }

        /**
         * Get many configuration values.
         *
         * @param array<string|int,mixed> $keys
         * @return array<string,mixed> 
         * @static 
         */
        public static function getMany($keys)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->getMany($keys);
        }

        /**
         * Get the specified string configuration value.
         *
         * @param string $key
         * @param (\Closure():(string|null))|string|null $default
         * @return string 
         * @static 
         */
        public static function string($key, $default = null)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->string($key, $default);
        }

        /**
         * Get the specified integer configuration value.
         *
         * @param string $key
         * @param (\Closure():(int|null))|int|null $default
         * @return int 
         * @static 
         */
        public static function integer($key, $default = null)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->integer($key, $default);
        }

        /**
         * Get the specified float configuration value.
         *
         * @param string $key
         * @param (\Closure():(float|null))|float|null $default
         * @return float 
         * @static 
         */
        public static function float($key, $default = null)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->float($key, $default);
        }

        /**
         * Get the specified boolean configuration value.
         *
         * @param string $key
         * @param (\Closure():(bool|null))|bool|null $default
         * @return bool 
         * @static 
         */
        public static function boolean($key, $default = null)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->boolean($key, $default);
        }

        /**
         * Get the specified array configuration value.
         *
         * @param string $key
         * @param (\Closure():(array<array-key, mixed>|null))|array<array-key, mixed>|null $default
         * @return array<array-key, mixed> 
         * @static 
         */
        public static function array($key, $default = null)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->array($key, $default);
        }

        /**
         * Set a given configuration value.
         *
         * @param array|string $key
         * @param mixed $value
         * @return void 
         * @static 
         */
        public static function set($key, $value = null)
        {
            /** @var \Illuminate\Config\Repository $instance */
            $instance->set($key, $value);
        }

        /**
         * Prepend a value onto an array configuration value.
         *
         * @param string $key
         * @param mixed $value
         * @return void 
         * @static 
         */
        public static function prepend($key, $value)
        {
            /** @var \Illuminate\Config\Repository $instance */
            $instance->prepend($key, $value);
        }

        /**
         * Push a value onto an array configuration value.
         *
         * @param string $key
         * @param mixed $value
         * @return void 
         * @static 
         */
        public static function push($key, $value)
        {
            /** @var \Illuminate\Config\Repository $instance */
            $instance->push($key, $value);
        }

        /**
         * Get all of the configuration items for the application.
         *
         * @return array 
         * @static 
         */
        public static function all()
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->all();
        }

        /**
         * Determine if the given configuration option exists.
         *
         * @param string $key
         * @return bool 
         * @static 
         */
        public static function offsetExists($key)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->offsetExists($key);
        }

        /**
         * Get a configuration option.
         *
         * @param string $key
         * @return mixed 
         * @static 
         */
        public static function offsetGet($key)
        {
            /** @var \Illuminate\Config\Repository $instance */
            return $instance->offsetGet($key);
        }

        /**
         * Set a configuration option.
         *
         * @param string $key
         * @param mixed $value
         * @return void 
         * @static 
         */
        public static function offsetSet($key, $value)
        {
            /** @var \Illuminate\Config\Repository $instance */
            $instance->offsetSet($key, $value);
        }

        /**
         * Unset a configuration option.
         *
         * @param string $key
         * @return void 
         * @static 
         */
        public static function offsetUnset($key)
        {
            /** @var \Illuminate\Config\Repository $instance */
            $instance->offsetUnset($key);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Config\Repository::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Config\Repository::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Config\Repository::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Illuminate\Config\Repository::flushMacros();
        }

            }
    /**
     * 
     *
     * @see \Illuminate\Database\DatabaseManager
     */
    class DB {
        /**
         * Get a database connection instance.
         *
         * @param string|null $name
         * @return \Illuminate\Database\Connection 
         * @static 
         */
        public static function connection($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->connection($name);
        }

        /**
         * Build a database connection instance from the given configuration.
         *
         * @param array $config
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function build($config)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->build($config);
        }

        /**
         * Calculate the dynamic connection name for an on-demand connection based on its configuration.
         *
         * @param array $config
         * @return string 
         * @static 
         */
        public static function calculateDynamicConnectionName($config)
        {
            return \Illuminate\Database\DatabaseManager::calculateDynamicConnectionName($config);
        }

        /**
         * Get a database connection instance from the given configuration.
         *
         * @param string $name
         * @param array $config
         * @param bool $force
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function connectUsing($name, $config, $force = false)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->connectUsing($name, $config, $force);
        }

        /**
         * Disconnect from the given database and remove from local cache.
         *
         * @param string|null $name
         * @return void 
         * @static 
         */
        public static function purge($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->purge($name);
        }

        /**
         * Disconnect from the given database.
         *
         * @param string|null $name
         * @return void 
         * @static 
         */
        public static function disconnect($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->disconnect($name);
        }

        /**
         * Reconnect to the given database.
         *
         * @param string|null $name
         * @return \Illuminate\Database\Connection 
         * @static 
         */
        public static function reconnect($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->reconnect($name);
        }

        /**
         * Set the default database connection for the callback execution.
         *
         * @param string $name
         * @param callable $callback
         * @return mixed 
         * @static 
         */
        public static function usingConnection($name, $callback)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->usingConnection($name, $callback);
        }

        /**
         * Get the default connection name.
         *
         * @return string 
         * @static 
         */
        public static function getDefaultConnection()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->getDefaultConnection();
        }

        /**
         * Set the default connection name.
         *
         * @param string $name
         * @return void 
         * @static 
         */
        public static function setDefaultConnection($name)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->setDefaultConnection($name);
        }

        /**
         * Get all of the supported drivers.
         *
         * @return string[] 
         * @static 
         */
        public static function supportedDrivers()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->supportedDrivers();
        }

        /**
         * Get all of the drivers that are actually available.
         *
         * @return string[] 
         * @static 
         */
        public static function availableDrivers()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->availableDrivers();
        }

        /**
         * Register an extension connection resolver.
         *
         * @param string $name
         * @param callable $resolver
         * @return void 
         * @static 
         */
        public static function extend($name, $resolver)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->extend($name, $resolver);
        }

        /**
         * Remove an extension connection resolver.
         *
         * @param string $name
         * @return void 
         * @static 
         */
        public static function forgetExtension($name)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->forgetExtension($name);
        }

        /**
         * Return all of the created connections.
         *
         * @return array<string, \Illuminate\Database\Connection> 
         * @static 
         */
        public static function getConnections()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->getConnections();
        }

        /**
         * Set the database reconnector callback.
         *
         * @param callable $reconnector
         * @return void 
         * @static 
         */
        public static function setReconnector($reconnector)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->setReconnector($reconnector);
        }

        /**
         * Set the application instance used by the manager.
         *
         * @param \Illuminate\Contracts\Foundation\Application $app
         * @return \Illuminate\Database\DatabaseManager 
         * @static 
         */
        public static function setApplication($app)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->setApplication($app);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Database\DatabaseManager::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Database\DatabaseManager::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Database\DatabaseManager::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Illuminate\Database\DatabaseManager::flushMacros();
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed 
         * @throws \BadMethodCallException
         * @static 
         */
        public static function macroCall($method, $parameters)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->macroCall($method, $parameters);
        }

        /**
         * Get a human-readable name for the given connection driver.
         *
         * @return string 
         * @static 
         */
        public static function getDriverTitle()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getDriverTitle();
        }

        /**
         * Run an insert statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @param string|null $sequence
         * @return bool 
         * @static 
         */
        public static function insert($query, $bindings = [], $sequence = null)
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->insert($query, $bindings, $sequence);
        }

        /**
         * Get the connection's last insert ID.
         *
         * @return string|int|null 
         * @static 
         */
        public static function getLastInsertId()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getLastInsertId();
        }

        /**
         * Determine if the connected database is a MariaDB database.
         *
         * @return bool 
         * @static 
         */
        public static function isMaria()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->isMaria();
        }

        /**
         * Get the server version for the connection.
         *
         * @return string 
         * @static 
         */
        public static function getServerVersion()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getServerVersion();
        }

        /**
         * Get a schema builder instance for the connection.
         *
         * @return \Illuminate\Database\Schema\MySqlBuilder 
         * @static 
         */
        public static function getSchemaBuilder()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getSchemaBuilder();
        }

        /**
         * Get the schema state for the connection.
         *
         * @param \Illuminate\Filesystem\Filesystem|null $files
         * @param callable|null $processFactory
         * @return \Illuminate\Database\Schema\MySqlSchemaState 
         * @static 
         */
        public static function getSchemaState($files = null, $processFactory = null)
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getSchemaState($files, $processFactory);
        }

        /**
         * Set the query grammar to the default implementation.
         *
         * @return void 
         * @static 
         */
        public static function useDefaultQueryGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->useDefaultQueryGrammar();
        }

        /**
         * Set the schema grammar to the default implementation.
         *
         * @return void 
         * @static 
         */
        public static function useDefaultSchemaGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->useDefaultSchemaGrammar();
        }

        /**
         * Set the query post processor to the default implementation.
         *
         * @return void 
         * @static 
         */
        public static function useDefaultPostProcessor()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->useDefaultPostProcessor();
        }

        /**
         * Begin a fluent query against a database table.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Contracts\Database\Query\Expression|string $table
         * @param string|null $as
         * @return \Illuminate\Database\Query\Builder 
         * @static 
         */
        public static function table($table, $as = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->table($table, $as);
        }

        /**
         * Get a new query builder instance.
         *
         * @return \Illuminate\Database\Query\Builder 
         * @static 
         */
        public static function query()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->query();
        }

        /**
         * Run a select statement and return a single result.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return mixed 
         * @static 
         */
        public static function selectOne($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->selectOne($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement and return the first column of the first row.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return mixed 
         * @throws \Illuminate\Database\MultipleColumnsSelectedException
         * @static 
         */
        public static function scalar($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->scalar($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return array 
         * @static 
         */
        public static function selectFromWriteConnection($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->selectFromWriteConnection($query, $bindings);
        }

        /**
         * Run a select statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return array 
         * @static 
         */
        public static function select($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->select($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement against the database and returns all of the result sets.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return array 
         * @static 
         */
        public static function selectResultSets($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->selectResultSets($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement against the database and returns a generator.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return \Generator<int, \stdClass> 
         * @static 
         */
        public static function cursor($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->cursor($query, $bindings, $useReadPdo);
        }

        /**
         * Run an update statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return int 
         * @static 
         */
        public static function update($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->update($query, $bindings);
        }

        /**
         * Run a delete statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return int 
         * @static 
         */
        public static function delete($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->delete($query, $bindings);
        }

        /**
         * Execute an SQL statement and return the boolean result.
         *
         * @param string $query
         * @param array $bindings
         * @return bool 
         * @static 
         */
        public static function statement($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->statement($query, $bindings);
        }

        /**
         * Run an SQL statement and get the number of rows affected.
         *
         * @param string $query
         * @param array $bindings
         * @return int 
         * @static 
         */
        public static function affectingStatement($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->affectingStatement($query, $bindings);
        }

        /**
         * Run a raw, unprepared query against the PDO connection.
         *
         * @param string $query
         * @return bool 
         * @static 
         */
        public static function unprepared($query)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->unprepared($query);
        }

        /**
         * Get the number of open connections for the database.
         *
         * @return int|null 
         * @static 
         */
        public static function threadCount()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->threadCount();
        }

        /**
         * Execute the given callback in "dry run" mode.
         *
         * @param \Closure $callback
         * @return array 
         * @static 
         */
        public static function pretend($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->pretend($callback);
        }

        /**
         * Execute the given callback without "pretending".
         *
         * @param \Closure $callback
         * @return mixed 
         * @static 
         */
        public static function withoutPretending($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->withoutPretending($callback);
        }

        /**
         * Bind values to their parameters in the given statement.
         *
         * @param \PDOStatement $statement
         * @param array $bindings
         * @return void 
         * @static 
         */
        public static function bindValues($statement, $bindings)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->bindValues($statement, $bindings);
        }

        /**
         * Prepare the query bindings for execution.
         *
         * @param array $bindings
         * @return array 
         * @static 
         */
        public static function prepareBindings($bindings)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->prepareBindings($bindings);
        }

        /**
         * Log a query in the connection's query log.
         *
         * @param string $query
         * @param array $bindings
         * @param float|null $time
         * @return void 
         * @static 
         */
        public static function logQuery($query, $bindings, $time = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->logQuery($query, $bindings, $time);
        }

        /**
         * Register a callback to be invoked when the connection queries for longer than a given amount of time.
         *
         * @param \DateTimeInterface|\Carbon\CarbonInterval|float|int $threshold
         * @param callable $handler
         * @return void 
         * @static 
         */
        public static function whenQueryingForLongerThan($threshold, $handler)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->whenQueryingForLongerThan($threshold, $handler);
        }

        /**
         * Allow all the query duration handlers to run again, even if they have already run.
         *
         * @return void 
         * @static 
         */
        public static function allowQueryDurationHandlersToRunAgain()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->allowQueryDurationHandlersToRunAgain();
        }

        /**
         * Get the duration of all run queries in milliseconds.
         *
         * @return float 
         * @static 
         */
        public static function totalQueryDuration()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->totalQueryDuration();
        }

        /**
         * Reset the duration of all run queries.
         *
         * @return void 
         * @static 
         */
        public static function resetTotalQueryDuration()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->resetTotalQueryDuration();
        }

        /**
         * Reconnect to the database if a PDO connection is missing.
         *
         * @return void 
         * @static 
         */
        public static function reconnectIfMissingConnection()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->reconnectIfMissingConnection();
        }

        /**
         * Register a hook to be run just before a database transaction is started.
         *
         * @param \Closure $callback
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function beforeStartingTransaction($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->beforeStartingTransaction($callback);
        }

        /**
         * Register a hook to be run just before a database query is executed.
         *
         * @param \Closure $callback
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function beforeExecuting($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->beforeExecuting($callback);
        }

        /**
         * Register a database query listener with the connection.
         *
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function listen($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->listen($callback);
        }

        /**
         * Get a new raw query expression.
         *
         * @param mixed $value
         * @return \Illuminate\Contracts\Database\Query\Expression 
         * @static 
         */
        public static function raw($value)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->raw($value);
        }

        /**
         * Escape a value for safe SQL embedding.
         *
         * @param string|float|int|bool|null $value
         * @param bool $binary
         * @return string 
         * @static 
         */
        public static function escape($value, $binary = false)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->escape($value, $binary);
        }

        /**
         * Determine if the database connection has modified any database records.
         *
         * @return bool 
         * @static 
         */
        public static function hasModifiedRecords()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->hasModifiedRecords();
        }

        /**
         * Indicate if any records have been modified.
         *
         * @param bool $value
         * @return void 
         * @static 
         */
        public static function recordsHaveBeenModified($value = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->recordsHaveBeenModified($value);
        }

        /**
         * Set the record modification state.
         *
         * @param bool $value
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setRecordModificationState($value)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setRecordModificationState($value);
        }

        /**
         * Reset the record modification state.
         *
         * @return void 
         * @static 
         */
        public static function forgetRecordModificationState()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->forgetRecordModificationState();
        }

        /**
         * Indicate that the connection should use the write PDO connection for reads.
         *
         * @param bool $value
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function useWriteConnectionWhenReading($value = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->useWriteConnectionWhenReading($value);
        }

        /**
         * Get the current PDO connection.
         *
         * @return \PDO 
         * @static 
         */
        public static function getPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getPdo();
        }

        /**
         * Get the current PDO connection parameter without executing any reconnect logic.
         *
         * @return \PDO|\Closure|null 
         * @static 
         */
        public static function getRawPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getRawPdo();
        }

        /**
         * Get the current PDO connection used for reading.
         *
         * @return \PDO 
         * @static 
         */
        public static function getReadPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getReadPdo();
        }

        /**
         * Get the current read PDO connection parameter without executing any reconnect logic.
         *
         * @return \PDO|\Closure|null 
         * @static 
         */
        public static function getRawReadPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getRawReadPdo();
        }

        /**
         * Set the PDO connection.
         *
         * @param \PDO|\Closure|null $pdo
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setPdo($pdo)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setPdo($pdo);
        }

        /**
         * Set the PDO connection used for reading.
         *
         * @param \PDO|\Closure|null $pdo
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setReadPdo($pdo)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setReadPdo($pdo);
        }

        /**
         * Get the database connection name.
         *
         * @return string|null 
         * @static 
         */
        public static function getName()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getName();
        }

        /**
         * Get the database connection full name.
         *
         * @return string|null 
         * @static 
         */
        public static function getNameWithReadWriteType()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getNameWithReadWriteType();
        }

        /**
         * Get an option from the configuration options.
         *
         * @param string|null $option
         * @return mixed 
         * @static 
         */
        public static function getConfig($option = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getConfig($option);
        }

        /**
         * Get the PDO driver name.
         *
         * @return string 
         * @static 
         */
        public static function getDriverName()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getDriverName();
        }

        /**
         * Get the query grammar used by the connection.
         *
         * @return \Illuminate\Database\Query\Grammars\Grammar 
         * @static 
         */
        public static function getQueryGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getQueryGrammar();
        }

        /**
         * Set the query grammar used by the connection.
         *
         * @param \Illuminate\Database\Query\Grammars\Grammar $grammar
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setQueryGrammar($grammar)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setQueryGrammar($grammar);
        }

        /**
         * Get the schema grammar used by the connection.
         *
         * @return \Illuminate\Database\Schema\Grammars\Grammar 
         * @static 
         */
        public static function getSchemaGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getSchemaGrammar();
        }

        /**
         * Set the schema grammar used by the connection.
         *
         * @param \Illuminate\Database\Schema\Grammars\Grammar $grammar
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setSchemaGrammar($grammar)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setSchemaGrammar($grammar);
        }

        /**
         * Get the query post processor used by the connection.
         *
         * @return \Illuminate\Database\Query\Processors\Processor 
         * @static 
         */
        public static function getPostProcessor()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getPostProcessor();
        }

        /**
         * Set the query post processor used by the connection.
         *
         * @param \Illuminate\Database\Query\Processors\Processor $processor
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setPostProcessor($processor)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setPostProcessor($processor);
        }

        /**
         * Get the event dispatcher used by the connection.
         *
         * @return \Illuminate\Contracts\Events\Dispatcher 
         * @static 
         */
        public static function getEventDispatcher()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getEventDispatcher();
        }

        /**
         * Set the event dispatcher instance on the connection.
         *
         * @param \Illuminate\Contracts\Events\Dispatcher $events
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setEventDispatcher($events)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setEventDispatcher($events);
        }

        /**
         * Unset the event dispatcher for this connection.
         *
         * @return void 
         * @static 
         */
        public static function unsetEventDispatcher()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->unsetEventDispatcher();
        }

        /**
         * Set the transaction manager instance on the connection.
         *
         * @param \Illuminate\Database\DatabaseTransactionsManager $manager
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setTransactionManager($manager)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setTransactionManager($manager);
        }

        /**
         * Unset the transaction manager for this connection.
         *
         * @return void 
         * @static 
         */
        public static function unsetTransactionManager()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->unsetTransactionManager();
        }

        /**
         * Determine if the connection is in a "dry run".
         *
         * @return bool 
         * @static 
         */
        public static function pretending()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->pretending();
        }

        /**
         * Get the connection query log.
         *
         * @return array 
         * @static 
         */
        public static function getQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getQueryLog();
        }

        /**
         * Get the connection query log with embedded bindings.
         *
         * @return array 
         * @static 
         */
        public static function getRawQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getRawQueryLog();
        }

        /**
         * Clear the query log.
         *
         * @return void 
         * @static 
         */
        public static function flushQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->flushQueryLog();
        }

        /**
         * Enable the query log on the connection.
         *
         * @return void 
         * @static 
         */
        public static function enableQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->enableQueryLog();
        }

        /**
         * Disable the query log on the connection.
         *
         * @return void 
         * @static 
         */
        public static function disableQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->disableQueryLog();
        }

        /**
         * Determine whether we're logging queries.
         *
         * @return bool 
         * @static 
         */
        public static function logging()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->logging();
        }

        /**
         * Get the name of the connected database.
         *
         * @return string 
         * @static 
         */
        public static function getDatabaseName()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getDatabaseName();
        }

        /**
         * Set the name of the connected database.
         *
         * @param string $database
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setDatabaseName($database)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setDatabaseName($database);
        }

        /**
         * Set the read / write type of the connection.
         *
         * @param string|null $readWriteType
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setReadWriteType($readWriteType)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setReadWriteType($readWriteType);
        }

        /**
         * Get the table prefix for the connection.
         *
         * @return string 
         * @static 
         */
        public static function getTablePrefix()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getTablePrefix();
        }

        /**
         * Set the table prefix in use by the connection.
         *
         * @param string $prefix
         * @return \Illuminate\Database\MySqlConnection 
         * @static 
         */
        public static function setTablePrefix($prefix)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setTablePrefix($prefix);
        }

        /**
         * Set the table prefix and return the grammar.
         *
         * @template TGrammar of \Illuminate\Database\Grammar
         * @param TGrammar $grammar
         * @return TGrammar 
         * @static 
         */
        public static function withTablePrefix($grammar)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->withTablePrefix($grammar);
        }

        /**
         * Execute the given callback without table prefix.
         *
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function withoutTablePrefix($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->withoutTablePrefix($callback);
        }

        /**
         * Register a connection resolver.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return void 
         * @static 
         */
        public static function resolverFor($driver, $callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            \Illuminate\Database\MySqlConnection::resolverFor($driver, $callback);
        }

        /**
         * Get the connection resolver for the given driver.
         *
         * @param string $driver
         * @return \Closure|null 
         * @static 
         */
        public static function getResolver($driver)
        {
            //Method inherited from \Illuminate\Database\Connection 
            return \Illuminate\Database\MySqlConnection::getResolver($driver);
        }

        /**
         * 
         *
         * @template TReturn of mixed
         * 
         * Execute a Closure within a transaction.
         * @param (\Closure(static): TReturn) $callback
         * @param int $attempts
         * @return TReturn 
         * @throws \Throwable
         * @static 
         */
        public static function transaction($callback, $attempts = 1)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->transaction($callback, $attempts);
        }

        /**
         * Start a new database transaction.
         *
         * @return void 
         * @throws \Throwable
         * @static 
         */
        public static function beginTransaction()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->beginTransaction();
        }

        /**
         * Commit the active database transaction.
         *
         * @return void 
         * @throws \Throwable
         * @static 
         */
        public static function commit()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->commit();
        }

        /**
         * Rollback the active database transaction.
         *
         * @param int|null $toLevel
         * @return void 
         * @throws \Throwable
         * @static 
         */
        public static function rollBack($toLevel = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->rollBack($toLevel);
        }

        /**
         * Get the number of active transactions.
         *
         * @return int 
         * @static 
         */
        public static function transactionLevel()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->transactionLevel();
        }

        /**
         * Execute the callback after a transaction commits.
         *
         * @param callable $callback
         * @return void 
         * @throws \RuntimeException
         * @static 
         */
        public static function afterCommit($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->afterCommit($callback);
        }

            }
    /**
     * 
     *
     * @see \Illuminate\Hashing\HashManager
     * @see \Illuminate\Hashing\AbstractHasher
     */
    class Hash {
        /**
         * Create an instance of the Bcrypt hash Driver.
         *
         * @return \Illuminate\Hashing\BcryptHasher 
         * @static 
         */
        public static function createBcryptDriver()
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->createBcryptDriver();
        }

        /**
         * Create an instance of the Argon2i hash Driver.
         *
         * @return \Illuminate\Hashing\ArgonHasher 
         * @static 
         */
        public static function createArgonDriver()
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->createArgonDriver();
        }

        /**
         * Create an instance of the Argon2id hash Driver.
         *
         * @return \Illuminate\Hashing\Argon2IdHasher 
         * @static 
         */
        public static function createArgon2idDriver()
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->createArgon2idDriver();
        }

        /**
         * Get information about the given hashed value.
         *
         * @param string $hashedValue
         * @return array 
         * @static 
         */
        public static function info($hashedValue)
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->info($hashedValue);
        }

        /**
         * Hash the given value.
         *
         * @param string $value
         * @param array $options
         * @return string 
         * @static 
         */
        public static function make($value, $options = [])
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->make($value, $options);
        }

        /**
         * Check the given plain value against a hash.
         *
         * @param string $value
         * @param string $hashedValue
         * @param array $options
         * @return bool 
         * @static 
         */
        public static function check($value, $hashedValue, $options = [])
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->check($value, $hashedValue, $options);
        }

        /**
         * Check if the given hash has been hashed using the given options.
         *
         * @param string $hashedValue
         * @param array $options
         * @return bool 
         * @static 
         */
        public static function needsRehash($hashedValue, $options = [])
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->needsRehash($hashedValue, $options);
        }

        /**
         * Determine if a given string is already hashed.
         *
         * @param string $value
         * @return bool 
         * @static 
         */
        public static function isHashed($value)
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->isHashed($value);
        }

        /**
         * Get the default driver name.
         *
         * @return string 
         * @static 
         */
        public static function getDefaultDriver()
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->getDefaultDriver();
        }

        /**
         * Verifies that the configuration is less than or equal to what is configured.
         *
         * @param array $value
         * @return bool 
         * @internal 
         * @static 
         */
        public static function verifyConfiguration($value)
        {
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->verifyConfiguration($value);
        }

        /**
         * Get a driver instance.
         *
         * @param string|null $driver
         * @return mixed 
         * @throws \InvalidArgumentException
         * @static 
         */
        public static function driver($driver = null)
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->driver($driver);
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return \Illuminate\Hashing\HashManager 
         * @static 
         */
        public static function extend($driver, $callback)
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->extend($driver, $callback);
        }

        /**
         * Get all of the created "drivers".
         *
         * @return array 
         * @static 
         */
        public static function getDrivers()
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->getDrivers();
        }

        /**
         * Get the container instance used by the manager.
         *
         * @return \Illuminate\Contracts\Container\Container 
         * @static 
         */
        public static function getContainer()
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->getContainer();
        }

        /**
         * Set the container instance used by the manager.
         *
         * @param \Illuminate\Contracts\Container\Container $container
         * @return \Illuminate\Hashing\HashManager 
         * @static 
         */
        public static function setContainer($container)
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->setContainer($container);
        }

        /**
         * Forget all of the resolved driver instances.
         *
         * @return \Illuminate\Hashing\HashManager 
         * @static 
         */
        public static function forgetDrivers()
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Illuminate\Hashing\HashManager $instance */
            return $instance->forgetDrivers();
        }

            }
    /**
     * 
     *
     * @method static \Illuminate\Http\Client\PendingRequest baseUrl(string $url)
     * @method static \Illuminate\Http\Client\PendingRequest withBody(\Psr\Http\Message\StreamInterface|string $content, string $contentType = 'application/json')
     * @method static \Illuminate\Http\Client\PendingRequest asJson()
     * @method static \Illuminate\Http\Client\PendingRequest asForm()
     * @method static \Illuminate\Http\Client\PendingRequest attach(string|array $name, string|resource $contents = '', string|null $filename = null, array $headers = [])
     * @method static \Illuminate\Http\Client\PendingRequest asMultipart()
     * @method static \Illuminate\Http\Client\PendingRequest bodyFormat(string $format)
     * @method static \Illuminate\Http\Client\PendingRequest withQueryParameters(array $parameters)
     * @method static \Illuminate\Http\Client\PendingRequest contentType(string $contentType)
     * @method static \Illuminate\Http\Client\PendingRequest acceptJson()
     * @method static \Illuminate\Http\Client\PendingRequest accept(string $contentType)
     * @method static \Illuminate\Http\Client\PendingRequest withHeaders(array $headers)
     * @method static \Illuminate\Http\Client\PendingRequest withHeader(string $name, mixed $value)
     * @method static \Illuminate\Http\Client\PendingRequest replaceHeaders(array $headers)
     * @method static \Illuminate\Http\Client\PendingRequest withBasicAuth(string $username, string $password)
     * @method static \Illuminate\Http\Client\PendingRequest withDigestAuth(string $username, string $password)
     * @method static \Illuminate\Http\Client\PendingRequest withToken(string $token, string $type = 'Bearer')
     * @method static \Illuminate\Http\Client\PendingRequest withUserAgent(string|bool $userAgent)
     * @method static \Illuminate\Http\Client\PendingRequest withUrlParameters(array $parameters = [])
     * @method static \Illuminate\Http\Client\PendingRequest withCookies(array $cookies, string $domain)
     * @method static \Illuminate\Http\Client\PendingRequest maxRedirects(int $max)
     * @method static \Illuminate\Http\Client\PendingRequest withoutRedirecting()
     * @method static \Illuminate\Http\Client\PendingRequest withoutVerifying()
     * @method static \Illuminate\Http\Client\PendingRequest sink(string|resource $to)
     * @method static \Illuminate\Http\Client\PendingRequest timeout(int|float $seconds)
     * @method static \Illuminate\Http\Client\PendingRequest connectTimeout(int|float $seconds)
     * @method static \Illuminate\Http\Client\PendingRequest retry(array|int $times, \Closure|int $sleepMilliseconds = 0, callable|null $when = null, bool $throw = true)
     * @method static \Illuminate\Http\Client\PendingRequest withOptions(array $options)
     * @method static \Illuminate\Http\Client\PendingRequest withMiddleware(callable $middleware)
     * @method static \Illuminate\Http\Client\PendingRequest withRequestMiddleware(callable $middleware)
     * @method static \Illuminate\Http\Client\PendingRequest withResponseMiddleware(callable $middleware)
     * @method static \Illuminate\Http\Client\PendingRequest beforeSending(callable $callback)
     * @method static \Illuminate\Http\Client\PendingRequest throw(callable|null $callback = null)
     * @method static \Illuminate\Http\Client\PendingRequest throwIf(callable|bool $condition)
     * @method static \Illuminate\Http\Client\PendingRequest throwUnless(callable|bool $condition)
     * @method static \Illuminate\Http\Client\PendingRequest dump()
     * @method static \Illuminate\Http\Client\PendingRequest dd()
     * @method static \Illuminate\Http\Client\Response get(string $url, array|string|null $query = null)
     * @method static \Illuminate\Http\Client\Response head(string $url, array|string|null $query = null)
     * @method static \Illuminate\Http\Client\Response post(string $url, array $data = [])
     * @method static \Illuminate\Http\Client\Response patch(string $url, array $data = [])
     * @method static \Illuminate\Http\Client\Response put(string $url, array $data = [])
     * @method static \Illuminate\Http\Client\Response delete(string $url, array $data = [])
     * @method static array pool(callable $callback)
     * @method static \Illuminate\Http\Client\Response send(string $method, string $url, array $options = [])
     * @method static \GuzzleHttp\Client buildClient()
     * @method static \GuzzleHttp\Client createClient(\GuzzleHttp\HandlerStack $handlerStack)
     * @method static \GuzzleHttp\HandlerStack buildHandlerStack()
     * @method static \GuzzleHttp\HandlerStack pushHandlers(\GuzzleHttp\HandlerStack $handlerStack)
     * @method static \Closure buildBeforeSendingHandler()
     * @method static \Closure buildRecorderHandler()
     * @method static \Closure buildStubHandler()
     * @method static \GuzzleHttp\Psr7\RequestInterface runBeforeSendingCallbacks(\GuzzleHttp\Psr7\RequestInterface $request, array $options)
     * @method static array mergeOptions(array ...$options)
     * @method static \Illuminate\Http\Client\PendingRequest stub(callable $callback)
     * @method static \Illuminate\Http\Client\PendingRequest async(bool $async = true)
     * @method static \GuzzleHttp\Promise\PromiseInterface|null getPromise()
     * @method static \Illuminate\Http\Client\PendingRequest setClient(\GuzzleHttp\Client $client)
     * @method static \Illuminate\Http\Client\PendingRequest setHandler(callable $handler)
     * @method static array getOptions()
     * @method static \Illuminate\Http\Client\PendingRequest|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
     * @method static \Illuminate\Http\Client\PendingRequest|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
     * @see \Illuminate\Http\Client\Factory
     */
    class Http {
        /**
         * Add middleware to apply to every request.
         *
         * @param callable $middleware
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function globalMiddleware($middleware)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->globalMiddleware($middleware);
        }

        /**
         * Add request middleware to apply to every request.
         *
         * @param callable $middleware
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function globalRequestMiddleware($middleware)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->globalRequestMiddleware($middleware);
        }

        /**
         * Add response middleware to apply to every request.
         *
         * @param callable $middleware
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function globalResponseMiddleware($middleware)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->globalResponseMiddleware($middleware);
        }

        /**
         * Set the options to apply to every request.
         *
         * @param \Closure|array $options
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function globalOptions($options)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->globalOptions($options);
        }

        /**
         * Create a new response instance for use during stubbing.
         *
         * @param array|string|null $body
         * @param int $status
         * @param array $headers
         * @return \GuzzleHttp\Promise\PromiseInterface 
         * @static 
         */
        public static function response($body = null, $status = 200, $headers = [])
        {
            return \Illuminate\Http\Client\Factory::response($body, $status, $headers);
        }

        /**
         * Create a new connection exception for use during stubbing.
         *
         * @param string|null $message
         * @return \GuzzleHttp\Promise\PromiseInterface 
         * @static 
         */
        public static function failedConnection($message = null)
        {
            return \Illuminate\Http\Client\Factory::failedConnection($message);
        }

        /**
         * Get an invokable object that returns a sequence of responses in order for use during stubbing.
         *
         * @param array $responses
         * @return \Illuminate\Http\Client\ResponseSequence 
         * @static 
         */
        public static function sequence($responses = [])
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->sequence($responses);
        }

        /**
         * Register a stub callable that will intercept requests and be able to return stub responses.
         *
         * @param callable|array|null $callback
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function fake($callback = null)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->fake($callback);
        }

        /**
         * Register a response sequence for the given URL pattern.
         *
         * @param string $url
         * @return \Illuminate\Http\Client\ResponseSequence 
         * @static 
         */
        public static function fakeSequence($url = '*')
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->fakeSequence($url);
        }

        /**
         * Stub the given URL using the given callback.
         *
         * @param string $url
         * @param \Illuminate\Http\Client\Response|\GuzzleHttp\Promise\PromiseInterface|callable|int|string|array $callback
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function stubUrl($url, $callback)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->stubUrl($url, $callback);
        }

        /**
         * Indicate that an exception should be thrown if any request is not faked.
         *
         * @param bool $prevent
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function preventStrayRequests($prevent = true)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->preventStrayRequests($prevent);
        }

        /**
         * Determine if stray requests are being prevented.
         *
         * @return bool 
         * @static 
         */
        public static function preventingStrayRequests()
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->preventingStrayRequests();
        }

        /**
         * Indicate that an exception should not be thrown if any request is not faked.
         *
         * @return \Illuminate\Http\Client\Factory 
         * @static 
         */
        public static function allowStrayRequests()
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->allowStrayRequests();
        }

        /**
         * Record a request response pair.
         *
         * @param \Illuminate\Http\Client\Request $request
         * @param \Illuminate\Http\Client\Response|null $response
         * @return void 
         * @static 
         */
        public static function recordRequestResponsePair($request, $response)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            $instance->recordRequestResponsePair($request, $response);
        }

        /**
         * Assert that a request / response pair was recorded matching a given truth test.
         *
         * @param callable $callback
         * @return void 
         * @static 
         */
        public static function assertSent($callback)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            $instance->assertSent($callback);
        }

        /**
         * Assert that the given request was sent in the given order.
         *
         * @param array $callbacks
         * @return void 
         * @static 
         */
        public static function assertSentInOrder($callbacks)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            $instance->assertSentInOrder($callbacks);
        }

        /**
         * Assert that a request / response pair was not recorded matching a given truth test.
         *
         * @param callable $callback
         * @return void 
         * @static 
         */
        public static function assertNotSent($callback)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            $instance->assertNotSent($callback);
        }

        /**
         * Assert that no request / response pair was recorded.
         *
         * @return void 
         * @static 
         */
        public static function assertNothingSent()
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            $instance->assertNothingSent();
        }

        /**
         * Assert how many requests have been recorded.
         *
         * @param int $count
         * @return void 
         * @static 
         */
        public static function assertSentCount($count)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            $instance->assertSentCount($count);
        }

        /**
         * Assert that every created response sequence is empty.
         *
         * @return void 
         * @static 
         */
        public static function assertSequencesAreEmpty()
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            $instance->assertSequencesAreEmpty();
        }

        /**
         * Get a collection of the request / response pairs matching the given truth test.
         *
         * @param callable $callback
         * @return \Illuminate\Support\Collection 
         * @static 
         */
        public static function recorded($callback = null)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->recorded($callback);
        }

        /**
         * Create a new pending request instance for this factory.
         *
         * @return \Illuminate\Http\Client\PendingRequest 
         * @static 
         */
        public static function createPendingRequest()
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->createPendingRequest();
        }

        /**
         * Get the current event dispatcher implementation.
         *
         * @return \Illuminate\Contracts\Events\Dispatcher|null 
         * @static 
         */
        public static function getDispatcher()
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->getDispatcher();
        }

        /**
         * Get the array of global middleware.
         *
         * @return array 
         * @static 
         */
        public static function getGlobalMiddleware()
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->getGlobalMiddleware();
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Http\Client\Factory::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Http\Client\Factory::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Http\Client\Factory::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Illuminate\Http\Client\Factory::flushMacros();
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed 
         * @throws \BadMethodCallException
         * @static 
         */
        public static function macroCall($method, $parameters)
        {
            /** @var \Illuminate\Http\Client\Factory $instance */
            return $instance->macroCall($method, $parameters);
        }

            }
    /**
     * 
     *
     * @method static \Illuminate\Routing\RouteRegistrar attribute(string $key, mixed $value)
     * @method static \Illuminate\Routing\RouteRegistrar whereAlpha(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereAlphaNumeric(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereNumber(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereUlid(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereUuid(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereIn(array|string $parameters, array $values)
     * @method static \Illuminate\Routing\RouteRegistrar as(string $value)
     * @method static \Illuminate\Routing\RouteRegistrar can(\UnitEnum|string $ability, array|string $models = [])
     * @method static \Illuminate\Routing\RouteRegistrar controller(string $controller)
     * @method static \Illuminate\Routing\RouteRegistrar domain(\BackedEnum|string $value)
     * @method static \Illuminate\Routing\RouteRegistrar middleware(array|string|null $middleware)
     * @method static \Illuminate\Routing\RouteRegistrar missing(\Closure $missing)
     * @method static \Illuminate\Routing\RouteRegistrar name(\BackedEnum|string $value)
     * @method static \Illuminate\Routing\RouteRegistrar namespace(string|null $value)
     * @method static \Illuminate\Routing\RouteRegistrar prefix(string $prefix)
     * @method static \Illuminate\Routing\RouteRegistrar scopeBindings()
     * @method static \Illuminate\Routing\RouteRegistrar where(array $where)
     * @method static \Illuminate\Routing\RouteRegistrar withoutMiddleware(array|string $middleware)
     * @method static \Illuminate\Routing\RouteRegistrar withoutScopedBindings()
     * @see \Illuminate\Routing\Router
     */
    class Route {
        /**
         * Register a new GET route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function get($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->get($uri, $action);
        }

        /**
         * Register a new POST route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function post($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->post($uri, $action);
        }

        /**
         * Register a new PUT route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function put($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->put($uri, $action);
        }

        /**
         * Register a new PATCH route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function patch($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->patch($uri, $action);
        }

        /**
         * Register a new DELETE route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function delete($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->delete($uri, $action);
        }

        /**
         * Register a new OPTIONS route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function options($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->options($uri, $action);
        }

        /**
         * Register a new route responding to all verbs.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function any($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->any($uri, $action);
        }

        /**
         * Register a new fallback route with the router.
         *
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function fallback($action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->fallback($action);
        }

        /**
         * Create a redirect from one URI to another.
         *
         * @param string $uri
         * @param string $destination
         * @param int $status
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function redirect($uri, $destination, $status = 302)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->redirect($uri, $destination, $status);
        }

        /**
         * Create a permanent redirect from one URI to another.
         *
         * @param string $uri
         * @param string $destination
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function permanentRedirect($uri, $destination)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->permanentRedirect($uri, $destination);
        }

        /**
         * Register a new route that returns a view.
         *
         * @param string $uri
         * @param string $view
         * @param array $data
         * @param int|array $status
         * @param array $headers
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function view($uri, $view, $data = [], $status = 200, $headers = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->view($uri, $view, $data, $status, $headers);
        }

        /**
         * Register a new route with the given verbs.
         *
         * @param array|string $methods
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function match($methods, $uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->match($methods, $uri, $action);
        }

        /**
         * Register an array of resource controllers.
         *
         * @param array $resources
         * @param array $options
         * @return void 
         * @static 
         */
        public static function resources($resources, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->resources($resources, $options);
        }

        /**
         * Route a resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingResourceRegistration 
         * @static 
         */
        public static function resource($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->resource($name, $controller, $options);
        }

        /**
         * Register an array of API resource controllers.
         *
         * @param array $resources
         * @param array $options
         * @return void 
         * @static 
         */
        public static function apiResources($resources, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->apiResources($resources, $options);
        }

        /**
         * Route an API resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingResourceRegistration 
         * @static 
         */
        public static function apiResource($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->apiResource($name, $controller, $options);
        }

        /**
         * Register an array of singleton resource controllers.
         *
         * @param array $singletons
         * @param array $options
         * @return void 
         * @static 
         */
        public static function singletons($singletons, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->singletons($singletons, $options);
        }

        /**
         * Route a singleton resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingSingletonResourceRegistration 
         * @static 
         */
        public static function singleton($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->singleton($name, $controller, $options);
        }

        /**
         * Register an array of API singleton resource controllers.
         *
         * @param array $singletons
         * @param array $options
         * @return void 
         * @static 
         */
        public static function apiSingletons($singletons, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->apiSingletons($singletons, $options);
        }

        /**
         * Route an API singleton resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingSingletonResourceRegistration 
         * @static 
         */
        public static function apiSingleton($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->apiSingleton($name, $controller, $options);
        }

        /**
         * Create a route group with shared attributes.
         *
         * @param array $attributes
         * @param \Closure|array|string $routes
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function group($attributes, $routes)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->group($attributes, $routes);
        }

        /**
         * Merge the given array with the last group stack.
         *
         * @param array $new
         * @param bool $prependExistingPrefix
         * @return array 
         * @static 
         */
        public static function mergeWithLastGroup($new, $prependExistingPrefix = true)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->mergeWithLastGroup($new, $prependExistingPrefix);
        }

        /**
         * Get the prefix from the last group on the stack.
         *
         * @return string 
         * @static 
         */
        public static function getLastGroupPrefix()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getLastGroupPrefix();
        }

        /**
         * Add a route to the underlying route collection.
         *
         * @param array|string $methods
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function addRoute($methods, $uri, $action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->addRoute($methods, $uri, $action);
        }

        /**
         * Create a new Route object.
         *
         * @param array|string $methods
         * @param string $uri
         * @param mixed $action
         * @return \Illuminate\Routing\Route 
         * @static 
         */
        public static function newRoute($methods, $uri, $action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->newRoute($methods, $uri, $action);
        }

        /**
         * Return the response returned by the given route.
         *
         * @param string $name
         * @return \Symfony\Component\HttpFoundation\Response 
         * @static 
         */
        public static function respondWithRoute($name)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->respondWithRoute($name);
        }

        /**
         * Dispatch the request to the application.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Symfony\Component\HttpFoundation\Response 
         * @static 
         */
        public static function dispatch($request)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->dispatch($request);
        }

        /**
         * Dispatch the request to a route and return the response.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Symfony\Component\HttpFoundation\Response 
         * @static 
         */
        public static function dispatchToRoute($request)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->dispatchToRoute($request);
        }

        /**
         * Gather the middleware for the given route with resolved class names.
         *
         * @param \Illuminate\Routing\Route $route
         * @return array 
         * @static 
         */
        public static function gatherRouteMiddleware($route)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->gatherRouteMiddleware($route);
        }

        /**
         * Resolve a flat array of middleware classes from the provided array.
         *
         * @param array $middleware
         * @param array $excluded
         * @return array 
         * @static 
         */
        public static function resolveMiddleware($middleware, $excluded = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->resolveMiddleware($middleware, $excluded);
        }

        /**
         * Create a response instance from the given value.
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @param mixed $response
         * @return \Symfony\Component\HttpFoundation\Response 
         * @static 
         */
        public static function prepareResponse($request, $response)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->prepareResponse($request, $response);
        }

        /**
         * Static version of prepareResponse.
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @param mixed $response
         * @return \Symfony\Component\HttpFoundation\Response 
         * @static 
         */
        public static function toResponse($request, $response)
        {
            return \Illuminate\Routing\Router::toResponse($request, $response);
        }

        /**
         * Substitute the route bindings onto the route.
         *
         * @param \Illuminate\Routing\Route $route
         * @return \Illuminate\Routing\Route 
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
         * @throws \Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException
         * @static 
         */
        public static function substituteBindings($route)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->substituteBindings($route);
        }

        /**
         * Substitute the implicit route bindings for the given route.
         *
         * @param \Illuminate\Routing\Route $route
         * @return void 
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
         * @throws \Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException
         * @static 
         */
        public static function substituteImplicitBindings($route)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->substituteImplicitBindings($route);
        }

        /**
         * Register a callback to run after implicit bindings are substituted.
         *
         * @param callable $callback
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function substituteImplicitBindingsUsing($callback)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->substituteImplicitBindingsUsing($callback);
        }

        /**
         * Register a route matched event listener.
         *
         * @param string|callable $callback
         * @return void 
         * @static 
         */
        public static function matched($callback)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->matched($callback);
        }

        /**
         * Get all of the defined middleware short-hand names.
         *
         * @return array 
         * @static 
         */
        public static function getMiddleware()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getMiddleware();
        }

        /**
         * Register a short-hand name for a middleware.
         *
         * @param string $name
         * @param string $class
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function aliasMiddleware($name, $class)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->aliasMiddleware($name, $class);
        }

        /**
         * Check if a middlewareGroup with the given name exists.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMiddlewareGroup($name)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->hasMiddlewareGroup($name);
        }

        /**
         * Get all of the defined middleware groups.
         *
         * @return array 
         * @static 
         */
        public static function getMiddlewareGroups()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getMiddlewareGroups();
        }

        /**
         * Register a group of middleware.
         *
         * @param string $name
         * @param array $middleware
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function middlewareGroup($name, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->middlewareGroup($name, $middleware);
        }

        /**
         * Add a middleware to the beginning of a middleware group.
         * 
         * If the middleware is already in the group, it will not be added again.
         *
         * @param string $group
         * @param string $middleware
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function prependMiddlewareToGroup($group, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->prependMiddlewareToGroup($group, $middleware);
        }

        /**
         * Add a middleware to the end of a middleware group.
         * 
         * If the middleware is already in the group, it will not be added again.
         *
         * @param string $group
         * @param string $middleware
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function pushMiddlewareToGroup($group, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->pushMiddlewareToGroup($group, $middleware);
        }

        /**
         * Remove the given middleware from the specified group.
         *
         * @param string $group
         * @param string $middleware
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function removeMiddlewareFromGroup($group, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->removeMiddlewareFromGroup($group, $middleware);
        }

        /**
         * Flush the router's middleware groups.
         *
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function flushMiddlewareGroups()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->flushMiddlewareGroups();
        }

        /**
         * Add a new route parameter binder.
         *
         * @param string $key
         * @param string|callable $binder
         * @return void 
         * @static 
         */
        public static function bind($key, $binder)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->bind($key, $binder);
        }

        /**
         * Register a model binder for a wildcard.
         *
         * @param string $key
         * @param string $class
         * @param \Closure|null $callback
         * @return void 
         * @static 
         */
        public static function model($key, $class, $callback = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->model($key, $class, $callback);
        }

        /**
         * Get the binding callback for a given binding.
         *
         * @param string $key
         * @return \Closure|null 
         * @static 
         */
        public static function getBindingCallback($key)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getBindingCallback($key);
        }

        /**
         * Get the global "where" patterns.
         *
         * @return array 
         * @static 
         */
        public static function getPatterns()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getPatterns();
        }

        /**
         * Set a global where pattern on all routes.
         *
         * @param string $key
         * @param string $pattern
         * @return void 
         * @static 
         */
        public static function pattern($key, $pattern)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->pattern($key, $pattern);
        }

        /**
         * Set a group of global where patterns on all routes.
         *
         * @param array $patterns
         * @return void 
         * @static 
         */
        public static function patterns($patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->patterns($patterns);
        }

        /**
         * Determine if the router currently has a group stack.
         *
         * @return bool 
         * @static 
         */
        public static function hasGroupStack()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->hasGroupStack();
        }

        /**
         * Get the current group stack for the router.
         *
         * @return array 
         * @static 
         */
        public static function getGroupStack()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getGroupStack();
        }

        /**
         * Get a route parameter for the current route.
         *
         * @param string $key
         * @param string|null $default
         * @return mixed 
         * @static 
         */
        public static function input($key, $default = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->input($key, $default);
        }

        /**
         * Get the request currently being dispatched.
         *
         * @return \Illuminate\Http\Request 
         * @static 
         */
        public static function getCurrentRequest()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getCurrentRequest();
        }

        /**
         * Get the currently dispatched route instance.
         *
         * @return \Illuminate\Routing\Route|null 
         * @static 
         */
        public static function getCurrentRoute()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getCurrentRoute();
        }

        /**
         * Get the currently dispatched route instance.
         *
         * @return \Illuminate\Routing\Route|null 
         * @static 
         */
        public static function current()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->current();
        }

        /**
         * Check if a route with the given name exists.
         *
         * @param string|array $name
         * @return bool 
         * @static 
         */
        public static function has($name)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->has($name);
        }

        /**
         * Get the current route name.
         *
         * @return string|null 
         * @static 
         */
        public static function currentRouteName()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteName();
        }

        /**
         * Alias for the "currentRouteNamed" method.
         *
         * @param mixed $patterns
         * @return bool 
         * @static 
         */
        public static function is(...$patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->is(...$patterns);
        }

        /**
         * Determine if the current route matches a pattern.
         *
         * @param mixed $patterns
         * @return bool 
         * @static 
         */
        public static function currentRouteNamed(...$patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteNamed(...$patterns);
        }

        /**
         * Get the current route action.
         *
         * @return string|null 
         * @static 
         */
        public static function currentRouteAction()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteAction();
        }

        /**
         * Alias for the "currentRouteUses" method.
         *
         * @param array|string $patterns
         * @return bool 
         * @static 
         */
        public static function uses(...$patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->uses(...$patterns);
        }

        /**
         * Determine if the current route action matches a given action.
         *
         * @param string $action
         * @return bool 
         * @static 
         */
        public static function currentRouteUses($action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteUses($action);
        }

        /**
         * Set the unmapped global resource parameters to singular.
         *
         * @param bool $singular
         * @return void 
         * @static 
         */
        public static function singularResourceParameters($singular = true)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->singularResourceParameters($singular);
        }

        /**
         * Set the global resource parameter mapping.
         *
         * @param array $parameters
         * @return void 
         * @static 
         */
        public static function resourceParameters($parameters = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->resourceParameters($parameters);
        }

        /**
         * Get or set the verbs used in the resource URIs.
         *
         * @param array $verbs
         * @return array|null 
         * @static 
         */
        public static function resourceVerbs($verbs = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->resourceVerbs($verbs);
        }

        /**
         * Get the underlying route collection.
         *
         * @return \Illuminate\Routing\RouteCollectionInterface 
         * @static 
         */
        public static function getRoutes()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getRoutes();
        }

        /**
         * Set the route collection instance.
         *
         * @param \Illuminate\Routing\RouteCollection $routes
         * @return void 
         * @static 
         */
        public static function setRoutes($routes)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->setRoutes($routes);
        }

        /**
         * Set the compiled route collection instance.
         *
         * @param array $routes
         * @return void 
         * @static 
         */
        public static function setCompiledRoutes($routes)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->setCompiledRoutes($routes);
        }

        /**
         * Remove any duplicate middleware from the given array.
         *
         * @param array $middleware
         * @return array 
         * @static 
         */
        public static function uniqueMiddleware($middleware)
        {
            return \Illuminate\Routing\Router::uniqueMiddleware($middleware);
        }

        /**
         * Set the container instance used by the router.
         *
         * @param \Illuminate\Container\Container $container
         * @return \Illuminate\Routing\Router 
         * @static 
         */
        public static function setContainer($container)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->setContainer($container);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Routing\Router::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Routing\Router::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Routing\Router::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Illuminate\Routing\Router::flushMacros();
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed 
         * @throws \BadMethodCallException
         * @static 
         */
        public static function macroCall($method, $parameters)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->macroCall($method, $parameters);
        }

        /**
         * Call the given Closure with this instance then return the instance.
         *
         * @param (callable($this): mixed)|null $callback
         * @return ($callback is null ? \Illuminate\Support\HigherOrderTapProxy : $this)
         * @static 
         */
        public static function tap($callback = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->tap($callback);
        }

            }
    /**
     * 
     *
     * @see \Illuminate\Routing\UrlGenerator
     */
    class URL {
        /**
         * Get the full URL for the current request.
         *
         * @return string 
         * @static 
         */
        public static function full()
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->full();
        }

        /**
         * Get the current URL for the request.
         *
         * @return string 
         * @static 
         */
        public static function current()
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->current();
        }

        /**
         * Get the URL for the previous request.
         *
         * @param mixed $fallback
         * @return string 
         * @static 
         */
        public static function previous($fallback = false)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->previous($fallback);
        }

        /**
         * Get the previous path info for the request.
         *
         * @param mixed $fallback
         * @return string 
         * @static 
         */
        public static function previousPath($fallback = false)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->previousPath($fallback);
        }

        /**
         * Generate an absolute URL to the given path.
         *
         * @param string $path
         * @param mixed $extra
         * @param bool|null $secure
         * @return string 
         * @static 
         */
        public static function to($path, $extra = [], $secure = null)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->to($path, $extra, $secure);
        }

        /**
         * Generate an absolute URL with the given query parameters.
         *
         * @param string $path
         * @param array $query
         * @param mixed $extra
         * @param bool|null $secure
         * @return string 
         * @static 
         */
        public static function query($path, $query = [], $extra = [], $secure = null)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->query($path, $query, $extra, $secure);
        }

        /**
         * Generate a secure, absolute URL to the given path.
         *
         * @param string $path
         * @param array $parameters
         * @return string 
         * @static 
         */
        public static function secure($path, $parameters = [])
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->secure($path, $parameters);
        }

        /**
         * Generate the URL to an application asset.
         *
         * @param string $path
         * @param bool|null $secure
         * @return string 
         * @static 
         */
        public static function asset($path, $secure = null)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->asset($path, $secure);
        }

        /**
         * Generate the URL to a secure asset.
         *
         * @param string $path
         * @return string 
         * @static 
         */
        public static function secureAsset($path)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->secureAsset($path);
        }

        /**
         * Generate the URL to an asset from a custom root domain such as CDN, etc.
         *
         * @param string $root
         * @param string $path
         * @param bool|null $secure
         * @return string 
         * @static 
         */
        public static function assetFrom($root, $path, $secure = null)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->assetFrom($root, $path, $secure);
        }

        /**
         * Get the default scheme for a raw URL.
         *
         * @param bool|null $secure
         * @return string 
         * @static 
         */
        public static function formatScheme($secure = null)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->formatScheme($secure);
        }

        /**
         * Create a signed route URL for a named route.
         *
         * @param \BackedEnum|string $name
         * @param mixed $parameters
         * @param \DateTimeInterface|\DateInterval|int|null $expiration
         * @param bool $absolute
         * @return string 
         * @throws \InvalidArgumentException
         * @static 
         */
        public static function signedRoute($name, $parameters = [], $expiration = null, $absolute = true)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->signedRoute($name, $parameters, $expiration, $absolute);
        }

        /**
         * Create a temporary signed route URL for a named route.
         *
         * @param \BackedEnum|string $name
         * @param \DateTimeInterface|\DateInterval|int $expiration
         * @param array $parameters
         * @param bool $absolute
         * @return string 
         * @static 
         */
        public static function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->temporarySignedRoute($name, $expiration, $parameters, $absolute);
        }

        /**
         * Determine if the given request has a valid signature.
         *
         * @param \Illuminate\Http\Request $request
         * @param bool $absolute
         * @param array $ignoreQuery
         * @return bool 
         * @static 
         */
        public static function hasValidSignature($request, $absolute = true, $ignoreQuery = [])
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->hasValidSignature($request, $absolute, $ignoreQuery);
        }

        /**
         * Determine if the given request has a valid signature for a relative URL.
         *
         * @param \Illuminate\Http\Request $request
         * @param array $ignoreQuery
         * @return bool 
         * @static 
         */
        public static function hasValidRelativeSignature($request, $ignoreQuery = [])
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->hasValidRelativeSignature($request, $ignoreQuery);
        }

        /**
         * Determine if the signature from the given request matches the URL.
         *
         * @param \Illuminate\Http\Request $request
         * @param bool $absolute
         * @param array $ignoreQuery
         * @return bool 
         * @static 
         */
        public static function hasCorrectSignature($request, $absolute = true, $ignoreQuery = [])
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->hasCorrectSignature($request, $absolute, $ignoreQuery);
        }

        /**
         * Determine if the expires timestamp from the given request is not from the past.
         *
         * @param \Illuminate\Http\Request $request
         * @return bool 
         * @static 
         */
        public static function signatureHasNotExpired($request)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->signatureHasNotExpired($request);
        }

        /**
         * Get the URL to a named route.
         *
         * @param \BackedEnum|string $name
         * @param mixed $parameters
         * @param bool $absolute
         * @return string 
         * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException|\InvalidArgumentException
         * @static 
         */
        public static function route($name, $parameters = [], $absolute = true)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->route($name, $parameters, $absolute);
        }

        /**
         * Get the URL for a given route instance.
         *
         * @param \Illuminate\Routing\Route $route
         * @param mixed $parameters
         * @param bool $absolute
         * @return string 
         * @throws \Illuminate\Routing\Exceptions\UrlGenerationException
         * @static 
         */
        public static function toRoute($route, $parameters, $absolute)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->toRoute($route, $parameters, $absolute);
        }

        /**
         * Get the URL to a controller action.
         *
         * @param string|array $action
         * @param mixed $parameters
         * @param bool $absolute
         * @return string 
         * @throws \InvalidArgumentException
         * @static 
         */
        public static function action($action, $parameters = [], $absolute = true)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->action($action, $parameters, $absolute);
        }

        /**
         * Format the array of URL parameters.
         *
         * @param mixed $parameters
         * @return array 
         * @static 
         */
        public static function formatParameters($parameters)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->formatParameters($parameters);
        }

        /**
         * Get the base URL for the request.
         *
         * @param string $scheme
         * @param string|null $root
         * @return string 
         * @static 
         */
        public static function formatRoot($scheme, $root = null)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->formatRoot($scheme, $root);
        }

        /**
         * Format the given URL segments into a single URL.
         *
         * @param string $root
         * @param string $path
         * @param \Illuminate\Routing\Route|null $route
         * @return string 
         * @static 
         */
        public static function format($root, $path, $route = null)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->format($root, $path, $route);
        }

        /**
         * Determine if the given path is a valid URL.
         *
         * @param string $path
         * @return bool 
         * @static 
         */
        public static function isValidUrl($path)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->isValidUrl($path);
        }

        /**
         * Set the default named parameters used by the URL generator.
         *
         * @param array $defaults
         * @return void 
         * @static 
         */
        public static function defaults($defaults)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            $instance->defaults($defaults);
        }

        /**
         * Get the default named parameters used by the URL generator.
         *
         * @return array 
         * @static 
         */
        public static function getDefaultParameters()
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->getDefaultParameters();
        }

        /**
         * Force the scheme for URLs.
         *
         * @param string|null $scheme
         * @return void 
         * @static 
         */
        public static function forceScheme($scheme)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            $instance->forceScheme($scheme);
        }

        /**
         * Force the use of the HTTPS scheme for all generated URLs.
         *
         * @param bool $force
         * @return void 
         * @static 
         */
        public static function forceHttps($force = true)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            $instance->forceHttps($force);
        }

        /**
         * Set the URL origin for all generated URLs.
         *
         * @param string|null $root
         * @return void 
         * @static 
         */
        public static function useOrigin($root)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            $instance->useOrigin($root);
        }

        /**
         * Set the forced root URL.
         *
         * @param string|null $root
         * @return void 
         * @deprecated Use useOrigin
         * @static 
         */
        public static function forceRootUrl($root)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            $instance->forceRootUrl($root);
        }

        /**
         * Set the URL origin for all generated asset URLs.
         *
         * @param string|null $root
         * @return void 
         * @static 
         */
        public static function useAssetOrigin($root)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            $instance->useAssetOrigin($root);
        }

        /**
         * Set a callback to be used to format the host of generated URLs.
         *
         * @param \Closure $callback
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function formatHostUsing($callback)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->formatHostUsing($callback);
        }

        /**
         * Set a callback to be used to format the path of generated URLs.
         *
         * @param \Closure $callback
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function formatPathUsing($callback)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->formatPathUsing($callback);
        }

        /**
         * Get the path formatter being used by the URL generator.
         *
         * @return \Closure 
         * @static 
         */
        public static function pathFormatter()
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->pathFormatter();
        }

        /**
         * Get the request instance.
         *
         * @return \Illuminate\Http\Request 
         * @static 
         */
        public static function getRequest()
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->getRequest();
        }

        /**
         * Set the current request instance.
         *
         * @param \Illuminate\Http\Request $request
         * @return void 
         * @static 
         */
        public static function setRequest($request)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            $instance->setRequest($request);
        }

        /**
         * Set the route collection.
         *
         * @param \Illuminate\Routing\RouteCollectionInterface $routes
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function setRoutes($routes)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->setRoutes($routes);
        }

        /**
         * Set the session resolver for the generator.
         *
         * @param callable $sessionResolver
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function setSessionResolver($sessionResolver)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->setSessionResolver($sessionResolver);
        }

        /**
         * Set the encryption key resolver.
         *
         * @param callable $keyResolver
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function setKeyResolver($keyResolver)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->setKeyResolver($keyResolver);
        }

        /**
         * Clone a new instance of the URL generator with a different encryption key resolver.
         *
         * @param callable $keyResolver
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function withKeyResolver($keyResolver)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->withKeyResolver($keyResolver);
        }

        /**
         * Set the callback that should be used to attempt to resolve missing named routes.
         *
         * @param callable $missingNamedRouteResolver
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function resolveMissingNamedRoutesUsing($missingNamedRouteResolver)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->resolveMissingNamedRoutesUsing($missingNamedRouteResolver);
        }

        /**
         * Get the root controller namespace.
         *
         * @return string 
         * @static 
         */
        public static function getRootControllerNamespace()
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->getRootControllerNamespace();
        }

        /**
         * Set the root controller namespace.
         *
         * @param string $rootNamespace
         * @return \Illuminate\Routing\UrlGenerator 
         * @static 
         */
        public static function setRootControllerNamespace($rootNamespace)
        {
            /** @var \Illuminate\Routing\UrlGenerator $instance */
            return $instance->setRootControllerNamespace($rootNamespace);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Routing\UrlGenerator::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Routing\UrlGenerator::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Routing\UrlGenerator::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Illuminate\Routing\UrlGenerator::flushMacros();
        }

            }
    /**
     * 
     *
     * @see \Illuminate\Validation\Factory
     */
    class Validator {
        /**
         * Create a new Validator instance.
         *
         * @param array $data
         * @param array $rules
         * @param array $messages
         * @param array $attributes
         * @return \Illuminate\Validation\Validator 
         * @static 
         */
        public static function make($data, $rules, $messages = [], $attributes = [])
        {
            /** @var \Illuminate\Validation\Factory $instance */
            return $instance->make($data, $rules, $messages, $attributes);
        }

        /**
         * Validate the given data against the provided rules.
         *
         * @param array $data
         * @param array $rules
         * @param array $messages
         * @param array $attributes
         * @return array 
         * @throws \Illuminate\Validation\ValidationException
         * @static 
         */
        public static function validate($data, $rules, $messages = [], $attributes = [])
        {
            /** @var \Illuminate\Validation\Factory $instance */
            return $instance->validate($data, $rules, $messages, $attributes);
        }

        /**
         * Register a custom validator extension.
         *
         * @param string $rule
         * @param \Closure|string $extension
         * @param string|null $message
         * @return void 
         * @static 
         */
        public static function extend($rule, $extension, $message = null)
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->extend($rule, $extension, $message);
        }

        /**
         * Register a custom implicit validator extension.
         *
         * @param string $rule
         * @param \Closure|string $extension
         * @param string|null $message
         * @return void 
         * @static 
         */
        public static function extendImplicit($rule, $extension, $message = null)
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->extendImplicit($rule, $extension, $message);
        }

        /**
         * Register a custom dependent validator extension.
         *
         * @param string $rule
         * @param \Closure|string $extension
         * @param string|null $message
         * @return void 
         * @static 
         */
        public static function extendDependent($rule, $extension, $message = null)
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->extendDependent($rule, $extension, $message);
        }

        /**
         * Register a custom validator message replacer.
         *
         * @param string $rule
         * @param \Closure|string $replacer
         * @return void 
         * @static 
         */
        public static function replacer($rule, $replacer)
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->replacer($rule, $replacer);
        }

        /**
         * Indicate that unvalidated array keys should be included in validated data when the parent array is validated.
         *
         * @return void 
         * @static 
         */
        public static function includeUnvalidatedArrayKeys()
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->includeUnvalidatedArrayKeys();
        }

        /**
         * Indicate that unvalidated array keys should be excluded from the validated data, even if the parent array was validated.
         *
         * @return void 
         * @static 
         */
        public static function excludeUnvalidatedArrayKeys()
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->excludeUnvalidatedArrayKeys();
        }

        /**
         * Set the Validator instance resolver.
         *
         * @param \Closure $resolver
         * @return void 
         * @static 
         */
        public static function resolver($resolver)
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->resolver($resolver);
        }

        /**
         * Get the Translator implementation.
         *
         * @return \Illuminate\Contracts\Translation\Translator 
         * @static 
         */
        public static function getTranslator()
        {
            /** @var \Illuminate\Validation\Factory $instance */
            return $instance->getTranslator();
        }

        /**
         * Get the Presence Verifier implementation.
         *
         * @return \Illuminate\Validation\PresenceVerifierInterface 
         * @static 
         */
        public static function getPresenceVerifier()
        {
            /** @var \Illuminate\Validation\Factory $instance */
            return $instance->getPresenceVerifier();
        }

        /**
         * Set the Presence Verifier implementation.
         *
         * @param \Illuminate\Validation\PresenceVerifierInterface $presenceVerifier
         * @return void 
         * @static 
         */
        public static function setPresenceVerifier($presenceVerifier)
        {
            /** @var \Illuminate\Validation\Factory $instance */
            $instance->setPresenceVerifier($presenceVerifier);
        }

        /**
         * Get the container instance used by the validation factory.
         *
         * @return \Illuminate\Contracts\Container\Container|null 
         * @static 
         */
        public static function getContainer()
        {
            /** @var \Illuminate\Validation\Factory $instance */
            return $instance->getContainer();
        }

        /**
         * Set the container instance used by the validation factory.
         *
         * @param \Illuminate\Contracts\Container\Container $container
         * @return \Illuminate\Validation\Factory 
         * @static 
         */
        public static function setContainer($container)
        {
            /** @var \Illuminate\Validation\Factory $instance */
            return $instance->setContainer($container);
        }

            }
    }

namespace Tymon\JWTAuth\Facades {
    /**
     * 
     *
     */
    class JWTAuth {
        /**
         * Attempt to authenticate the user and return the token.
         *
         * @param array $credentials
         * @return false|string 
         * @static 
         */
        public static function attempt($credentials)
        {
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->attempt($credentials);
        }

        /**
         * Authenticate a user via a token.
         *
         * @return \Tymon\JWTAuth\Contracts\JWTSubject|false 
         * @static 
         */
        public static function authenticate()
        {
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->authenticate();
        }

        /**
         * Alias for authenticate().
         *
         * @return \Tymon\JWTAuth\Contracts\JWTSubject|false 
         * @static 
         */
        public static function toUser()
        {
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->toUser();
        }

        /**
         * Get the authenticated user.
         *
         * @return \Tymon\JWTAuth\Contracts\JWTSubject 
         * @static 
         */
        public static function user()
        {
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->user();
        }

        /**
         * Generate a token for a given subject.
         *
         * @param \Tymon\JWTAuth\Contracts\JWTSubject $subject
         * @return string 
         * @static 
         */
        public static function fromSubject($subject)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->fromSubject($subject);
        }

        /**
         * Alias to generate a token for a given user.
         *
         * @param \Tymon\JWTAuth\Contracts\JWTSubject $user
         * @return string 
         * @static 
         */
        public static function fromUser($user)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->fromUser($user);
        }

        /**
         * Refresh an expired token.
         *
         * @param bool $forceForever
         * @param bool $resetClaims
         * @return string 
         * @static 
         */
        public static function refresh($forceForever = false, $resetClaims = false)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->refresh($forceForever, $resetClaims);
        }

        /**
         * Invalidate a token (add it to the blacklist).
         *
         * @param bool $forceForever
         * @return \Tymon\JWTAuth\JWTAuth 
         * @static 
         */
        public static function invalidate($forceForever = false)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->invalidate($forceForever);
        }

        /**
         * Alias to get the payload, and as a result checks that
         * the token is valid i.e. not expired or blacklisted.
         *
         * @return \Tymon\JWTAuth\Payload 
         * @throws \Tymon\JWTAuth\Exceptions\JWTException
         * @static 
         */
        public static function checkOrFail()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->checkOrFail();
        }

        /**
         * Check that the token is valid.
         *
         * @param bool $getPayload
         * @return \Tymon\JWTAuth\Payload|bool 
         * @static 
         */
        public static function check($getPayload = false)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->check($getPayload);
        }

        /**
         * Get the token.
         *
         * @return \Tymon\JWTAuth\Token|null 
         * @static 
         */
        public static function getToken()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->getToken();
        }

        /**
         * Parse the token from the request.
         *
         * @return \Tymon\JWTAuth\JWTAuth 
         * @throws \Tymon\JWTAuth\Exceptions\JWTException
         * @static 
         */
        public static function parseToken()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->parseToken();
        }

        /**
         * Get the raw Payload instance.
         *
         * @return \Tymon\JWTAuth\Payload 
         * @static 
         */
        public static function getPayload()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->getPayload();
        }

        /**
         * Alias for getPayload().
         *
         * @return \Tymon\JWTAuth\Payload 
         * @static 
         */
        public static function payload()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->payload();
        }

        /**
         * Convenience method to get a claim value.
         *
         * @param string $claim
         * @return mixed 
         * @static 
         */
        public static function getClaim($claim)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->getClaim($claim);
        }

        /**
         * Create a Payload instance.
         *
         * @param \Tymon\JWTAuth\Contracts\JWTSubject $subject
         * @return \Tymon\JWTAuth\Payload 
         * @static 
         */
        public static function makePayload($subject)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->makePayload($subject);
        }

        /**
         * Check if the subject model matches the one saved in the token.
         *
         * @param string|object $model
         * @return bool 
         * @static 
         */
        public static function checkSubjectModel($model)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->checkSubjectModel($model);
        }

        /**
         * Set the token.
         *
         * @param \Tymon\JWTAuth\Token|string $token
         * @return \Tymon\JWTAuth\JWTAuth 
         * @static 
         */
        public static function setToken($token)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->setToken($token);
        }

        /**
         * Unset the current token.
         *
         * @return \Tymon\JWTAuth\JWTAuth 
         * @static 
         */
        public static function unsetToken()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->unsetToken();
        }

        /**
         * Set the request instance.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Tymon\JWTAuth\JWTAuth 
         * @static 
         */
        public static function setRequest($request)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->setRequest($request);
        }

        /**
         * Set whether the subject should be "locked".
         *
         * @param bool $lock
         * @return \Tymon\JWTAuth\JWTAuth 
         * @static 
         */
        public static function lockSubject($lock)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->lockSubject($lock);
        }

        /**
         * Get the Manager instance.
         *
         * @return \Tymon\JWTAuth\Manager 
         * @static 
         */
        public static function manager()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->manager();
        }

        /**
         * Get the Parser instance.
         *
         * @return \Tymon\JWTAuth\Http\Parser\Parser 
         * @static 
         */
        public static function parser()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->parser();
        }

        /**
         * Get the Payload Factory.
         *
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function factory()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->factory();
        }

        /**
         * Get the Blacklist.
         *
         * @return \Tymon\JWTAuth\Blacklist 
         * @static 
         */
        public static function blacklist()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->blacklist();
        }

        /**
         * Set the custom claims.
         *
         * @param array $customClaims
         * @return \Tymon\JWTAuth\JWTAuth 
         * @static 
         */
        public static function customClaims($customClaims)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->customClaims($customClaims);
        }

        /**
         * Alias to set the custom claims.
         *
         * @param array $customClaims
         * @return \Tymon\JWTAuth\JWTAuth 
         * @static 
         */
        public static function claims($customClaims)
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->claims($customClaims);
        }

        /**
         * Get the custom claims.
         *
         * @return array 
         * @static 
         */
        public static function getCustomClaims()
        {
            //Method inherited from \Tymon\JWTAuth\JWT 
            /** @var \Tymon\JWTAuth\JWTAuth $instance */
            return $instance->getCustomClaims();
        }

            }
    /**
     * 
     *
     */
    class JWTFactory {
        /**
         * Create the Payload instance.
         *
         * @param bool $resetClaims
         * @return \Tymon\JWTAuth\Payload 
         * @static 
         */
        public static function make($resetClaims = false)
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->make($resetClaims);
        }

        /**
         * Empty the claims collection.
         *
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function emptyClaims()
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->emptyClaims();
        }

        /**
         * Build and get the Claims Collection.
         *
         * @return \Tymon\JWTAuth\Claims\Collection 
         * @static 
         */
        public static function buildClaimsCollection()
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->buildClaimsCollection();
        }

        /**
         * Get a Payload instance with a claims collection.
         *
         * @param \Tymon\JWTAuth\Claims\Collection $claims
         * @return \Tymon\JWTAuth\Payload 
         * @static 
         */
        public static function withClaims($claims)
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->withClaims($claims);
        }

        /**
         * Set the default claims to be added to the Payload.
         *
         * @param array $claims
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function setDefaultClaims($claims)
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->setDefaultClaims($claims);
        }

        /**
         * Helper to set the ttl.
         *
         * @param int $ttl
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function setTTL($ttl)
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->setTTL($ttl);
        }

        /**
         * Helper to get the ttl.
         *
         * @return int 
         * @static 
         */
        public static function getTTL()
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->getTTL();
        }

        /**
         * Get the default claims.
         *
         * @return array 
         * @static 
         */
        public static function getDefaultClaims()
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->getDefaultClaims();
        }

        /**
         * Get the PayloadValidator instance.
         *
         * @return \Tymon\JWTAuth\Validators\PayloadValidator 
         * @static 
         */
        public static function validator()
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->validator();
        }

        /**
         * Set the custom claims.
         *
         * @param array $customClaims
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function customClaims($customClaims)
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->customClaims($customClaims);
        }

        /**
         * Alias to set the custom claims.
         *
         * @param array $customClaims
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function claims($customClaims)
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->claims($customClaims);
        }

        /**
         * Get the custom claims.
         *
         * @return array 
         * @static 
         */
        public static function getCustomClaims()
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->getCustomClaims();
        }

        /**
         * Set the refresh flow flag.
         *
         * @param bool $refreshFlow
         * @return \Tymon\JWTAuth\Factory 
         * @static 
         */
        public static function setRefreshFlow($refreshFlow = true)
        {
            /** @var \Tymon\JWTAuth\Factory $instance */
            return $instance->setRefreshFlow($refreshFlow);
        }

            }
    }

namespace Maatwebsite\Excel\Facades {
    /**
     * 
     *
     */
    class Excel {
        /**
         * 
         *
         * @param object $export
         * @param string|null $fileName
         * @param string $writerType
         * @param array $headers
         * @return \Symfony\Component\HttpFoundation\BinaryFileResponse 
         * @throws \PhpOffice\PhpSpreadsheet\Exception
         * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @static 
         */
        public static function download($export, $fileName, $writerType = null, $headers = [])
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->download($export, $fileName, $writerType, $headers);
        }

        /**
         * 
         *
         * @param string|null $disk Fallback for usage with named properties
         * @param object $export
         * @param string $filePath
         * @param string|null $diskName
         * @param string $writerType
         * @param mixed $diskOptions
         * @return bool 
         * @throws \PhpOffice\PhpSpreadsheet\Exception
         * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @static 
         */
        public static function store($export, $filePath, $diskName = null, $writerType = null, $diskOptions = [], $disk = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->store($export, $filePath, $diskName, $writerType, $diskOptions, $disk);
        }

        /**
         * 
         *
         * @param object $export
         * @param string $filePath
         * @param string|null $disk
         * @param string $writerType
         * @param mixed $diskOptions
         * @return \Illuminate\Foundation\Bus\PendingDispatch 
         * @static 
         */
        public static function queue($export, $filePath, $disk = null, $writerType = null, $diskOptions = [])
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->queue($export, $filePath, $disk, $writerType, $diskOptions);
        }

        /**
         * 
         *
         * @param object $export
         * @param string $writerType
         * @return string 
         * @static 
         */
        public static function raw($export, $writerType)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->raw($export, $writerType);
        }

        /**
         * 
         *
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return \Maatwebsite\Excel\Reader|\Illuminate\Foundation\Bus\PendingDispatch 
         * @static 
         */
        public static function import($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->import($import, $filePath, $disk, $readerType);
        }

        /**
         * 
         *
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return array 
         * @static 
         */
        public static function toArray($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->toArray($import, $filePath, $disk, $readerType);
        }

        /**
         * 
         *
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return \Illuminate\Support\Collection 
         * @static 
         */
        public static function toCollection($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->toCollection($import, $filePath, $disk, $readerType);
        }

        /**
         * 
         *
         * @param \Illuminate\Contracts\Queue\ShouldQueue $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string $readerType
         * @return \Illuminate\Foundation\Bus\PendingDispatch 
         * @static 
         */
        public static function queueImport($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->queueImport($import, $filePath, $disk, $readerType);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void 
         * @static 
         */
        public static function macro($name, $macro)
        {
            \Maatwebsite\Excel\Excel::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void 
         * @throws \ReflectionException
         * @static 
         */
        public static function mixin($mixin, $replace = true)
        {
            \Maatwebsite\Excel\Excel::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool 
         * @static 
         */
        public static function hasMacro($name)
        {
            return \Maatwebsite\Excel\Excel::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void 
         * @static 
         */
        public static function flushMacros()
        {
            \Maatwebsite\Excel\Excel::flushMacros();
        }

        /**
         * 
         *
         * @param string $concern
         * @param callable $handler
         * @param string $event
         * @static 
         */
        public static function extend($concern, $handler, $event = 'Maatwebsite\\Excel\\Events\\BeforeWriting')
        {
            return \Maatwebsite\Excel\Excel::extend($concern, $handler, $event);
        }

        /**
         * When asserting downloaded, stored, queued or imported, use regular expression
         * to look for a matching file path.
         *
         * @return void 
         * @static 
         */
        public static function matchByRegex()
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            $instance->matchByRegex();
        }

        /**
         * When asserting downloaded, stored, queued or imported, use regular string
         * comparison for matching file path.
         *
         * @return void 
         * @static 
         */
        public static function doNotMatchByRegex()
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            $instance->doNotMatchByRegex();
        }

        /**
         * 
         *
         * @param string $fileName
         * @param callable|null $callback
         * @static 
         */
        public static function assertDownloaded($fileName, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertDownloaded($fileName, $callback);
        }

        /**
         * 
         *
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static 
         */
        public static function assertStored($filePath, $disk = null, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertStored($filePath, $disk, $callback);
        }

        /**
         * 
         *
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static 
         */
        public static function assertQueued($filePath, $disk = null, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertQueued($filePath, $disk, $callback);
        }

        /**
         * 
         *
         * @static 
         */
        public static function assertQueuedWithChain($chain)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertQueuedWithChain($chain);
        }

        /**
         * 
         *
         * @param string $classname
         * @param callable|null $callback
         * @static 
         */
        public static function assertExportedInRaw($classname, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertExportedInRaw($classname, $callback);
        }

        /**
         * 
         *
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static 
         */
        public static function assertImported($filePath, $disk = null, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertImported($filePath, $disk, $callback);
        }

            }
    }

namespace niklasravnsborg\LaravelPdf\Facades {
    /**
     * 
     *
     */
    class Pdf {
        /**
         * Load a HTML string
         *
         * @param string $html
         * @return \Pdf 
         * @static 
         */
        public static function loadHTML($html, $config = [])
        {
            /** @var \niklasravnsborg\LaravelPdf\PdfWrapper $instance */
            return $instance->loadHTML($html, $config);
        }

        /**
         * Load a HTML file
         *
         * @param string $file
         * @return \Pdf 
         * @static 
         */
        public static function loadFile($file, $config = [])
        {
            /** @var \niklasravnsborg\LaravelPdf\PdfWrapper $instance */
            return $instance->loadFile($file, $config);
        }

        /**
         * Load a View and convert to HTML
         *
         * @param string $view
         * @param array $data
         * @param array $mergeData
         * @return \Pdf 
         * @static 
         */
        public static function loadView($view, $data = [], $mergeData = [], $config = [])
        {
            /** @var \niklasravnsborg\LaravelPdf\PdfWrapper $instance */
            return $instance->loadView($view, $data, $mergeData, $config);
        }

            }
    }

namespace Barryvdh\DomPDF\Facade {
    /**
     * 
     *
     */
    class Pdf {
        /**
         * Get the DomPDF instance
         *
         * @static 
         */
        public static function getDomPDF()
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->getDomPDF();
        }

        /**
         * Show or hide warnings
         *
         * @static 
         */
        public static function setWarnings($warnings)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setWarnings($warnings);
        }

        /**
         * Load a HTML string
         *
         * @param string|null $encoding Not used yet
         * @static 
         */
        public static function loadHTML($string, $encoding = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadHTML($string, $encoding);
        }

        /**
         * Load a HTML file
         *
         * @static 
         */
        public static function loadFile($file)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadFile($file);
        }

        /**
         * Add metadata info
         *
         * @param array<string, string> $info
         * @static 
         */
        public static function addInfo($info)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->addInfo($info);
        }

        /**
         * Load a View and convert to HTML
         *
         * @param array<string, mixed> $data
         * @param array<string, mixed> $mergeData
         * @param string|null $encoding Not used yet
         * @static 
         */
        public static function loadView($view, $data = [], $mergeData = [], $encoding = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadView($view, $data, $mergeData, $encoding);
        }

        /**
         * Set/Change an option (or array of options) in Dompdf
         *
         * @param array<string, mixed>|string $attribute
         * @param null|mixed $value
         * @static 
         */
        public static function setOption($attribute, $value = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setOption($attribute, $value);
        }

        /**
         * Replace all the Options from DomPDF
         *
         * @param array<string, mixed> $options
         * @static 
         */
        public static function setOptions($options, $mergeWithDefaults = false)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setOptions($options, $mergeWithDefaults);
        }

        /**
         * Output the PDF as a string.
         * 
         * The options parameter controls the output. Accepted options are:
         * 
         * 'compress' = > 1 or 0 - apply content stream compression, this is
         *    on (1) by default
         *
         * @param array<string, int> $options
         * @return string The rendered PDF as string
         * @static 
         */
        public static function output($options = [])
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->output($options);
        }

        /**
         * Save the PDF to a file
         *
         * @static 
         */
        public static function save($filename, $disk = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->save($filename, $disk);
        }

        /**
         * Make the PDF downloadable by the user
         *
         * @static 
         */
        public static function download($filename = 'document.pdf')
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->download($filename);
        }

        /**
         * Return a response with the PDF to show in the browser
         *
         * @static 
         */
        public static function stream($filename = 'document.pdf')
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->stream($filename);
        }

        /**
         * Render the PDF
         *
         * @static 
         */
        public static function render()
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->render();
        }

        /**
         * 
         *
         * @param array<string> $pc
         * @static 
         */
        public static function setEncryption($password, $ownerpassword = '', $pc = [])
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setEncryption($password, $ownerpassword, $pc);
        }

            }
    }

namespace SimpleSoftwareIO\QrCode\Facades {
    /**
     * 
     *
     */
    class QrCode {
        /**
         * Generates the QrCode.
         *
         * @param string $text
         * @param string|null $filename
         * @return void|\Illuminate\Support\HtmlString|string 
         * @throws WriterException
         * @throws InvalidArgumentException
         * @static 
         */
        public static function generate($text, $filename = null)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->generate($text, $filename);
        }

        /**
         * Merges an image over the QrCode.
         *
         * @param string $filepath
         * @param float $percentage
         * @param \SimpleSoftwareIO\QrCode\SimpleSoftwareIO\QrCode\boolean|bool $absolute
         * @return \Generator 
         * @static 
         */
        public static function merge($filepath, $percentage = 0.2, $absolute = false)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->merge($filepath, $percentage, $absolute);
        }

        /**
         * Merges an image string with the center of the QrCode.
         *
         * @param string $content
         * @param float $percentage
         * @return \Generator 
         * @static 
         */
        public static function mergeString($content, $percentage = 0.2)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->mergeString($content, $percentage);
        }

        /**
         * Sets the size of the QrCode.
         *
         * @param int $pixels
         * @return \Generator 
         * @static 
         */
        public static function size($pixels)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->size($pixels);
        }

        /**
         * Sets the format of the QrCode.
         *
         * @param string $format
         * @return \Generator 
         * @throws InvalidArgumentException
         * @static 
         */
        public static function format($format)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->format($format);
        }

        /**
         * Sets the foreground color of the QrCode.
         *
         * @param int $red
         * @param int $green
         * @param int $blue
         * @param null|int $alpha
         * @return \Generator 
         * @static 
         */
        public static function color($red, $green, $blue, $alpha = null)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->color($red, $green, $blue, $alpha);
        }

        /**
         * Sets the background color of the QrCode.
         *
         * @param int $red
         * @param int $green
         * @param int $blue
         * @param null|int $alpha
         * @return \Generator 
         * @static 
         */
        public static function backgroundColor($red, $green, $blue, $alpha = null)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->backgroundColor($red, $green, $blue, $alpha);
        }

        /**
         * Sets the eye color for the provided eye index.
         *
         * @param int $eyeNumber
         * @param int $innerRed
         * @param int $innerGreen
         * @param int $innerBlue
         * @param int $outterRed
         * @param int $outterGreen
         * @param int $outterBlue
         * @return \Generator 
         * @throws InvalidArgumentException
         * @static 
         */
        public static function eyeColor($eyeNumber, $innerRed, $innerGreen, $innerBlue, $outterRed = 0, $outterGreen = 0, $outterBlue = 0)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->eyeColor($eyeNumber, $innerRed, $innerGreen, $innerBlue, $outterRed, $outterGreen, $outterBlue);
        }

        /**
         * 
         *
         * @static 
         */
        public static function gradient($startRed, $startGreen, $startBlue, $endRed, $endGreen, $endBlue, $type)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->gradient($startRed, $startGreen, $startBlue, $endRed, $endGreen, $endBlue, $type);
        }

        /**
         * Sets the eye style.
         *
         * @param string $style
         * @return \Generator 
         * @throws InvalidArgumentException
         * @static 
         */
        public static function eye($style)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->eye($style);
        }

        /**
         * Sets the style of the blocks for the QrCode.
         *
         * @param string $style
         * @param float $size
         * @return \Generator 
         * @throws InvalidArgumentException
         * @static 
         */
        public static function style($style, $size = 0.5)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->style($style, $size);
        }

        /**
         * Sets the encoding for the QrCode.
         * 
         * Possible values are
         * ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6,
         * ISO-8859-7, ISO-8859-8, ISO-8859-9, ISO-8859-10, ISO-8859-11,
         * ISO-8859-12, ISO-8859-13, ISO-8859-14, ISO-8859-15, ISO-8859-16,
         * SHIFT-JIS, WINDOWS-1250, WINDOWS-1251, WINDOWS-1252, WINDOWS-1256,
         * UTF-16BE, UTF-8, ASCII, GBK, EUC-KR.
         *
         * @param string $encoding
         * @return \Generator 
         * @static 
         */
        public static function encoding($encoding)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->encoding($encoding);
        }

        /**
         * Sets the error correction for the QrCode.
         * 
         * L: 7% loss.
         * M: 15% loss.
         * Q: 25% loss.
         * H: 30% loss.
         *
         * @param string $errorCorrection
         * @return \Generator 
         * @static 
         */
        public static function errorCorrection($errorCorrection)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->errorCorrection($errorCorrection);
        }

        /**
         * Sets the margin of the QrCode.
         *
         * @param int $margin
         * @return \Generator 
         * @static 
         */
        public static function margin($margin)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->margin($margin);
        }

        /**
         * Fetches the Writer.
         *
         * @param \BaconQrCode\Renderer\ImageRenderer $renderer
         * @return \BaconQrCode\Writer 
         * @static 
         */
        public static function getWriter($renderer)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->getWriter($renderer);
        }

        /**
         * Fetches the Image Renderer.
         *
         * @return \BaconQrCode\Renderer\ImageRenderer 
         * @static 
         */
        public static function getRenderer()
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->getRenderer();
        }

        /**
         * Returns the Renderer Style.
         *
         * @return \BaconQrCode\Renderer\RendererStyle\RendererStyle 
         * @static 
         */
        public static function getRendererStyle()
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->getRendererStyle();
        }

        /**
         * Fetches the formatter.
         *
         * @return \BaconQrCode\Renderer\Image\ImageBackEndInterface 
         * @static 
         */
        public static function getFormatter()
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->getFormatter();
        }

        /**
         * Fetches the module.
         *
         * @return \BaconQrCode\Renderer\Module\ModuleInterface 
         * @static 
         */
        public static function getModule()
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->getModule();
        }

        /**
         * Fetches the eye style.
         *
         * @return \BaconQrCode\Renderer\Eye\EyeInterface 
         * @static 
         */
        public static function getEye()
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->getEye();
        }

        /**
         * Fetches the color fill.
         *
         * @return \BaconQrCode\Renderer\RendererStyle\Fill 
         * @static 
         */
        public static function getFill()
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->getFill();
        }

        /**
         * Creates a RGB or Alpha channel color.
         *
         * @param int $red
         * @param int $green
         * @param int $blue
         * @param null|int $alpha
         * @return \BaconQrCode\Renderer\Color\ColorInterface 
         * @static 
         */
        public static function createColor($red, $green, $blue, $alpha = null)
        {
            /** @var \SimpleSoftwareIO\QrCode\Generator $instance */
            return $instance->createColor($red, $green, $blue, $alpha);
        }

            }
    }

namespace Illuminate\Support {
    /**
     * 
     *
     * @template TKey of array-key
     * @template-covariant TValue
     * @implements \ArrayAccess<TKey, TValue>
     * @implements \Illuminate\Support\Enumerable<TKey, TValue>
     */
    class Collection {
        /**
         * 
         *
         * @see \Maatwebsite\Excel\Mixins\DownloadCollectionMixin::downloadExcel()
         * @param string $fileName
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @param array $responseHeaders
         * @static 
         */
        public static function downloadExcel($fileName, $writerType = null, $withHeadings = false, $responseHeaders = [])
        {
            return \Illuminate\Support\Collection::downloadExcel($fileName, $writerType, $withHeadings, $responseHeaders);
        }

        /**
         * 
         *
         * @see \Maatwebsite\Excel\Mixins\StoreCollectionMixin::storeExcel()
         * @param string $filePath
         * @param string|null $disk
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @static 
         */
        public static function storeExcel($filePath, $disk = null, $writerType = null, $withHeadings = false)
        {
            return \Illuminate\Support\Collection::storeExcel($filePath, $disk, $writerType, $withHeadings);
        }

            }
    }

namespace Illuminate\Http {
    /**
     * 
     *
     */
    class Request {
        /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param array $rules
         * @param mixed $params
         * @static 
         */
        public static function validate($rules, ...$params)
        {
            return \Illuminate\Http\Request::validate($rules, ...$params);
        }

        /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param string $errorBag
         * @param array $rules
         * @param mixed $params
         * @static 
         */
        public static function validateWithBag($errorBag, $rules, ...$params)
        {
            return \Illuminate\Http\Request::validateWithBag($errorBag, $rules, ...$params);
        }

        /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $absolute
         * @static 
         */
        public static function hasValidSignature($absolute = true)
        {
            return \Illuminate\Http\Request::hasValidSignature($absolute);
        }

        /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @static 
         */
        public static function hasValidRelativeSignature()
        {
            return \Illuminate\Http\Request::hasValidRelativeSignature();
        }

        /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @param mixed $absolute
         * @static 
         */
        public static function hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
        {
            return \Illuminate\Http\Request::hasValidSignatureWhileIgnoring($ignoreQuery, $absolute);
        }

        /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @static 
         */
        public static function hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
        {
            return \Illuminate\Http\Request::hasValidRelativeSignatureWhileIgnoring($ignoreQuery);
        }

            }
    }

namespace Illuminate\Routing {
    /**
     * 
     *
     */
    class Route {
        /**
         * 
         *
         * @see \Spatie\Permission\PermissionServiceProvider::registerMacroHelpers()
         * @param mixed $roles
         * @static 
         */
        public static function role($roles = [])
        {
            return \Illuminate\Routing\Route::role($roles);
        }

        /**
         * 
         *
         * @see \Spatie\Permission\PermissionServiceProvider::registerMacroHelpers()
         * @param mixed $permissions
         * @static 
         */
        public static function permission($permissions = [])
        {
            return \Illuminate\Routing\Route::permission($permissions);
        }

            }
    }

namespace Tymon\JWTAuth\Claims {
    /**
     * 
     *
     */
    class Collection {
        /**
         * 
         *
         * @see \Maatwebsite\Excel\Mixins\DownloadCollectionMixin::downloadExcel()
         * @param string $fileName
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @param array $responseHeaders
         * @static 
         */
        public static function downloadExcel($fileName, $writerType = null, $withHeadings = false, $responseHeaders = [])
        {
            return \Tymon\JWTAuth\Claims\Collection::downloadExcel($fileName, $writerType, $withHeadings, $responseHeaders);
        }

        /**
         * 
         *
         * @see \Maatwebsite\Excel\Mixins\StoreCollectionMixin::storeExcel()
         * @param string $filePath
         * @param string|null $disk
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @static 
         */
        public static function storeExcel($filePath, $disk = null, $writerType = null, $withHeadings = false)
        {
            return \Tymon\JWTAuth\Claims\Collection::storeExcel($filePath, $disk, $writerType, $withHeadings);
        }

            }
    }


namespace  {
    class App extends \Illuminate\Support\Facades\App {}
    class Auth extends \Illuminate\Support\Facades\Auth {}
    class Config extends \Illuminate\Support\Facades\Config {}
    class DB extends \Illuminate\Support\Facades\DB {}
    class Hash extends \Illuminate\Support\Facades\Hash {}
    class Http extends \Illuminate\Support\Facades\Http {}
    class JWTAuth extends \Tymon\JWTAuth\Facades\JWTAuth {}
    class JWTFactory extends \Tymon\JWTAuth\Facades\JWTFactory {}
    class Route extends \Illuminate\Support\Facades\Route {}
    class URL extends \Illuminate\Support\Facades\URL {}
    class Validator extends \Illuminate\Support\Facades\Validator {}
    class Excel extends \Maatwebsite\Excel\Facades\Excel {}
    class PDF extends \niklasravnsborg\LaravelPdf\Facades\Pdf {}
    class Pdf extends \Barryvdh\DomPDF\Facade\Pdf {}
    class QrCode extends \SimpleSoftwareIO\QrCode\Facades\QrCode {}
}





