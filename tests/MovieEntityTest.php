<?php

use App\Entity\Director;
use App\Entity\Movie;
use App\Entity\TMDB;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MovieEntityTest extends KernelTestCase
{
    /**
     * @return void
     */
    public function testReturnTitle(): void
    {
        $movie = new Movie();
        $movie->setTitle('Test title');

        $this->assertEquals('Test title', $movie->getTitle());
    }

    /**
     * @return void
     */
    public function testReturnDirector(): void
    {
        $movie = new Movie();

        $director = new Director();
        $director->setName('Test Director');

        $movie->addDirector($director);

        $this->assertEquals('Test Director', $director->getName());
    }

    /**
     * @return void
     */
    public function testReturnTMDB(): void
    {
        $movie = new Movie();

        $TMDB = new TMDB();
        $TMDB->setUniqueId(1234);

        $movie->setTMDB($TMDB);

        $this->assertEquals(1234, $TMDB->getUniqueId());
    }
}