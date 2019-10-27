<?php
namespace App;

use App\Command\WeatherCommand;
use App\Service\OpenWeatherMapService;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Dotenv\Dotenv;

class AppKernel
{
    private $container;

    public function boot()
    {
        (new Dotenv())->load(__DIR__ . '/../.env');
        self::initDiContainer();
        return $this;
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    private function initDiContainer()
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder
            ->setParameter('openWeatherApiKey', $_ENV['OPEN_WEATHER_API_KEY']);

        $containerBuilder
            ->register('openWeatherMapService', OpenWeatherMapService::class)
            ->addArgument('%openWeatherApiKey%')
            ->addArgument(new Client());

        $containerBuilder
            ->register('weatherCommand', WeatherCommand::class)
            ->addArgument(new Reference('openWeatherMapService'));

        $this->container = $containerBuilder;
    }
}
