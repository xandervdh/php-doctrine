<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student;

class RestController extends AbstractFOSRestController
{
    private TeacherRepository $teacherRepository;

    /**
     * RestController constructor.
     * @param TeacherRepository $teacherRepository
     */
    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * @Route("/students/", name="students")
     */
    public function createStudents(Request $request): response
    {
        $repository = $this->getDoctrine()->getRepository(Student::class);
        $teachers = $repository->findAll();

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($teachers, 'json');

        $form = $this->createFormBuilder(null, [
            //'action' => '/students',
            'method' => 'PUT',
        ])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', TextType::class)
            ->add('street', TextType::class)
            ->add('houseNumber', TextType::class)
            ->add('city', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('Teacher', TextType::class)
            ->getForm();

        if (isset($_REQUEST['form'])){
            $data = $_REQUEST['form'];
            $teacher = $this->teacherRepository->findOneById($data['Teacher']);
            $entityManager = $this->getDoctrine()->getManager();

            $student = new Student();
            $student->setFirstName($data['firstName']);
            $student->setLastName($data['lastName']);
            $student->setEmail($data['email']);
            $student->setAddress(new Address($data['street'], $data['houseNumber'], $data['city'], $data['zipCode']));
            $student->setTeacher($teacher);

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($student);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            $message = new Response('Saved new student with id '.$student->getId());

            return $this->render('rest/index.html.twig', [
                'message' => $message,
                'form' => $form->createView(),
                'json' => $jsonContent,
            ]);
        }
        return $this->render('rest/index.html.twig', [
            'message' => 'create student',
            'form' => $form->createView(),
            'json' => $jsonContent,
        ]);
    }

    /**
     * @Route("/teachers", name="teachers")
     */
    public function teachers(): response
    {
        $repository = $this->getDoctrine()->getRepository(Teacher::class);
        $teachers = $repository->findAll();

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($teachers, 'json');

        $form = $this->createFormBuilder(null, [
            //'action' => '/students',
            'method' => 'PUT',
        ])
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('street', TextType::class)
            ->add('houseNumber', TextType::class)
            ->add('city', TextType::class)
            ->add('zipCode', TextType::class)
            ->getForm();

        if (isset($_REQUEST['form'])){
            $data = $_REQUEST['form'];
            $entityManager = $this->getDoctrine()->getManager();

            $teacher = new Teacher();
            $teacher->setName($data['name']);
            $teacher->setEmail($data['email']);
            $teacher->setAddress(new Address($data['street'], $data['houseNumber'], $data['city'], $data['zipCode']));

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($teacher);
            $entityManager->initializeObject($teacher);
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            $message = new Response('Saved new teacher with id '.$teacher->getId());

            return $this->render('rest/index.html.twig', [
                'message' => $message,
                'form' => $form->createView(),
                'json' => $jsonContent,
            ]);
        }
        return $this->render('rest/index.html.twig', [
            'message' => 'create teacher',
            'form' => $form->createView(),
            'json' => $jsonContent,
        ]);
    }

    /**
     * @Route("/students/{id}", name="studentProfile")
     */
    public function studentProfile(Request $request)
    {
        $id = $request->attributes->get('id');
        $repository = $this->getDoctrine()->getRepository(Student::class);
        $student = $repository->find($id);

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($student, 'json');

        return $this->render('rest/profile.html.twig', [
            'message' => 'student',
            'json' => $jsonContent,
        ]);
    }

    /**
     * @Route("/teachers/{id}", name="studentProfile")
     */
    public function teacherProfile(Request $request)
    {
        $id = $request->attributes->get('id');
        $repository = $this->getDoctrine()->getRepository(Teacher::class);
        $teacher = $repository->find($id);

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($teacher, 'json');

        return $this->render('rest/profile.html.twig', [
            'message' => 'teachers',
            'json' => $jsonContent,
        ]);
    }
}
