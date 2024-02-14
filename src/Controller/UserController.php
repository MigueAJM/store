<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    private const TEMPLATE = 'user/index.html.twig';
    #[Route('/', name: '_sign_in', methods: ['GET'])]
    public function signIn(): Response
    {
        $form = $this->getFormSignIn()->createView();
        $form->vars['attr']['id'] = "form-sign-in";
        return $this->render(self::TEMPLATE, [
            'form' => $form,
            'title' => "Sign in",
            'formId' => ['signIn' => 'form-sign-in']
        ]);
    }

    #[Route('/auth', name: '_auth', methods: ['POST'])]
    public function authenticate(Request $request, UserRepository $repository): Response
    {
        $form = $this->getFormSignIn();
        $entity = $form->handleRequest($request)->getData();
        $password = $_ENV['SALT'].$entity->getPassword();
        $password = hash($_ENV['ALG'], $password);
        $criteria = [
            'email' => trim($entity->getEmail()),
            'password' => $password
        ];
        $user = $repository->findBy($criteria);
        if(!isset($user[0])) return $this->json(['error' => "Email or password is incorrect."], 400);
        return $this->json($user[0]->toArray(), Response::HTTP_ACCEPTED);
    }

    public function register(Request $request, UserRepository $repository): Response
    {
        $form = $this->getFormUser();
        return $this->render(self::TEMPLATE, ['form' => $form->createView()]);
    }

    private function getFormSignIn(): FormInterface
    {
        $form = $this->createFormBuilder(new User())
            ->setMethod(Request::METHOD_POST)
            ->setAction($this->generateUrl('app_user_auth'))
            ->add('email', EmailType::class, [
                'label' => "Email:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ])
            ->add('password', PasswordType::class, [
                'label' => "Password:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ])
            ->add('signIn', SubmitType::class, [
                'label' => "Sign in",
                'attr' => [
                    'class' => "btn btn-primary",
                    'style' => "position: absolute; right: 1rem; top: 0.5rem"
                ]
            ])
        ;
        return $form->getForm();
    }

    private function getFormResetPassword(): FormInterface
    {
        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, [
                'label' => "Password:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => "Confirm Password:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ])
            ->add('confirm', SubmitType::class, [
                'label' => "Confirm",
                'attr' => [
                    'class' => "btn btn-primary",
                    'style' => "position: absolute; right: 1rem; top: 0.5rem"
                ]
            ])
        ;
        return $form->getForm();
    }

    private function getFormUser(bool $isUpdate = false): FormInterface
    {
        $form = $this->createFormBuilder(new User())
            ->setMethod(Request::METHOD_POST)
            ->setAction($this->generateUrl('app_user_index'))
            ->add('id', IntegerType::class, [
                'label' => "ID:",
                'row_attr' => ['class' => "col-sm-12 col-md-6", 'hidden' => true]
            ])
            ->add('firstname', TextType::class, [
                'label' => "Firstname:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ])
            ->add('lastname', TextType::class, [
                'label' => "Lastname:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ])
            ->add('nickname', TextType::class, [
                'label' => "Nickname:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ])
            ->add('email', EmailType::class, [
                'label' => "Email:",
                'label_attr' => ['class' => "form-label"],
                'attr' => ['class' => "form-control"],
                'row_attr' => ['class' => 'col-sm-12 col-md-6']
            ]);
            if(!$isUpdate){
                $form->add('password', PasswordType::class, [
                    'label' => "Password:",
                    'label_attr' => ['class' => "form-label"],
                    'attr' => ['class' => "form-control"],
                    'row_attr' => ['class' => 'col-sm-12 col-md-6']
                ])
                ->add('confirmPassword', PasswordType::class, [
                    'label' => "Confirm Password:",
                    'label_attr' => ['class' => "form-label"],
                    'attr' => ['class' => "form-control"],
                    'row_attr' => ['class' => 'col-sm-12 col-md-6']
                ]);
            }
        $form->add('register', SubmitType::class, [
            'label' => "Register",
            'attr' => [
                'class' => "btn btn-primary",
                'style' => "position: absolute; right: 1rem; top: 0.5rem"
            ]
        ]);
            /*->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'placeholder' => 'Selected an option',
                'row_attr' => ['class' => "col-sm-12 col-md-12"],
                'attr' => ['class' => "form-select"],
            ]) */
        return $form->getForm();
    }
}
