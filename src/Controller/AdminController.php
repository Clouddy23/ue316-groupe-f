<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    // ==================== POSTS ====================

    #[Route('', name: 'admin_dashboard')]
    #[Route('/posts', name: 'admin_posts')]
    public function posts(PostRepository $postRepository): Response
    {
        return $this->render('admin/posts.html.twig', [
            'posts' => $postRepository->findBy([], ['publishedAt' => 'DESC']),
        ]);
    }

    #[Route('/posts/new', name: 'admin_post_new', methods: ['GET', 'POST'])]
    public function newPost(Request $request, EntityManagerInterface $em, PostRepository $postRepository): Response
    {
        $post = new Post();
        $post->setPublishedAt(new \DateTimeImmutable());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $slugger = new AsciiSlugger();
            $baseSlug = strtolower($slugger->slug($post->getTitle())->toString());
            $slug = $baseSlug;
            $counter = 1;
            while ($postRepository->findOneBy(['slug' => $slug])) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $post->setSlug($slug);
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Article créé.');
            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('admin/post_form.html.twig', [
            'form' => $form,
            'title' => 'Nouvel article',
        ]);
    }

    #[Route('/posts/{id}/edit', name: 'admin_post_edit', methods: ['GET', 'POST'])]
    public function editPost(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Article modifié.');
            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('admin/post_form.html.twig', [
            'form' => $form,
            'title' => 'Modifier : ' . $post->getTitle(),
        ]);
    }

    #[Route('/posts/{id}/delete', name: 'admin_post_delete', methods: ['POST'])]
    public function deletePost(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $em->remove($post);
            $em->flush();
            $this->addFlash('success', 'Article supprimé.');
        }
        return $this->redirectToRoute('admin_posts');
    }

    // ==================== COMMENTS ====================

    #[Route('/comments', name: 'admin_comments')]
    public function comments(CommentRepository $commentRepository): Response
    {
        return $this->render('admin/comments.html.twig', [
            'comments' => $commentRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/comments/{id}/toggle', name: 'admin_comment_toggle', methods: ['POST'])]
    public function toggleComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle' . $comment->getId(), $request->request->get('_token'))) {
            $comment->setIsFlagged(!$comment->isFlagged());
            $em->flush();
        }
        return $this->redirectToRoute('admin_comments');
    }

    #[Route('/comments/{id}/delete', name: 'admin_comment_delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé.');
        }
        return $this->redirectToRoute('admin_comments');
    }

    // ==================== USERS ====================

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findBy([], ['id' => 'ASC']),
        ]);
    }

    #[Route('/users/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function newUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $role = $request->request->get('role');

            if ($email && $password) {
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($hasher->hashPassword($user, $password));
                $user->setRoles($role === 'admin' ? ['ROLE_ADMIN'] : []);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Utilisateur créé.');
                return $this->redirectToRoute('admin_users');
            }
        }

        return $this->render('admin/user_form.html.twig');
    }

    #[Route('/users/{id}/role', name: 'admin_user_role', methods: ['POST'])]
    public function toggleRole(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas modifier votre propre rôle.');
            return $this->redirectToRoute('admin_users');
        }

        if ($this->isCsrfTokenValid('role' . $user->getId(), $request->request->get('_token'))) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $user->setRoles([]);
            } else {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $em->flush();
            $this->addFlash('success', 'Rôle modifié.');
        }
        return $this->redirectToRoute('admin_users');
    }

    #[Route('/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('admin_users');
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé.');
        }
        return $this->redirectToRoute('admin_users');
    }
}
