<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/10/31
 * Time: 2:27 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;


class EditorController extends Controller
{
    protected $config = [];

    function __construct()
    {
        $config_filename = app()->basePath('resources/editor/config.json');
        $this->config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($config_filename)), true);
    }

    protected function doUploadImage(Request $request)
    {
        $maxSize = $this->config['imageMaxSize'] / 1024;
        $limitExtension = implode(',', array_map(function ($ext) {
            return ltrim($ext, '.');
        }, $this->config['imageAllowFiles']));

        $this->validate($request, [
            $this->config['imageFieldName'] => "required|image|mimes:{$limitExtension}|max:{$maxSize}"
        ]);

        $file = $request->file($this->config['imageFieldName']);
        $html_path = app()->basePath('html');
        $upload_path = $this->fullPath($this->config['imagePathFormat']) . '/';

        if ($file->isValid()) {
            $filename = \bin2hex(\random_bytes(10)) . '.' . $file->guessExtension();

            $file->move($html_path . $upload_path, $filename);
            return response()->json([
                'state' => 'SUCCESS',
                'url' => $upload_path . $filename,
                'title' => $file->getClientOriginalName(),
                'original' => $file->getClientOriginalName()
            ]);
        } else {
            throw new FileException($file->getErrorMessage());
        }
    }

    protected function doCatchimage(Request $request)
    {
        $sources = $request->input($this->config['catcherFieldName']);
        $rets = [];

        if ($sources) {
            foreach ($sources as $url) {
                $rets[] = $this->download($url);
            }
        }

        return response()->json([
            'state' => 'SUCCESS',
            'list' => $rets
        ]);
    }

    protected function doListImage(Request $request)
    {
        $defaultSize = $this->config['imageManagerListSize'];

        $start = intval($request->query('start', 0));
        $size = intval($request->query('size', $defaultSize));

        $limitExtension = implode(',', array_map(function ($ext) {
            return ltrim($ext, '.');
        }, $this->config['imageManagerAllowFiles']));
        $webPath = app()->basePath('html');
        $listPath = $webPath . $this->fullPath($this->config['imageManagerListPath']);

        $data = array_map(function ($url) use ($webPath) {
            return [
                'url' => str_replace($webPath, '', $url)
            ];
        }, glob("{$listPath}/*/*/*.\x7B{$limitExtension}\x7D", GLOB_BRACE));

        return response()->json([
            'state' => 'SUCCESS',
            'list' => array_slice($data, $start, $size),
            'start' => $start,
            'total' => count($data)
        ]);
    }

    protected function doConfig(Request $request)
    {
        return response()->json($this->config, 200, [], \JSON_PRETTY_PRINT);
    }

    public function main(Request $request)
    {
        $action = $request->query('action');

        try {
            if (is_string($action) && method_exists($this, "do{$action}")) {
                return call_user_func([$this, "do{$action}"], $request);
            } else {
                throw new FileException('Method error');
            }
        } catch (FileException $e) {
            return response()->json(['state' => $e->getMessage()]);
        }
    }

    private function fullPath($path_template)
    {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = $path_template;
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);
        $format = str_replace('{iphash}', md5($_SERVER['REMOTE_ADDR']), $format);
        return rtrim($format, '/');
    }

    private function download($url)
    {
        $maxSize = $this->config['catcherMaxSize'];
        $limitExtension = array_map(function ($ext) {
            return ltrim($ext, '.');
        }, $this->config['catcherAllowFiles']);
        $allowTypes = array_map(function ($ext) {
            return "image/{$ext}";
        }, $limitExtension);

        $content = file_get_contents($url);
        $img = getimagesizefromstring($content);

        if ($img && in_array($img['mime'], $allowTypes)) {
            $guesser = ExtensionGuesser::getInstance();
            $ext = $guesser->guess($img['mime']);
            $size = strlen($content);

            $html_path = app()->basePath('html');
            $upload_path = $this->fullPath($this->config['catcherPathFormat']);

            if (in_array($ext, $limitExtension) && $size <= $maxSize) {
                if (!is_dir("{$html_path}{$upload_path}")) {
                    mkdir("{$html_path}{$upload_path}", 0777, true);
                }

                $filename = \bin2hex(\random_bytes(10)) . '.' . $ext;
                file_put_contents("{$html_path}{$upload_path}/{$filename}", $content);

                return [
                    "url" => "{$upload_path}/{$filename}",
                    "source" => $url,
                    "state" => "SUCCESS"
                ];
            } else {
                throw new FileException("file extension .{$ext} or size {$size} error");
            }

        } else {
            throw new FileException('Only support catching image file');
        }
    }
}