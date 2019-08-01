<?php
namespace App\Lib\Templating\Helper;

use \Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
/**
 * Description of Url
 *
 * @author ZIgr <zaporozhets.igor at gmail.coml>
 * @date Jun 21, 2019
 * @encoding UTF-8
 *  
 */
class UrlHelper extends Helper
{
    
    public function to($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        return \Application::get('UrlGenerator')->generate($route, $parameters, $referenceType);
    }

    public function current(){
        
    }

    public function getName()
    {
        return 'url';
    }

}
