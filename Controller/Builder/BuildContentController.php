<?php

namespace Builder\PageBundle\Controller\Builder;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Builder\PageBundle\Controller\Builder\Utils;
use Builder\PageBundle\Entity\Page_Content;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class BuildContentController extends Controller
{
    //Contents of default page with filtered position: selectposition*
    public function buildDefaultContentAction($selectposition, Request $request)
    {
        $page = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BuilderPageBundle:Page')
            ->findOneBy(array('slug' => 'default'));
        $selectContents = null;
        if ($page) {
            $pageContents = $page->getPageContents();
            $selectContents = Utils::filterPageContentPositionStartWith($pageContents, $selectposition);
        }
        //dump($selectContents);
        return $this->render('BuilderPageBundle:BuildPage:buildcontents.html.twig', array(
            'page' => $page,
            'contents' => $selectContents,
            'request' => $request,
            'notfoundmessage' => 'Erreur: le contenu par défaut en position ' . $selectposition . ' doit être défini dans la base.'
        ));
    }

    public function buildCardAction($contentName, Request $request)
    {
        $content = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BuilderPageBundle:Content')
            ->findOneBy(array('title' => $contentName));

        $pageContent = new \Builder\PageBundle\Entity\Page_Content();
        $pageContent->setPage(new \Builder\PageBundle\Entity\Page());
        $pageContent->setContent($content);
        return $this->render('BuilderPageBundle:BuildContent:card.html.twig', array(
            'pageContent' => $pageContent
        ));
    }

    //ONLY CONTENT, peut être utilisé dans un popup par exemple
    public function buildPageContentAction($slug, Request $request)
    {
        //dump($slug);
        $page = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BuilderPageBundle:Page')
            ->findOneBy(array('slug' => $slug));
        $pageContents = null;
        if ($page) {
            $pageContents = $page->getPageContents();
        }
        return $this->render('BuilderPageBundle:BuildPage:buildcontents.html.twig', array(
            'page' => $page,
            'contents' => $pageContents,
            'notfoundmessage' => 'Erreur: Pas de contenu trouvé.'
        ));
    }

    public function buildContentAction($pageContent, $id = 0, Request $request)
    {

        // $user = $this->getUser();
        // isset($user) && ($user->hasGroup($group) || $this->get('security.authorization_checker')->isGranted(strtoupper('ROLE_' . $group->getName())))
        
        //Call correct controlleur depending type: $pageContent->getContent()->getType()
        switch ($pageContent->getContent()->getType()) {
            case "Image":
                return $this->render('BuilderPageBundle:BuildContent:image.html.twig', array(
                    'pageContent' => $pageContent
                ));
                break;
            case "MainMenu":
                //dump($request);
                return $this->render('BuilderPageBundle:BuildContent:menu.html.twig', array(
                    'pageContent' => $pageContent,
                    'request' => $request
                ));
                break;
            case "Content":
            case "ProdContent":
                return $this->render('BuilderPageBundle:BuildContent:content.html.twig', array(
                    'pageContent' => $pageContent
                ));
                break;
            case "Menu":
                return $this->menuAction( $pageContent );
                break;
            case "Controller":
                //$this->generateUrl('my_login_path');
                return $this->forward(strip_tags($pageContent->getContent()->getContent()), ['request' => $request, 'id' => $id, 'pageContent' => $pageContent]);
                break;
            case "Card":
                $sluglink = null;
                $content_title = null;
                $pageContent_title = $pageContent->getContent()->getTitle();
                if($pageContent_title != null ){
                    $title_elems = explode(">>", $pageContent_title);
                    if(count($title_elems)>1){
                        $content_title = $title_elems[0];
                        $sluglink = $title_elems[1];
                    }
                }
                return $this->render('BuilderPageBundle:BuildContent:card.html.twig', array(
                    'pos' => $pageContent->getPosition(),
                    'pageContent' => $pageContent,
                    'content_title' => $content_title,
                    'slug_link' => $sluglink
                ));
                break;
            case "Carousel":
                //recupérer le carousel avec pageContent.content = carousel name 
                $carousel = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('BuilderPageBundle:Carousel')
                    ->findBy(array('carousel' => strip_tags($pageContent->getContent()->getContent())));
                //le transmettre à la vue

                return $this->render('BuilderPageBundle:BuildContent:carousel.html.twig', array(
                    'carousel' => $carousel,
                    'pageContent' => $pageContent
                ));
                break;
            case "Form":
                //Faire le rendu du formulaire
                //dump($request);
                return $this->forward('BuilderFormBundle:Form:show', ['request' => $request, 'id' => $id, 'pageContent' => $pageContent]);
                break;
            default: //Text
                return $this->render('BuilderPageBundle:BuildContent:text.html.twig', array(
                    'pageContent' => $pageContent
                ));
        }


        // // TRY TO USE HTTP CACHE: error headers are displayed on page...
        // $response = new Response();
        
        // // dump($response->isNotModified($request));
        // // dump($request->isMethodCacheable());
        // // Check that the Response is not modified for the given Request
        // if (!$response->isNotModified($request)) {
            

        //     //$responseContent = null;
        //     //Call correct controlleur depending type: $pageContent->getContent()->getType()
        //     switch ($pageContent->getContent()->getType()) {
        //         case "Image":
        //             $response = $this->render('BuilderPageBundle:BuildContent:image.html.twig', array(
        //                 'pageContent' => $pageContent
        //             ));
        //             break;
        //         case "MainMenu":
        //             $response = $this->render('BuilderPageBundle:BuildContent:menu.html.twig', array(
        //                 'pageContent' => $pageContent
        //             ));
        //             break;
        //         case "Content":
        //         case "ProdContent":
        //             $response = $this->render('BuilderPageBundle:BuildContent:content.html.twig', array(
        //                 'pageContent' => $pageContent
        //             ));
        //             break;
        //         case "Menu":
        //             $response = $this->menuAction($pageContent);
        //             break;
        //         case "Controller":
        //             //$this->generateUrl('my_login_path');
        //             $response = $this->forward(strip_tags($pageContent->getContent()->getContent()), ['request' => $request, 'id' => $id, 'pageContent' => $pageContent]);
        //             break;
        //         case "Card":
        //             $response = $this->render('BuilderPageBundle:BuildContent:card.html.twig', array(
        //                 'pageContent' => $pageContent
        //             ));
        //             break;
        //         case "Carousel":
        //             //recupérer le carousel avec pageContent.content = carousel name 
        //             $carousel = $this->get('doctrine.orm.entity_manager')
        //                 ->getRepository('BuilderPageBundle:Carousel')
        //                 ->findBy(array('carousel' => strip_tags($pageContent->getContent()->getContent())));
        //             //le transmettre à la vue

        //             $response = $this->render('BuilderPageBundle:BuildContent:carousel.html.twig', array(
        //                 'carousel' => $carousel,
        //                 'pageContent' => $pageContent
        //             ));
        //             break;
        //         case "Form":
        //             //Faire le rendu du formulaire
        //             //dump($request);
        //             $response = $this->forward('BuilderFormBundle:Form:show', ['request' => $request, 'id' => $id, 'pageContent' => $pageContent]);
        //             break;
        //         default: //Text
        //             $response = $this->render('BuilderPageBundle:BuildContent:text.html.twig', array(
        //                 'pageContent' => $pageContent
        //             ));
        //     }
        //     $date = new \DateTime();
        //     $response->setLastModified($date);
        //     // dump($response);
        //     //$response->setContent($responseContent);
        // }
        // $response->setMaxAge(300);
        // return $response;

    }

    //Appel du rendu d'un controlleur existant
    // controllerURL est une URL: on va chercher la route déclaré 
    public function buildWithControllerAction($controllerUrl, Request $request)
    {
        $message = "MESSAGE : "; //pour le debug
        $route = null;
        // 1er cas: gestion des contenus FosUserBunde avec prefix /user/
        //tentative de récupération de la route: /user/$controllerUrl
        $route = $this->testMatchUrl('/user/', $controllerUrl, $request);
        if (isset($route) && isset($route["_controller"])) {
            //return new JsonResponse($route);
            //$message = $message . " #route trouvé: /user/".$controllerUrl;
            return $this->forward($route['_controller'], ['request' => $request]);
        }
        $route = $this->testMatchUrl('/user/', $controllerUrl . '/', $request);
        if (isset($route)) {
            //return new JsonResponse($route);
            //$message = $message . " #route trouvé: /user/".$controllerUrl.'/';
            return $this->forward($route['_controller'], ['request' => $request]);
        }
        $route = $this->testMatchUrl('/content/', $controllerUrl, $request);
        if (isset($route)) {
            //return new JsonResponse($route);
            //$message = $message . " #route trouvé: /content/".$controllerUrl;
            return $this->forward($route['_controller'], ['request' => $request]);
        }
        $route = $this->testMatchUrl('/content/', $controllerUrl . '/', $request);
        if (isset($route)) {
            //$message = $message . " #route trouvé: /content/".$controllerUrl.'/';
            return $this->forward($route['_controller'], ['request' => $request]);
        }

        //return new Response($message);
        return new Response(" L'url demandé (" . $controllerUrl . ") n'est pas valide.");


        // //Si la route est une redirection, recurérer la route de la redirection
        // if(isset($route["path"]))
        // try{
        //     //$message = $message.'#try route : /user/+'.$controllerUrl;
        //     $route = $this->get('router')->match('/user/'.$controllerUrl);
        //     if(isset($route["path"]))
        //     {
        //         $message = $message.'#$route["path"]!= null => try redirect path :'.$route["path"];
        //         $route = $this->get('router')->match($route["path"]);
        //     }
        // }
        // catch(\Exception $e){
        //     try{
        //         $message = $message.' # and try route :/user/+'.$controllerUrl.'/';
        //         $route = $this->get('router')->match('/user/'.$controllerUrl.'/');
        //     }
        //     catch(\Exception $e2){
        //         $message = $message.'# no route match';
        //         $route = null;
        //     }
        // }
        // //return new Response($message);
        // //return new JsonResponse($route);
        // if($route != null && is_array($route))
        // {
        //     try{
        //         return $this->forward($route['_controller'], ['request' => $request]);
        //     }
        //     catch(\Exception $e){
        //         return new Response(' url demandé non valide: '.$controllerUrl);
        //     }
        // }
        // return new Response($message);
        // return new JsonResponse($route);
    }

    private function testMatchUrl($prefix, $url, $request)
    {
        try {
            $route = $this->get('router')->match($prefix . $url);
            //Si la route est une redirection, recurérer la route de la redirection
            if (isset($route["path"])) {
                $route = $this->get('router')->match($route["path"]);
            }
            //Si la route semble valide, appeller le controller
            if ($route != null && is_array($route)) {
                if (isset($route["_route"]) && $route["_route"] == "builder_buildpage") {
                    return null;
                } else {
                    return $route;
                }
                //return $this->forward($route['_controller'], ['request' => $request]);
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    // public function imageAction($pageContent, Request $request)
    // {
    //     return $this->render('BuilderPageBundle:BuildContent:image.html.twig', array(
    //         'pageContent' => $pageContent
    //     ));
    // }


    // public function textAction($pageContent, Request $request)
    // {
    //     return $this->render('BuilderPageBundle:BuildContent:text.html.twig', array(
    //         'pageContent' => $pageContent
    //     ));
    // }

    // public function menuAction($pageContent, Request $request)
    // {
    //     return $this->render('BuilderPageBundle:BuildContent:text.html.twig', array(
    //         'pageContent' => $pageContent
    //     ));
    // }

    public function menuAction($pageContent)
    {
        $menuName = $pageContent->getContent()->getContent();

        //IF menu is create with twig and not with knp_menu_render
        $menu = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BuilderPageBundle:Menu')
            ->findOneBy(array('name' => strip_tags($menuName)));

        return $this->render('BuilderPageBundle:BuildContent:menu.html.twig', array(
            'menuName' => $menuName,
            'menu' => $menu,
            'pageContent' => $pageContent
        ));
    }

    public function menuPrincipalAction($menuName, Request $baserequest)
    {
        $currentslug = "";
        $baserequest = $baserequest->get('request');
        if ($baserequest) {
            $currentslug = $baserequest->get('slug');
        }
        //dump($baserequest);
        //dump($baserequest->get('request')->get('slug'));
        if ($menuName == null) {
            $menuName = 'Principal';
        }

        //LOAD MENU PRINCIPAL
        $menu = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BuilderPageBundle:Menu')
            ->findOneBy(array('name' => $menuName));

        //initialisation
        $links = [];
        $i = 0;

        $user = $this->getUser();
        if ($menu != null) {
            foreach ($menu->getMenuPages() as $menuPage) {
                //VERIFIER LES DROITS D'ACCES A LA PAGE:
                // On vérifie que l'utilisateur dispose bien du rôle demandé au niveau de la page:
                // $hasPageRights = true si le group Users est indiqué
                // Sinon on controlle si le user a le group demandé
                // ou si l'utilisateur à le role 'ROLE_'+group.name dans toute les responsabilités calculées
                $hasPageRights = false;
                if (count($menuPage->getPage()->getRights()) == 0) {
                    $hasPageRights = true;
                }
                foreach ($menuPage->getPage()->getRights() as $group) {
                    if ($group->getName() == "All") {
                        $hasPageRights = true; //All users acce
                        break;
                    } elseif (isset($user) && ($user->hasGroup($group) || $this->get('security.authorization_checker')->isGranted(strtoupper('ROLE_' . $group->getName())))) // $user->hasRole(strtoupper('ROLE_'. $group->getName()))))
                    {
                        $hasPageRights = true; // users has rights
                        break;
                    }
                }

                if ($hasPageRights) {
                    $links[$i]["title"] = $menuPage->getPage()->getName();
                    $links[$i]["path"] = $menuPage->getPage()->getSlug();
                    $i = $i + 1;
                }
            }
        }

        return $this->render(':' . $this->container->getParameter('template_repo') . '/views/parts:main-menu.html.twig', array(
            'links' => $links,
            'baserequest' => $baserequest,
            'currentslug' => $currentslug
        ));
    }

    public function menuFooterAction($menuName)
    {
        if ($menuName == null) {
            $menuName = 'Footer';
        }

        //LOAD MENU PRINCIPAL
        $menu = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BuilderPageBundle:Menu')
            ->findOneBy(array('name' => $menuName));

        //initialisation
        $links = [];
        $i = 0;

        $user = $this->getUser();
        if ($menu != null) {
            foreach ($menu->getMenuPages() as $menuPage) {
                //VERIFIER LES DROITS D'ACCES A LA PAGE:
                // On vérifie que l'utilisateur dispose bien du rôle demandé au niveau de la page:
                // $hasPageRights = true si le group Users est indiqué
                // Sinon on controlle si le user a le group demandé
                // ou si l'utilisateur à le role 'ROLE_'+group.name dans toute les responsabilités calculées
                $hasPageRights = false;
                if (count($menuPage->getPage()->getRights()) == 0) {
                    $hasPageRights = true;
                }
                foreach ($menuPage->getPage()->getRights() as $group) {
                    if ($group->getName() == "All") {
                        $hasPageRights = true; //All users acce
                        break;
                    } elseif (isset($user) && ($user->hasGroup($group) || $this->get('security.authorization_checker')->isGranted(strtoupper('ROLE_' . $group->getName())))) // $user->hasRole(strtoupper('ROLE_'. $group->getName()))))
                    {
                        $hasPageRights = true; // users has rights
                        break;
                    }
                }

                if ($hasPageRights) {
                    $links[$i]["title"] = $menuPage->getPage()->getName();
                    $links[$i]["path"] = $menuPage->getPage()->getSlug();
                    $i = $i + 1;
                }
            }
        }

        return $this->render(':' . $this->container->getParameter('template_repo') . '/views/parts:footer-menu.html.twig', array(
            'links' => $links,
        ));
    }

    public function buildSpecificContentAction($contentTitle, Request $request){
        $content = null;
        if ($contentTitle != null) {
            $content = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BuilderPageBundle:Content')
            ->findOneBy(array('title' => $contentTitle));
        }
        // dump($contentTitle);
        $pageContent_temp = new Page_Content();
        if($content != null){
            $pageContent_temp->setContent($content);
        }
        
        return $this->buildContentAction($pageContent_temp, 0, $request);
    }
}
