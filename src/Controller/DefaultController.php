<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ContactType;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ContactRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET", "POST"})
     */
    public function index(Request $request, ContactRepository $contactRepository): Response
    {
        $form = $this->createForm(ContactType::class, new Contact());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contact = $form->getData();
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.');
            return $this->redirectToRoute('home');
        }

        $contacts = $contactRepository->findAll();

        return $this->render('base.html.twig', [
            'form' => $form->createView(),
            'contacts' => $contacts,
        ]);
    }
}