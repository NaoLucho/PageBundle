<?php
namespace Builder\PageBundle\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder extends Controller implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function buildMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menuName = $options["menuName"];
        $request = $options["request"];
        $route = 'builder_buildpage';
        $routeparam = [];
        //dump($request);
        if($request != null && $request->get('_route')!=null){
            $route = $request->get('_route');
            $routeparam = array('username'=>$request->get('username'));
        }
        // dump($request->get('_route'));
        // dump($request->get('username'));
        //if($menuName == null)
        //    $menuName = 'Principal';
        // access services from the container!
        $em = $this->container->get('doctrine')->getManager();

        $menuprincipal = $em->getRepository('BuilderPageBundle:Menu')
        ->findOneBy(array('name' => $menuName));
        
        //dump($menuName);
        if($menuprincipal != null && $menuprincipal->getMenuPages() != null)
        {
            //$parentName = "";
            $menuprev = $menu;

            //Pour récupérer le user avec get user j'ai eu a extends Controller qui n'était pas use
            // Dans l'idéal il faudrait passer le user en paramettres dans $options
            $user = $this->getUser();
            foreach($menuprincipal->getMenuPages() as $menuPage)
            {
                //VERIFIER LES DROITS D'ACCES A LA PAGE:
                // On vérifie que l'utilisateur dispose bien du rôle demandé au niveau de la page:
                // $hasPageRights = true si le group Users est indiqué
                // Sinon on controlle si le user a le group demandé
                // ou si l'utilisateur à le role 'ROLE_'+group.name dans toute les responsabilités calculées
                $hasPageRights = false;
                foreach ($menuPage->getPage()->getRights() as $group) {
                    if ($group->getName() == "All") {
                        $hasPageRights = true; //All users acce
                        break;
                    } elseif (isset($user) && ($user->hasGroup($group) || $this->get('security.authorization_checker')->isGranted(strtoupper('ROLE_' . $group->getName()))))// $user->hasRole(strtoupper('ROLE_'. $group->getName()))))
                    {
                        $hasPageRights = true; // users has rights
                        break;
                    }
                }

                if($hasPageRights)
                {
                    //$add = "";
                    $positions = explode('.', $menuPage->getPosition());
                    //$menulevel = count($positions);
                    if(count($positions) < 2 )
                    {
                        //parent est $menu
                        $menuprev = $menu;
                        $add = "root";
                    }
                    else
                    {
                        //$add = count($positions) . '<'. $menuprev->getLevel() .'/';
                        //$precondition = count($positions) < $menuprev->getLevel();
                        while (count($positions) < $menuprev->getLevel()+1) {
                            //$add = $menuprev->getName().'+lvl'. $menuprev->getLevel().'/';
                            $menuprev = $menuprev->getParent();
                        }
                        $menuprev->setAttribute('dropdown', 1);
    
                    }
                    $name = $menuPage->getPage()->getName();
                    
                    $routeparam = array('slug' => $menuPage->getPage()->getSlug());
                    $attributes = [];
                    if( $menuPage->getPage()->getClass() != ""){
                        $attributes = array('class' => $menuPage->getPage()->getClass());
                    }
                        
                    $menuprev = $menuprev->addChild(
                        $name,
                        //$add.'#'. $menuPage->getPage()->getName(). $menuPage->getPosition(),
                        array(
                            'route' => 'builder_buildpage',
                            'routeParameters' => $routeparam,
                            'attributes' => $attributes
                        )
                    );
                    
                    // $menuItem->getLevel();
                    // $menuItem->getParent();

                    // $parentName = $menuPage->getPage()->getName();
                }
            }
        }

        //ADD CONNEXION:
        // if($this->get('security.authorization_checker')->isGranted("IS_AUTHENTICATED_REMEMBERED"))
        // {
        //     $menuprev = $menu->addChild(
        //         $user->getUsername(),
        //         array(
        //             'route' => 'builder_buildpage',
        //             'routeParameters' => array('slug' => 'profile'),
        //         )
        //     );
        //     $menuprev = $menu->addChild(
        //         "Déconnexion",
        //         array(
        //             'route' => 'fos_user_security_logout',
        //         )
        //     );
        // } else {
        //     $menuprev = $menu->addChild(
        //         "Connexion",
        //         array(
        //             'route' => 'site_login',
        //             'linkAttributes' => array(
        //                 'id' => 'login',
        //                 'data-toggle' => 'modal',
        //                 'data-target' => '#modalLogin'
        //             )
        //         )
        //     );
        // }

        return $menu;
    }
}