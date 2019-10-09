<?php

namespace Builder\PageBundle\Service\SiteMap;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SiteMap
{
    private $router;
    private $em;

    public function __construct(RouterInterface $router, ObjectManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * Génère l'ensemble des valeurs des balises <url> du sitemap.
     *
     * @return array Tableau contenant l'ensemble des balise url du sitemap.
     */
    public function generer()
    {
        $urls = [];

        $pages = $this->em->getRepository('BuilderPageBundle:Page')->findAll();

        foreach ($pages as $page) {
            if ($page->getSlug() != "default" && $page->getSlug() != "actu") {
                $urls[] = [
                    'loc' => $this->router->generate('builder_buildpage', ['slug' => $page->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL)
                ];
            }
            if($page->getSlug() == "actu"){
                $actus = $this->em->getRepository('SiteBundle:Article')->findAll();
                foreach ($actus as $actu) {
                    if($actu->getIsActive())
                        $urls[] = [
                            'loc' => $this->router->generate('builder_buildpageid', ['slug' => $page->getSlug(), 'id' => $actu->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                            'lastmod' =>  $actu->getPublishedAt()->format('Y-m-d')
                        ];
                }
            }
        }

        return $urls;
    }
}
