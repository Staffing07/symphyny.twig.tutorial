<?php
namespace App\Controller;

use App\Entity\Article;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;  //allows use of routing - needs to be added to project via bash ' composer requires annotations '
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; //allows use of specifying and restricting methods
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     * @Method({"GET"})
     */


    public function index()
    {
        // return new Response
        // ('<html><body>Hello</body></html>');

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        //return $this->render('articles/index.html.twig', array ('name' => 'David'));
        return $this->render('articles/index.html.twig', array('articles' => $articles));
    }



    /**
     * @Route("/article/new", name="new_article")
     * Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $article = new Article();


        // This is where the form is being created (ignore the red squiggles caused by intelephense it works fine)
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' =>
            array('class' => 'form-control')))
            ->add('body', TextareaType::class, array(
                'required' => false, 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            //create new db record and redirect to homepage named article_list
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', array(
            // we pass this view in as te variable form and call it in the new.html.twig page
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/article/edit/{id}", name="edit_article")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $article = new Article();
        // finds the article by the id passed in
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        // This is where the form is being created (ignore the red squiggles caused by intelephense it works fine)
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' =>
            array('class' => 'form-control')))
            ->add('body', TextareaType::class, array(
                'required' => false, 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            //create new db record and redirect to homepage named article_list
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/edit.html.twig', array(
            // we pass this view in as te variable form and call it in the new.html.twig page
            'form' => $form->createView()
        ));
    }





    /**
     * @Route("/article/{id}", name="article_show")
     */

    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', array('article' => $article));
    }

    /**
     * @Route("/article/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    // /**
    //  * @Route("/article/save")
    //  */
    // public function save(){
    //     $entityManager = $this->getDoctrine()->getManager();

    //     $article = new Article();
    //     $article->setTitle('Article Two');
    //     $article->setBody('This is the body for article two');

    //     $entityManager->persist($article);  //take the dump

    //     $entityManager->flush();  // flush it

    //     return new Response('Saved an article with the id of '.$article->getId());

    // }
}

    // To see this you need to setup a route.  Can either use the routes.yaml file in bin/config or use annotations by adding it to composer. Then the route can be added directly above the method as shown above.  Can also add abilty to GET requests etc
