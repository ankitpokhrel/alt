<?php

namespace Alt;

use CV\DNN\Net;
use CV\{Mat, Size, Scalar};

use function CV\imread;
use function CV\DNN\{blobFromImage, readNetFromTensorflow};

class SsdDnn
{
    /** @const string */
    const MODEL_PATH = __DIR__ . '/../models/ssdlite_mobilenet_v2_coco/';

    /** @const string */
    const COCO_LABELS = 'coco-labels.txt';

    /** @const string */
    const INFERENCE_PROTOBUF = 'frozen_inference_graph.pb';

    /** @const string */
    const MODEL_FORMAT = 'ssdlite_mobilenet_v2_coco.pbtxt';

    /**
     * Get categories.
     *
     * @return array
     */
    public static function getCategories() : array
    {
        return explode("\n", file_get_contents(self::MODEL_PATH . self::COCO_LABELS));
    }

    /**
     * Get blob from image.
     *
     * @param string $imagePath
     *
     * @return Mat
     */
    public static function blobFromImage(string $imagePath) : Mat
    {
        $image = imread($imagePath);
        $size  = new Size(300, 300);
        $mean  = new Scalar(127.5, 127.5, 127.5);

        return blobFromImage($image, 0.013, $size, $mean, true, false);
    }

    /**
     * Read neural net from Tensorflow.
     *
     * @return Net
     */
    public static function readNetFromTensorflow() : Net
    {
        return readNetFromTensorflow(
            self::MODEL_PATH . self::INFERENCE_PROTOBUF,
            self::MODEL_PATH . self::MODEL_FORMAT
        );
    }
}
