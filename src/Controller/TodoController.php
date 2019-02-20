<?php

/*
 * This file is part of the TodoList package.
 * (c) Aleksey Mihayluk <sidlerbiz@gmail.com>
 */

namespace App\Controller;

use App\Form\CreateTodoType;
use App\Entity\Todo;
use App\Form\EditTodoType;
use Neo\Bundle\TodoBundle\Service\TodoService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class TodoController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TodoService
     */
    private $todoService;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param TodoService $todoService
     * @param Environment $twig
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        TodoService $todoService,
        Environment $twig,
        RouterInterface $router,
        SessionInterface $session
    ) {
        $this->session = $session;
        $this->router = $router;
        $this->todoService = $todoService;
        $this->twig = $twig;
    }

    /**
     * @Route("/todos", name="todo_list")
     *
     * @return Response
     */
    public function listAction()
    {
        $todos = $this->todoService->getList();

        $result = $this->twig->render('todo/index.html.twig', [
            'todos' => $todos,
        ]);

        return new Response($result);
    }

    /**
     * @Route("/todo/create", name="todo_create")
     *
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     *
     * @return Response
     */
    public function createAction(Request $request, FormFactoryInterface $formFactory)
    {
        $todo = new Todo();

        $form = $formFactory->create(CreateTodoType::class, $todo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todoService->save($todo);

            $this->session->getFlashBag()->add('notice', 'Todo Added');

            return new RedirectResponse($this->router->generate('todo_list'));
        }

        $result = $this->twig->render('todo/create.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($result);
    }

    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     *
     * @param $id
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     *
     * @return Response
     */
    public function editAction($id, Request $request, FormFactoryInterface $formFactory)
    {
        $todo = $this->todoService->getById($id);
        $form = $formFactory->create(EditTodoType::class, $todo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todoService->save($todo);

            $this->session->getFlashBag()->add('notice', 'Todo Updated');

            return new RedirectResponse($this->router->generate('todo_list'));
        }

        $result = $this->twig->render('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);

        return new Response($result);
    }

    /**
     * @Route("/todo/details/{id}", name="todo_details")
     *
     * @param int $id
     *
     * @return Response
     */
    public function detailsAction($id): Response
    {
        $todo = $this->todoService->getById($id);

        $result = $this->twig->render('todo/details.html.twig', [
            'todo' => $todo,
        ]);

        return new Response($result);
    }

    /**
     * @Route("/todo/done/{id}", name="todo_done")
     *
     * @param int $id
     *
     * @return Response
     */
    public function doneAction($id): Response
    {
        $this->todoService->changeStatus($id, true);

        $this->session->getFlashBag()->add('notice', 'Todo marked as Done');

        return new RedirectResponse($this->router->generate('todo_list'));
    }

    /**
     * @Route("/todo/reset/{id}", name="todo_reset")
     *
     * @param int $id
     *
     * @return Response
     */
    public function resetAction($id): Response
    {
        $this->todoService->changeStatus($id, false);

        $this->session->getFlashBag()->add('notice', 'Todo reverted');

        return new RedirectResponse($this->router->generate('todo_list'));
    }

    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     *
     * @param int $id
     * @param RouterInterface $router
     * @param Session $session
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id, RouterInterface $router, Session $session)
    {
        $this->todoService->remove($id);

        $session->getFlashBag()->add('notice', 'Todo Removed');

        return new RedirectResponse($router->generate('todo_list'));
    }
}
