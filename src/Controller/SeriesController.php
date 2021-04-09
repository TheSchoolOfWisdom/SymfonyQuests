<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramSearchType;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Services\SlugifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SeriesController
 * @package App\Controller
 * @Route("/series", name="series_")
 */
class SeriesController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function index(ProgramRepository $programRepository) : Response
    {
        $form = $this->createForm(
            ProgramSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );

        $programs = $programRepository->findAll();

        return $this->render('series/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/{category}",
     *     requirements={"category"="^[a-z0-9-]+$"},
     *     defaults={"category"=""},
     *     name="category"
     * )
     * @param string $category
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function showByCategory(string $category, CategoryRepository $categoryRepository): Response
    {
        $category = SlugifyService::slugify($category);
        $category = $categoryRepository->findOneBy(['name' => $category]);

        return $this->render('series/category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/{showByProgram}",
     *     requirements={"showByProgram"="^[a-z0-9-]+$"},
     *     defaults={"showByProgram"=""},
     *     name="serie"
     * )
     * @param string $showByProgram
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function showByProgram(string $showByProgram, ProgramRepository $programRepository): Response
    {
        $showByProgram = SlugifyService::slugify($showByProgram);
        $program = $programRepository->findOneBy(['title' => $showByProgram]);

        return $this->render('series/serie.html.twig', [
            'program' => $program
        ]);
    }

    /**
    * @Route("/{program}/{season}",
    *     name="season"
    * )
    * @param string $program
    * @param Season $season
    * @param ProgramRepository $programRepository
    * @return Response
    */
    public function showBySeason(string $program, Season $season, ProgramRepository $programRepository): Response
    {
        $program = SlugifyService::slugify($program);

        $program = $programRepository->findOneBy(['title' => $program]);

        return $this->render('series/season.html.twig', [
            'program' => $program,
            'season' => $season
        ]);
    }

    /**
     * @Route("/{program}/{season}/{episode}",
     *     name="episode"
     * )
     * @param string $program
     * @param Season $season
     * @param Episode $episode
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function showByEpisode(string $program, Season $season, Episode $episode, ProgramRepository $programRepository): Response
    {
        $program = SlugifyService::slugify($program);
        $program = $programRepository->findOneBy(['title' => $program]);

        return $this->render('series/episode.html.twig', [
            'episode' => $episode,
            'season' => $season
        ]);
    }
}
