<?php

if (!function_exists('breadcrumbs')) {
    function breadcrumbs($name, $params = [])
    {
        $breadcrumbs = config("breadcrumbs.$name");
        if (!$breadcrumbs) {
            return '';
        }

        $trail = [];
        while ($breadcrumbs) {
            $title = $breadcrumbs['title'];
            foreach ($params as $key => $value) {
                $title = str_replace(":$key", $value, $title);
            }

            $trail[] = [
                'title' => $title,
                'url' => route($breadcrumbs['route'], $params),
            ];

            $breadcrumbs = isset($breadcrumbs['parent']) ? config("breadcrumbs.{$breadcrumbs['parent']}") : null;
        }

        return array_reverse($trail);
    }
}
