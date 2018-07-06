<?php

namespace App\Domain\FilesCountService;

use App\Domain\FilesTreeReader\File;
use App\Domain\FilesTreeReader\WordOccurrence;
use PHPUnit\Framework\TestCase;

class FilesCountServiceTest extends TestCase
{
    /** @var array $files */
    private $files = [];

    protected function setUp()
    {
        $this->files = [
            new File('ActivityController.php'),
            new File('CityController.php'),
            new File('CategoryController.php'),
            new File('ScapesController.php'),
            new File('HotelController.php'),
            new File('ActivityControllerTest.php'),
            new File('CityControllerTest.php'),
            new File('CategoryControllerTest.php'),
            new File('ScapesControllerTest.php'),
            new File('HotelControllerTest.php'),
        ];
        $this->sut = new FilesCountService();
    }

    public function testCountOccurrencesSuccess()
    {
        $wordOcurrences = FilesCountService::countOccurrences($this->files);

        $resultExpected = ['Controller' => new WordOccurrence('Controller', 10)];
        $resultExpected = array_merge(['Activity' => new WordOccurrence('Activity', 2)], $resultExpected);
        $resultExpected = array_merge(['City' => new WordOccurrence('City', 2)], $resultExpected);
        $resultExpected = array_merge(['Category' => new WordOccurrence('Category', 2)], $resultExpected);
        $resultExpected = array_merge(['Scapes' => new WordOccurrence('Scapes', 2)], $resultExpected);
        $resultExpected = array_merge(['Hotel' => new WordOccurrence('Hotel', 2)], $resultExpected);
        $resultExpected = array_merge(['Test' => new WordOccurrence('Test', 5)], $resultExpected);

        $this->assertEquals($wordOcurrences, $resultExpected);

    }
}
