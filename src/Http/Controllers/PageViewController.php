<?php

namespace Startupful\WebpageManager\Http\Controllers;

use Startupful\WebpageManager\Models\Page;
use Startupful\WebpageManager\Models\WebpageElement;
use Startupful\StartupfulPlugin\Models\PluginSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class PageViewController extends Controller
{
    public function showMain()
    {
        $page = Page::where('slug', 'main')->first();

        if (!$page) {
            abort(404);
        }

        return $this->renderPage($page);
    }

    public function show($slug)
    {
        try {
            if ($this->isAsset($slug)) {
                return $this->handleAssetRequest($slug);
            }

            $page = Page::where('slug', $slug)->first();
            
            if (!$page) {
                abort(404);
            }

            if (!$page->is_published) {
                return $this->handleUnpublished($page);
            }

            // Fetch active header and footer elements
            $headers = WebpageElement::headers()->active()->get();
            $footers = WebpageElement::footers()->active()->get();

            // Fetch menu data
            $menuData = $this->getMenuData();
            $headerMenuData = $this->getHeaderMenuData($menuData);
            $footerMenuData = $this->getFooterMenuData($menuData);
            $customStyles = $this->getCustomStyles();

            return view('webpage-manager::page-view', compact('page', 'headers', 'footers', 'menuData', 'headerMenuData', 'footerMenuData', 'customStyles'));
        } catch (\Exception $e) {
            Log::error("Error showing page: " . $e->getMessage());
            return $this->handleError($e);
        }
    }

    private function handleUnpublished($page)
    {
        // 게시되지 않은 페이지에 대한 처리
        if (auth()->check() && auth()->user()->can('view unpublished pages')) {
            return view('webpage-manager::page-preview', compact('page'));
        }
        abort(404);
    }

    private function handleError($exception)
    {
        // 오류 처리
        return response()->view('webpage-manager::errors.500', ['exception' => $exception], 500);
    }

    private function isAsset($slug)
    {
        // 애셋 파일인지 확인 (예: .js, .css, .png 등)
        return pathinfo($slug, PATHINFO_EXTENSION) != '';
    }

    private function handleAssetRequest($slug)
    {
        // 애셋 요청 처리 (예: public 폴더에서 파일 찾기)
        $path = public_path($slug);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404);
    }

    private function getMenuData()
    {
        $menuSetting = PluginSetting::where('key', 'menu')->first();
        $menuData = $menuSetting ? json_decode($menuSetting->value, true) : null;

        return $menuData;
    }

    private function getHeaderMenuData($menuData)
    {
        if (isset($menuData['unify_menus']) && $menuData['unify_menus']) {
            return $menuData['unified_menu'] ?? [];
        }
        return $menuData['header_menu'] ?? [];
    }

    private function getFooterMenuData($menuData)
    {
        if (isset($menuData['unify_menus']) && $menuData['unify_menus']) {
            return $menuData['unified_menu'] ?? [];
        }
        return $menuData['footer_menu'] ?? [];
    }

    private function getCustomStyles()
    {
        $themeSetting = PluginSetting::where('key', 'theme')->first();
        
        if (!$themeSetting) {
            return $this->getDefaultStyles();
        }

        $themeData = json_decode($themeSetting->value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->getDefaultStyles();
        }

        $containerWidth = $themeData['container_width'] ?? '1280px';
        $primaryColor = $themeData['primary_color'] ?? '#000000';
        $secondaryColor = $themeData['secondary_color'] ?? '#ffffff';

        $styles = ".custom-container-width {";
        if ($containerWidth === '100%') {
            $styles .= "width: 100%;";
        } else {
            $styles .= "max-width: {$containerWidth}px;";
        }
        $styles .= "}";

        $styles .= ".custom-main-color { color: {$primaryColor}; }";
        $styles .= ".custom-secondary-color { color: {$secondaryColor}; }";

        return $styles;
    }

    private function getDefaultStyles()
    {
        return "
            .custom-container-width { max-width: 1280px; }
            .custom-main-color { color: #000000; }
            .custom-secondary-color { color: #ffffff; }
        ";
    }
    
}