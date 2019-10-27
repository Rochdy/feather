<?php
namespace Tests\Command;

use App\AppKernel;
use App\Command\WeatherCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class WeatherCommandTest extends TestCase
{
    /** @var WeatherCommand */
    private $weatherCommand;

    /** @var CommandTester */
    private $weatherCommandTester;

    protected function setUp()
    {
        $kernel = (new AppKernel())->boot();
        $weatherCommand = $kernel->getContainer()->get('weatherCommand');
        $application = new Application();
        $application->add($weatherCommand);
        $this->weatherCommand = $application->find('weather');
        $this->weatherCommandTester = new CommandTester($this->weatherCommand);
    }

    protected function tearDown()
    {
        $this->weatherCommand = null;
        $this->weatherCommandTester = null;
    }

    public function testCommandShouldThrowRunTimeExceptionIfCityArgumentIsMissing()
    {
        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        $this->weatherCommandTester->execute([
            'command'  => $this->weatherCommand->getName(),
        ]);
    }

    public function testCommandThrowDetailedErrorIfCityIsNotFound()
    {
        $this->weatherCommandTester->execute([
            'command'  => $this->weatherCommand->getName(),
            'city' => 'notACityNameLol'
        ]);
        $output = $this->weatherCommandTester->getDisplay();
        $this->assertContains("I Can't Find This city", $output);
    }

    public function testWeatherConditionShownSuccessfullyIfCityIsValid()
    {
        $city = 'berlin';
        $this->weatherCommandTester->execute([
            'command'  => $this->weatherCommand->getName(),
            'city' => $city
        ]);
        $output = $this->weatherCommandTester->getDisplay();
        $this->assertContains("In {$city}", $output);
        $this->assertContains('degrees celsius', $output);
    }

    public function testWeatherConditionShownSuccessfullyIfCityIsValidWithGerman()
    {
        $city = 'Düsseldorf';
        $this->weatherCommandTester->execute([
            'command'  => $this->weatherCommand->getName(),
            'city' => $city
        ]);
        $output = $this->weatherCommandTester->getDisplay();
        $this->assertContains("In {$city}", $output);
        $this->assertContains('degrees celsius', $output);
    }

    public function testWeatherConditionShownSuccessfullyIfCityIsValidWithArabic()
    {
        $city = 'أسوان';
        $this->weatherCommandTester->execute([
            'command'  => $this->weatherCommand->getName(),
            'city' => $city
        ]);
        $output = $this->weatherCommandTester->getDisplay();
        $this->assertContains("In {$city}", $output);
        $this->assertContains('degrees celsius', $output);
    }

    public function testWeatherConditionContainsAvalidTempDegree()
    {
        $city = 'berlin';
        $this->weatherCommandTester->execute([
            'command'  => $this->weatherCommand->getName(),
            'city' => $city
        ]);
        $output = $this->weatherCommandTester->getDisplay();
        $this->assertIsNumeric(explode(" ",$output)[5]);
    }
}
