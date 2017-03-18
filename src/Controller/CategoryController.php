<?php
namespace Controller;
use Library\Controller;
use Library\Request;
use Model\Category;
use Library\Session;
use Library\Pagination\Pagination;
class CategoryController extends Controller
{
    const BOOKS_PER_PAGE = 5;
    public function indexAction(Request $request)
    {
        $repos=$this->container->get('repository_manager')->getRepository('Category');
        $id = $request->get('id');
        $category = $repos->find($id);

        $page=(int)$request->get('page',1);
        if ($page < 1)
        {
            $page=1;
        }
        $repos=$this->container->get('repository_manager')->getRepository('Post');
        $count = $repos->count($id);
        $posts = $repos->findByCategoryByPage($id, $page, self::BOOKS_PER_PAGE);
        if (!$posts && $count) {
            $this->container->get('router')->redirect('/');
        }
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['category'=>$category, 'posts'=>$posts, 'pagination'=>$pagination];
        return $this->render('index.phtml', $args);

    }

    public function analiticsAction(Request $request)
    {
        if (!Session::has('user')) {
            $this->container->get('router')->redirect('/login');
        }
        $page=(int)$request->get('page',1);
        if ($page < 1)
        {
            $page=1;
        }
        $repos=$this->container->get('repository_manager')->getRepository('Post');
        $count = $repos->countAnalitics();

        $posts = $repos->findByAnalitics($page, self::BOOKS_PER_PAGE);

        if (!$posts && $count) {
            $this->container->get('router')->redirect('/');
        }
        $pagination = new Pagination($count, self::BOOKS_PER_PAGE, $page);
        $args=['posts'=>$posts, 'pagination'=>$pagination];
        return $this->render('analitics.phtml', $args);
    }

    public function addAction(Request $request)
    {

        $id = $request->get('id');
        $amount = $request->get('amount', 1);

//        $cart = new Cart();
//        $cart->addProduct($id);
//
//        $this->container->get('router')->redirect("/book-{$id}.html");

        try {
            $book = $this
                ->container
                ->get('repository_manager')
                ->getRepository('Book')
                ->find($id, $findOnlyActive = true)
            ;

        } catch (\Exception $e) {
            Session::setFlash('Book not found');
            $this->container->get('router')->redirect('/');
        }

        $this->container->get('cart_service')->addToCart($book, $amount);
        Session::setFlash('Book(s) added to cart');
        $this->container->get('router')->redirect("/book-{$id}.html");
    }

    /**
     * @param Request $request
     * @return string
     */
    public function showListAction(Request $request)
    {


        $cartService = $this->container->get('cart_service');
        $cart = $cartService->getCart();

        $ids = [];

        foreach ($cart as $id => $amount) {
            $ids[] = $id;
        }

        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $books = $repo->findByIdArray($ids);

        return $this->render('show.phtml', ['books' => $books, 'cart' => $cart]);
    }




}