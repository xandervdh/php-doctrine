<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
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
     * @Route("/students", name="students")
     */



    public function createStudents()
    {
        $teacher = $this->teacherRepository->findOneById(1);
        $entityManager = $this->getDoctrine()->getManager();

        $student = new Student();
        $student->setFirstName('Sicco');
        $student->setLastName('Smit');
        $student->setEmail('sicco@becode.com');
        $student->setAddress(new Address('ergens', 666, 'deurne', 2640));
        $student->setTeacher($teacher);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($student);
        $entityManager->initializeObject($student);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new student with id '.$student->getId());
    }

    /**
     * @Route("/teachers", name="teachers")
     */
    public function teachers(): response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $teacher = new Teacher();
        $teacher->setName('Xander');
        $teacher->setEmail('xander@hotmail.com');
        $teacher->setAddress(new Address('guido gezellelaan', 58, 'mortsel', 2640));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($teacher);
        //$entityManager->initializeObject($teacher);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new teacher with id '.$teacher->getId());
    }
}
