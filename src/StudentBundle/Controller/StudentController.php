<?php

namespace StudentBundle\Controller;

use StudentBundle\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * @Route("/student")
 */
class StudentController extends Controller
{
    /**
     * @Route("/", name="students")
     */
    public function showAllAction()
    {
        $students = $this->getDoctrine()->getRepository("StudentBundle:Student")->findAll();

        return $this->render('StudentBundle:Student:show_all.html.twig', array(
            "students" => $students
        ));
    }

    /**
     * @Route("/create", name="student_create")
     * @param Request $request
     */
    public function createAction(Request $request)
    {

        $student = new Student();

        $form = $this->createFormBuilder($student)
            ->add('Nom', TextType::class)
            ->add('Prenom', TextType::class)
            ->add('Naissance', DateType::class)
            ->add('classe', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Student'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute("students");
        }

        return $this->render('StudentBundle:Student:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/update/{id}", name="student_update")
     */
    public function updateAction(Request $request, $id)
    {
        $student = $this->getDoctrine()->getRepository("StudentBundle:Student")->findOneById($id);

        $form = $this->createFormBuilder($student)
            ->add('Nom', TextType::class)
            ->add('Prenom', TextType::class)
            ->add('Naissance', DateType::class)
            ->add('classe', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Update Student'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute("student_show", array("id" => $id));
        }

        return $this->render('StudentBundle:Student:update.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/show/{id}", name="student_show")
     */
    public function showAction($id)
    {
        $student = $this->getDoctrine()->getRepository("StudentBundle:Student")->findOneById($id);

        return $this->render('StudentBundle:Student:show.html.twig', array(
            "student" => $student
        ));
    }

    /**
     * @Route("/delete/{id}", name="student_delete")
     */
    public function deleteAction($id)
    {
        $student = $this->getDoctrine()->getRepository("StudentBundle:Student")->findOneById($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute("students");
    }

}
