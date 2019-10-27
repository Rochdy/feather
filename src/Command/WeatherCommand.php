<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use App\Service\OpenWeatherMapService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Exception\CityNotFoundException;
use App\Exception\FreeTrialExceededException;
use App\Exception\InvalidApiKeyException;

class WeatherCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'weather';
    /**
     * @var OpenWeatherMapService
     */
    protected $openWeatherMapService;

    /**
     * WeatherCommand constructor.
     * @param OpenWeatherMapService $openWeatherMapService
     */
    public function __construct(OpenWeatherMapService $openWeatherMapService)
    {
        $this->openWeatherMapService = $openWeatherMapService;
        parent::__construct();
    }

    /**
     * Weather Command Configuration
     */
    protected function configure()
    {
        $this
            ->setDescription('Show a city weather.')
            ->setHelp('This command allows you the current weather of any city which you specify as argument')
            ->addArgument('city', InputArgument::REQUIRED, 'The city name')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $city = $input->getArgument('city');
        try{
            $weatherInfo = $this->openWeatherMapService->setCity($city)->getFormattedWeatherCondition();
            $output->writeln("<info>{$weatherInfo}</info>");
        } catch (CityNotFoundException $e) {
            $output->writeln("<comment>{$e->getMessage()}</comment>");
        } catch (FreeTrialExceededException $e) {
            $output->writeln("<comment>{$e->getMessage()}</comment>");
        } catch (InvalidApiKeyException $e) {
            $output->writeln("<comment>{$e->getMessage()}</comment>");
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }
    }
}
