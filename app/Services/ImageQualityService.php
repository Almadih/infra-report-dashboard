<?php

namespace App\Services;

use App\Models\Image;
use File;
use Illuminate\Support\Facades\Storage;
use Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ImageQualityService
{
    protected float $blurThreshold = 100;

    protected int $minWidth = 800;

    protected int $minHeight = 600;

    // Minimum acceptable file size in kilobytes for a "decent" image
    protected int $minFileSizeKB = 100;

    public function isLowQuality(Image $image): bool
    {
        $file = Storage::path($image->path);

        if (! file_exists($file)) {
            return true;
        }
        // 1. Check file size
        $dimensions = getimagesize($file);

        $fileSize = File::size($file);
        Log::info("File size: $fileSize");
        Log::info("Dimensions: width: $dimensions[0], height: $dimensions[1]");
        if ($fileSize / 1024 < $this->minFileSizeKB) {
            return true;
        }

        if ($dimensions === false) {
            return true; // Not a valid image
        }

        $width = $dimensions[0];
        $height = $dimensions[1];

        if ($width < $this->minWidth || $height < $this->minHeight) {
            return false;
        }

        return false;
    }

    /**
     * Checks if an image is likely blurry using Laplacian variance.
     *
     * @return bool Returns true if the image is considered blurry, false otherwise.
     */
    public function isBlurry(Image $file): bool
    {
        $imagePath = Storage::path($file->path);
        $pythonPath = '/usr/bin/python'; // Or find with `which python3`
        $scriptPath = base_path('app/scripts/detect_blur.py');

        // 3. Create the process to run the Python script
        $process = new Process([
            $pythonPath,
            $scriptPath,
            '--image', $imagePath,
            '--threshold', $this->blurThreshold,
        ]);

        try {
            // 4. Run the process
            $process->mustRun();

            // 5. Get the output and decode the JSON
            $output = $process->getOutput();
            Log::info("Output: $output");
            $result = json_decode($output, true);

            // Check if the python script itself encountered an error
            if (! $result || ! $result['success']) {
                return false;
            }

            // 6. Return the result to the client

            return $result['is_blurry'];

        } catch (ProcessFailedException $exception) {
            // Handle cases where the process could not be executed
            Log::error($exception->getMessage());

            return false;
        }
    }
}
