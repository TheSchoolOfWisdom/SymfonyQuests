<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Services\SlugifyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoriesController
 * @package App\Controller
 * @Route ("/categories", name="categories_")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();
        return $this->render('categories/index.html.twig', [
            'categories' => $category
        ]);
    }

    /**
     * @Route("/add",
     *      name="add",
     *      methods="GET|POST"
     * )
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['method' => Request::METHOD_GET]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('series_category', [
                'category' => SlugifyService::slugify($category->getName())
            ]);
        }

        return $this->render('categories/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}",
     *      name="delete"
     * )
     * @param Category $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Category $id, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($id);
        $entityManager->flush();
        return $this->redirectToRoute('categories_index');
    }
}
