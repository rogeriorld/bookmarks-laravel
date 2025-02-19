<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use WebPConvert\WebPConvert;

class ImageHelper
{
    public static function processCoverImage($coverImage, $bookmarkID, $directoryPath)
    {
        if ($coverImage) {
            $imageData = $coverImage;
            // Check if the image is a base64 string and if it is not webp format
            if (strpos($coverImage, 'data:image/webp;base64,') === false) {
                $imageData = self::convertImageToWebP($coverImage);
            } else {
                $imageData = self::convertWebPToLowerQuality($imageData, $directoryPath, 80);
            }

            // Remove o prefixo "data:image/webp;base64," da string base64
            $imageData = str_replace('data:image/webp;base64,', '', $imageData);

            // Substitui espaços por sinais de mais (+) na string base64
            $imageData = str_replace(' ', '+', $imageData);

            // Decodifica a string base64 para obter os dados binários da imagem
            $decodedImageData = base64_decode($imageData);

            $imageName = 'cover_image_' . $bookmarkID . '.cwebp';

            Storage::makeDirectory($directoryPath);

            $directoryPath = $directoryPath . $imageName;

            Storage::put($directoryPath, encrypt($decodedImageData));
        }
    }

    public static function convertImageToWebP($imageData)
    {
        // Just convert PNG and JPEG images
        // Check what will be the extension of the image fot the temporary file
        // data:image/jpeg;base64,
        // data:image/png;base64,
        // data:image/webp;base64,
        // Use regex to check if the image is a PNG or JPEG or any other format
        preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches);
        $extension = $matches[1];
        $imageData = str_replace("data:image/{$matches[1]};base64,", '', $imageData);
        // $extension = strpos($imageData, 'image/png') !== false ? 'png' : 'jpeg';
        // if ($extension === 'png') {
        //     $imageData = str_replace('data:image/png;base64,', '', $imageData);
        // } else {
        //     $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        // }

        $imageData = str_replace(' ', '+', $imageData);
        $decodedImageData = base64_decode($imageData);

        // Create a temporary file to save the image
        $tempFile = tempnam(sys_get_temp_dir(), $extension);
        file_put_contents($tempFile, $decodedImageData);

        // Create a temporary file to save the webp image
        $webpPath = storage_path('app/public/temp_image.webp');
        // Converter a imagem PNG para o formato WebP
        WebPConvert::convert($tempFile, $webpPath);

        // Ler a imagem WebP gerada e codificá-la em base64
        $webpData = file_get_contents($webpPath);
        $base64WebP = 'data:image/webp;base64,' . base64_encode($webpData);

        // Remover os arquivos temporários
        unlink($tempFile);
        unlink($webpPath);

        return $base64WebP;
    }

    public static function convertWebPToLowerQuality($imageData, $quality = 80)
    {
        // Define as opções de conversão
        $options = [
            'quality' => $quality,
        ];

        $imageData = str_replace("data:image/webp;base64,", '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);

        $decodedImageData = base64_decode($imageData);


        // Create a temporary file to save the image
        $tempFile = tempnam(sys_get_temp_dir(), 'webp');
        file_put_contents($tempFile, $decodedImageData);
        // Load the WebP file
        $im = imagecreatefromwebp($tempFile);
        // Ensure the directory exists
        $directory = 'app/public';
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        // Convert it to a jpeg file with 100% quality
        imagejpeg($im, storage_path('app/public/temp.jpeg'), 100);

        // Create a temporary file to save the webp image
        $webpPath = storage_path('app/public/temp_image.webp');

        // Converte a imagem WebP para uma versão de menor qualidade
        WebPConvert::convert(storage_path('app/public/temp.jpeg'), $webpPath);

        // Ler a imagem WebP gerada e codificá-la em base64
        $webpData = file_get_contents($webpPath);
        $base64WebP = 'data:image/webp;base64,' . base64_encode($webpData);

        // Remover os arquivos temporários
        unlink($tempFile);
        unlink($webpPath);
        imagedestroy($im);

        return $base64WebP;
    }
}
