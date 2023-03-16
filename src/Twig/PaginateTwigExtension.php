<?php

namespace App\Twig;

use Illuminate\Database\Eloquent\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginateTwigExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'paginate';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('paginate', [$this, 'paginate']),
        ];
    }

    public function paginate(): string
    {
        $params = func_get_args();

        /** @var Collection $params */

        list($params, $uri, $currentUrl) = $params;

        $html = "";

        foreach ($params->toArray() as $page) {
            $page = (object) $page;

            $url = $uri . '/' . str_replace(['/', '?'], '', $page->url);
            $url = str_replace(['='], '', $url);
            $url = str_replace(['page'], '', $url);


            $url = $currentUrl . '/' . $url;
            $label = $page->label;

            $active = ($page->active ? 'current-page' : '');

            $button = "<a href='{$url}' class='blog-page transition {$active}'>{$label}</a> ";

            if ($page->label == 'Previous') {
                $button = "<a href='#' class='prevposts-link'><i class='fa fa-caret-left'></i></a> ";
            }

            if ($page->label == 'Next') {
                $button = "<a href='{$url}' class='nextposts-link'><i class='fa fa-caret-right'></i></a> ";
            }

            $html .= $button;
        }

        return $html;
    }
}
