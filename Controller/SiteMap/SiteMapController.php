<?php

namespace Builder\PageBundle\Controller\SiteMap;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteMapController extends Controller
{

    /**
     * Génère le sitemap du site.
     *
     **#Route("/sitemap.{_format}", name="front_sitemap", Requirements={"_format" = "xml"})
     */
    public function siteMapAction()
    {
        return $this->render(
            'BuilderPageBundle:SiteMap:sitemap.xml.twig',
            ['urls' => $this->get('pagebundle.sitemap')->generer()]
        );
    }

}