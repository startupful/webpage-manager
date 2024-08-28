<?php

namespace Startupful\WebpageManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class PreviewController extends Controller
{
    public function render(Request $request)
    {
        $code = $request->input('code');
        $type = $request->input('type');

        // 헤더나 푸터 코드를 독립적으로 렌더링
        $content = View::make('webpage-manager::components.rendered-element', [
            'content' => $code,
            'type' => $type
        ])->render();

        // 기본 레이아웃을 포함한 HTML을 생성합니다.
        $html = View::make('webpage-manager::layouts.preview', [
            'content' => $content,
            'type' => $type
        ])->render();

        return response($html);
    }
}